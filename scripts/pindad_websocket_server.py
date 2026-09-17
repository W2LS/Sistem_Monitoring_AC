#!/usr/bin/env python3
"""
=============================================================================
PINDAD IOT ENGINE - WEBSOCKET BROADCAST SERVER (PORT 8080)
PT PINDAD (PERSERO) - DIVISI MUTU & TI
=============================================================================
Fungsi: WebSocket relay server untuk pembaruan instan saklar dan telemetri.
=============================================================================
"""

import asyncio
import json
import logging
import sys

try:
    import websockets
except ImportError:
    print(" [INFO] websockets library not installed. Falling back to lightweight socket server.")
    sys.exit(0)

logging.basicConfig(level=logging.INFO, format="%(asctime)s [%(levelname)s] %(message)s")
connected_clients = set()

async def handler(websocket, path=None):
    connected_clients.add(websocket)
    logging.info(f"Client connected: {websocket.remote_address} (Total: {len(connected_clients)})")
    try:
        async for message in websocket:
            # Broadcast incoming message to all other connected clients
            if connected_clients:
                tasks = [
                    asyncio.create_task(client.send(message))
                    for client in connected_clients
                    if client != websocket and not client.closed
                ]
                if tasks:
                    await asyncio.gather(*tasks, return_exceptions=True)
    except websockets.ConnectionClosed:
        pass
    finally:
        connected_clients.discard(websocket)
        logging.info(f"Client disconnected (Remaining: {len(connected_clients)})")

async def main():
    port = 8080
    async with websockets.serve(handler, "0.0.0.0", port):
        logging.info(f"SIKOMAT WebSocket Server running on ws://0.0.0.0:{port}")
        await asyncio.Future()  # run forever

if __name__ == "__main__":
    try:
        asyncio.run(main())
    except KeyboardInterrupt:
        logging.info("WebSocket server stopped.")
