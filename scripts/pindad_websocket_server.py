"""
High-Performance Native Industrial WebSocket Server for SIKOMAT AC PT PINDAD (PERSERO)
RFC 6455 Standard Compliant • Multi-Client Event-Driven Broadcaster
Zero external dependencies required • 100% Air-Gapped Network Ready
"""
import sys
import os
if hasattr(sys.stdout, 'reconfigure'):
    try:
        sys.stdout.reconfigure(encoding='utf-8')
    except Exception:
        pass

import socket
import threading
import hashlib
import base64
import struct
import json
import time

WS_PORT = 8080
MQTT_HOST = "127.0.0.1"
MQTT_PORT = 1883

clients = set()
clients_lock = threading.Lock()

def encode_ws_frame(message_text):
    data = message_text.encode('utf-8')
    length = len(data)
    frame = bytearray([0x81]) # Fin + Text frame
    if length <= 125:
        frame.append(length)
    elif length <= 65535:
        frame.append(126)
        frame.extend(struct.pack('>H', length))
    else:
        frame.append(127)
        frame.extend(struct.pack('>Q', length))
    frame.extend(data)
    return bytes(frame)

def broadcast_message(data_dict):
    if isinstance(data_dict, dict):
        payload = json.dumps(data_dict)
    else:
        payload = str(data_dict)
    
    frame = encode_ws_frame(payload)
    with clients_lock:
        dead_clients = set()
        for client in list(clients):
            try:
                client.sendall(frame)
            except Exception:
                dead_clients.add(client)
        for dead in dead_clients:
            clients.discard(dead)
            try:
                dead.close()
            except Exception:
                pass

def handle_connection(sock, addr):
    try:
        # Read initial HTTP request line and headers
        request_bytes = b''
        while b'\r\n\r\n' not in request_bytes:
            chunk = sock.recv(1024)
            if not chunk:
                break
            request_bytes += chunk

        if not request_bytes:
            sock.close()
            return

        header_part, _, body_start = request_bytes.partition(b'\r\n\r\n')
        header_text = header_part.decode('utf-8', errors='ignore')
        lines = header_text.split('\r\n')
        first_line = lines[0] if lines else ''

        headers = {}
        for line in lines[1:]:
            if ':' in line:
                k, v = line.split(':', 1)
                headers[k.strip().lower()] = v.strip()

        # 1. Check if this is an HTTP POST to /broadcast
        if first_line.startswith('POST ') and ('/broadcast' in first_line or '/api/broadcast' in first_line):
            content_length = int(headers.get('content-length', 0))
            body_bytes = body_start
            while len(body_bytes) < content_length:
                chunk = sock.recv(min(4096, content_length - len(body_bytes)))
                if not chunk:
                    break
                body_bytes += chunk
            
            try:
                body_str = body_bytes.decode('utf-8', errors='ignore')
                event_data = json.loads(body_str)
                broadcast_message(event_data)
                resp_body = b'{"success":true}\n'
            except Exception as e:
                resp_body = b'{"error":true}\n'

            resp = (
                b"HTTP/1.1 200 OK\r\n"
                b"Content-Type: application/json\r\n"
                b"Access-Control-Allow-Origin: *\r\n"
                b"Connection: close\r\n"
                b"Content-Length: " + str(len(resp_body)).encode('ascii') + b"\r\n\r\n" + resp_body
            )
            try:
                sock.sendall(resp)
                sock.shutdown(socket.SHUT_RDWR)
            except Exception:
                pass
            sock.close()
            return

        # 2. Check if WebSocket Upgrade Request
        sec_key = headers.get('sec-websocket-key')
        if sec_key and 'upgrade' in headers.get('connection', '').lower() and headers.get('upgrade', '').lower() == 'websocket':
            guid = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11"
            accept_raw = hashlib.sha1((sec_key + guid).encode('utf-8')).digest()
            accept_key = base64.b64encode(accept_raw).decode('utf-8')

            handshake_resp = (
                "HTTP/1.1 101 Switching Protocols\r\n"
                "Upgrade: websocket\r\n"
                "Connection: Upgrade\r\n"
                f"Sec-WebSocket-Accept: {accept_key}\r\n\r\n"
            )
            sock.sendall(handshake_resp.encode('utf-8'))

            with clients_lock:
                clients.add(sock)

            # Send welcome frame
            welcome = {
                "type": "connection_established",
                "message": "SIKOMAT AC WebSocket Server Active",
                "timestamp": time.time(),
                "connected_clients": len(clients)
            }
            sock.sendall(encode_ws_frame(json.dumps(welcome)))

            # Keep reading frames from client
            while True:
                raw = sock.recv(2)
                if not raw or len(raw) < 2:
                    break
                b1, b2 = raw[0], raw[1]
                opcode = b1 & 0x0F
                if opcode == 0x08: # Close frame
                    break

                payload_len = b2 & 0x7F
                if payload_len == 126:
                    ext = sock.recv(2)
                    if len(ext) < 2: break
                    payload_len = struct.unpack('>H', ext)[0]
                elif payload_len == 127:
                    ext = sock.recv(8)
                    if len(ext) < 8: break
                    payload_len = struct.unpack('>Q', ext)[0]
                
                is_masked = (b2 & 0x80) != 0
                mask_key = sock.recv(4) if is_masked else b''
                data = b''
                while len(data) < payload_len:
                    chunk = sock.recv(payload_len - len(data))
                    if not chunk: break
                    data += chunk

                if opcode == 0x09: # Ping -> Pong
                    sock.sendall(bytearray([0x8A, 0x00]))
            return

        # Fallback 404
        resp = b"HTTP/1.1 404 Not Found\r\nContent-Length: 0\r\n\r\n"
        sock.sendall(resp)
        sock.close()
    except Exception:
        try:
            sock.close()
        except Exception:
            pass
    finally:
        with clients_lock:
            clients.discard(sock)

def start_ws_server(port=8080):
    server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    server.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    server.bind(("0.0.0.0", port))
    server.listen(128)
    print(f"[SIKOMAT AC] WebSocket Realtime Server active on ws://0.0.0.0:{port}")
    
    while True:
        try:
            client_sock, client_addr = server.accept()
            t = threading.Thread(target=handle_connection, args=(client_sock, client_addr), daemon=True)
            t.start()
        except Exception:
            time.sleep(0.2)

def start_mqtt_bridge():
    try:
        import paho.mqtt.client as mqtt
        def on_connect(client, userdata, flags, rc, properties=None):
            client.subscribe("pindad/#")

        def on_message(client, userdata, msg):
            try:
                topic = msg.topic
                payload_str = msg.payload.decode('utf-8', errors='ignore')
                event = {
                    "type": "mqtt_event",
                    "topic": topic,
                    "payload": payload_str,
                    "timestamp": time.time()
                }
                try:
                    event["data"] = json.loads(payload_str)
                except Exception:
                    pass
                broadcast_message(event)
            except Exception:
                pass

        client = mqtt.Client(mqtt.CallbackAPIVersion.VERSION2 if hasattr(mqtt, 'CallbackAPIVersion') else None, "Pindad_WS_Bridge")
        client.on_connect = on_connect
        client.on_message = on_message
        client.connect(MQTT_HOST, MQTT_PORT, 60)
        client.loop_forever()
    except Exception as e:
        print(f"MQTT Bridge note: {e}")

if __name__ == "__main__":
    t_mqtt = threading.Thread(target=start_mqtt_bridge, daemon=True)
    t_mqtt.start()
    start_ws_server(WS_PORT)
