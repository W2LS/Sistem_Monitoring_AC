import paho.mqtt.client as mqtt
import time

BLYNK_AUTH = "2zT3Crp6HA5DZQaxI26aftTrFUAuwo3F"

def on_connect(client, userdata, flags, rc, *args):
    print("Blynk MQTT Connection status code:", rc)
    if rc == 0:
        print("Connected to Blynk MQTT successfully!")
        client.subscribe("downlink/ds/#")
        client.publish("ds/V0", "2.15")
        client.publish("ds/V1", "2.08")
        client.publish("ds/V2", "930")
        client.publish("ds/V3", "1")
        client.publish("ds/V4", "1")

def on_message(client, userdata, msg):
    print(f"Received from Blynk App: Topic={msg.topic} Payload={msg.payload.decode('utf-8')}")

client = mqtt.Client(client_id="Blynk_Pindad_RPI")
client.username_pw_set("device", BLYNK_AUTH)
client.on_connect = on_connect
client.on_message = on_message

print("Connecting to Blynk MQTT broker (blynk.cloud:1883)...")
try:
    client.connect("blynk.cloud", 1883, 60)
    client.loop_start()
    time.sleep(4)
    client.loop_stop()
    client.disconnect()
except Exception as e:
    print("Blynk MQTT test error:", e)
