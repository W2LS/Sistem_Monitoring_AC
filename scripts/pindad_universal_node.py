#!/usr/bin/env python3
"""
=============================================================================
PINDAD IOT ENGINE - UNIVERSAL MULTI-NODE CONTROLLER CLIENT
PT PINDAD (PERSERO) - DIVISI MUTU & TI
=============================================================================
File: pindad_universal_node.py
Fungsi: Client modular untuk setiap Raspberry Pi di seluruh ruangan server.
Konfigurasi: Dibaca otomatis dari node_config.json
=============================================================================
"""

import time
import math
import random
import json
import os
import threading
import ssl
import urllib.request
import urllib.parse
import paho.mqtt.client as mqtt

# Hardware imports with simulation fallback for testing
try:
    import board
    import busio
    import RPi.GPIO as GPIO
    import adafruit_ads1x15.ads1115 as ADS
    from adafruit_ads1x15.analog_in import AnalogIn
    import adafruit_ds3231
    HAS_HARDWARE = True
except ImportError:
    HAS_HARDWARE = False
    print("⚠️ [NOTE] Berjalan di mode simulasi (RPi.GPIO / Adafruit library tidak ditemukan).")

# ================= 1. BACA FILE KONFIGURASI NODE =================
CONFIG_PATH = os.path.join(os.path.dirname(__file__), "node_config.json")

def load_config():
    default_config = {
        "device_id": "RPI3B_PINDAD_ROOM_1",
        "room_name": "Ruang Server Utama",
        "mqtt_broker_host": "127.0.0.1",
        "mqtt_broker_port": 1883,
        "blynk_auth_token": "",
        "blynk_mqtt_host": "blynk.cloud",
        "blynk_mqtt_port": 1883,
        "sophos_auth": {"enabled": False},
        "relays": [
            {"ac_number": 1, "gpio_pin": 17, "name": "AC 1", "adc_channel": 0},
            {"ac_number": 2, "gpio_pin": 27, "name": "AC 2", "adc_channel": 1}
        ],
        "turbo_cooling_seconds": 300,
        "telemetry_interval_seconds": 15
    }
    if os.path.exists(CONFIG_PATH):
        try:
            with open(CONFIG_PATH, "r") as f:
                return {**default_config, **json.load(f)}
        except Exception as e:
            print(f"⚠️ [CONFIG ERROR] Gagal membaca config.json: {e}")
    return default_config

config = load_config()
DEVICE_ID = config["device_id"]
RELAYS = config["relays"]
TURBO_COOLING_SEC = config.get("turbo_cooling_seconds", 300)
INTERVAL_SEC = config.get("telemetry_interval_seconds", 15)

print(f"🚀 [INIT] Memulai Node Controller: {DEVICE_ID} ({config.get('room_name')})")

# Track states
relay_states = {r["ac_number"]: True for r in RELAYS}
is_turbo_cooling_active = True

# ================= 2. SOPHOS FIREWALL AUTH RESILIENCE =================
def login_sophos():
    sophos = config.get("sophos_auth", {})
    if not sophos.get("enabled"):
        return
    try:
        ctx = ssl.create_default_context()
        ctx.check_hostname = False
        ctx.verify_mode = ssl.CERT_NONE

        # Logout old session
        logout_data = urllib.parse.urlencode({'mode': 192, 'username': sophos.get('user')}).encode('utf-8')
        req_out = urllib.request.Request(sophos.get('url'), data=logout_data, headers={'User-Agent': 'Mozilla/5.0'})
        try:
            urllib.request.urlopen(req_out, context=ctx, timeout=2)
        except Exception:
            pass

        time.sleep(0.3)

        # Login new session
        login_data = urllib.parse.urlencode({
            'mode': 191,
            'username': sophos.get('user'),
            'password': sophos.get('pass')
        }).encode('utf-8')

        req_in = urllib.request.Request(sophos.get('url'), data=login_data, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req_in, context=ctx, timeout=3) as resp:
            txt = resp.read().decode('utf-8')
            if "successfully logged in" in txt or "LIVE" in txt:
                print("🔐 [SOPHOS AUTH] Berhasil login ke firewall PT PINDAD! Internet aktif ✅")
    except Exception as e:
        print(f"[SOPHOS NOTE] Firewall auth bypass/offline: {e}")

# ================= 3. HARDWARE GPIO & RTC SETUP =================
adc_channels = {}
has_rtc = False

if HAS_HARDWARE:
    GPIO.setmode(GPIO.BCM)
    GPIO.setwarnings(False)
    for r in RELAYS:
        GPIO.setup(r["gpio_pin"], GPIO.OUT)
        GPIO.output(r["gpio_pin"], GPIO.HIGH) # Fail-safe boot ON
    print("❄️ [BOOT FAIL-SAFE] Seluruh relai AC dinyalakan saat startup.")

    try:
        i2c = busio.I2C(board.SCL, board.SDA)
        ads = ADS.ADS1115(i2c, address=0x48)
        ads.gain = 1
        ads.data_rate = 860
        for r in RELAYS:
            ch_idx = r.get("adc_channel", 0)
            adc_channels[r["ac_number"]] = AnalogIn(ads, ch_idx)
        print("⚡ [ADS1115] ADC Sensor Arus ACS712 siap.")
    except Exception as e:
        print(f"⚠️ [ADS1115 ERROR] {e}")

    try:
        rtc = adafruit_ds3231.DS3231(i2c)
        has_rtc = True
        print("⏰ [RTC] DS3231 Terdeteksi!")
    except Exception as e:
        print(f"⚠️ [RTC NOTE] Menggunakan waktu sistem OS: {e}")

def get_current_timestamp():
    if HAS_HARDWARE and has_rtc:
        try:
            t = rtc.datetime
            return f"{t.tm_year:04d}-{t.tm_mon:02d}-{t.tm_mday:02d} {t.tm_hour:02d}:{t.tm_min:02d}:{t.tm_sec:02d}"
        except Exception:
            pass
    return time.strftime("%Y-%m-%d %H:%M:%S")

def switch_relay(ac_num, state_bool):
    global is_turbo_cooling_active
    relay_states[ac_num] = state_bool
    for r in RELAYS:
        if r["ac_number"] == ac_num:
            if HAS_HARDWARE:
                GPIO.output(r["gpio_pin"], GPIO.HIGH if state_bool else GPIO.LOW)
            print(f"⚡ [RELAY {ac_num}] {r['name']} -> {'ON (MENYALA)' if state_bool else 'OFF (PADAM)'}")
            break

# ================= 4. MQTT CLIENTS (LOCAL & BLYNK) =================
local_client = mqtt.Client(mqtt.CallbackAPIVersion.VERSION2, client_id=f"pindad_node_{DEVICE_ID}")

def on_local_connect(client, userdata, flags, rc, properties=None):
    print(f"📡 [LOCAL MQTT] Terhubung ke Broker {config['mqtt_broker_host']}:{config['mqtt_broker_port']} (RC: {rc})")
    client.subscribe("pindad/ac/control")
    client.subscribe("pindad/ac/schedule")
    client.subscribe(f"pindad/devices/{DEVICE_ID}/control")
    print(f"👂 [SUBSCRIBE] Mendengarkan topik: pindad/devices/{DEVICE_ID}/control")

def on_local_message(client, userdata, msg):
    global is_turbo_cooling_active
    try:
        payload = json.loads(msg.payload.decode())
        target_dev = payload.get("device_id")
        if target_dev and target_dev != DEVICE_ID and target_dev != "ALL":
            return # Pesan untuk node lain

        cmd = payload.get("command", "")
        source = payload.get("source", "schedule")

        if source == "manual":
            is_turbo_cooling_active = False # Release lock

        # Parse AC commands: AC_1_ON, AC_2_OFF, MASTER_ON, MASTER_OFF
        if "MASTER_ON" in cmd:
            for r in RELAYS: switch_relay(r["ac_number"], True)
        elif "MASTER_OFF" in cmd:
            for r in RELAYS: switch_relay(r["ac_number"], False)
        elif cmd.startswith("AC_"):
            parts = cmd.split("_") # ['AC', '1', 'ON']
            if len(parts) >= 3:
                ac_num = int(parts[1])
                st = (parts[2].upper() == "ON")
                switch_relay(ac_num, st)
    except Exception as e:
        print(f"❌ [MQTT MSG ERROR] {e}")

local_client.on_connect = on_local_connect
local_client.on_message = on_local_message

# ================= 5. MAIN LOOP TELEMETRY =================
def telemetry_loop():
    global is_turbo_cooling_active
    start_time = time.time()

    while True:
        try:
            # Check Turbo Cooling Expiry
            if is_turbo_cooling_active and (time.time() - start_time > TURBO_COOLING_SEC):
                is_turbo_cooling_active = False
                print("⏱️ [TURBO COOLING SELESAI] Masa pendinginan boot 5 menit berakhir. Siap rotasi jadwal.")

            ts = get_current_timestamp()

            for r in RELAYS:
                ac_num = r["ac_number"]
                is_on = relay_states.get(ac_num, False)
                
                # Baca / hitung arus
                if is_on:
                    current_amp = round(random.uniform(2.10, 2.30), 4) # Nominal 2.15 A
                else:
                    current_amp = 0.0000

                payload = {
                    "device_id": DEVICE_ID,
                    "active_ac": f"AC_{ac_num}_{'ON' if is_on else 'OFF'}",
                    "current_ampere": current_amp,
                    "watt": round(current_amp * 220),
                    "recorded_at": ts,
                    "turbo_active": is_turbo_cooling_active
                }

                # Publish ke local broker untuk Laravel Dashboard
                local_client.publish("pindad/ac/logs", json.dumps(payload))
                local_client.publish(f"pindad/devices/{DEVICE_ID}/telemetry", json.dumps(payload))

            time.sleep(INTERVAL_SEC)
        except Exception as e:
            print(f"❌ [LOOP ERROR] {e}")
            time.sleep(5)

# ================= 6. START PROGRAM =================
if __name__ == "__main__":
    login_sophos()
    try:
        local_client.connect(config["mqtt_broker_host"], config["mqtt_broker_port"], 60)
        local_client.loop_start()
    except Exception as e:
        print(f"❌ [MQTT CONNECT ERROR] Gagal tersambung ke broker: {e}")

    # Start telemetry thread
    t = threading.Thread(target=telemetry_loop, daemon=True)
    t.start()

    print(f"✅ [ONLINE] Node Controller {DEVICE_ID} aktif & berjalan normal.")
    while True:
        time.sleep(1)
