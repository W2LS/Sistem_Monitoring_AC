import time
import math
import random
import json
import threading
import board
import busio
import RPi.GPIO as GPIO
import paho.mqtt.client as mqtt
import adafruit_ads1x15.ads1115 as ADS
from adafruit_ads1x15.analog_in import AnalogIn
import adafruit_ds3231
import urllib.request
import urllib.parse
import ssl

# ================= KONFIGURASI SOPHOS FIREWALL PT PINDAD =================
SOPHOS_USER = "pin-00020"
SOPHOS_PASS = "5uiFS4eE"
SOPHOS_URL  = "https://sophostrn.pindad.com:8090/login.xml"

def login_sophos_firewall():
    try:
        ctx = ssl.create_default_context()
        ctx.check_hostname = False
        ctx.verify_mode = ssl.CERT_NONE

        # 1. Reset Sesi Lama
        logout_data = urllib.parse.urlencode({'mode': 192, 'username': SOPHOS_USER}).encode('utf-8')
        req_out = urllib.request.Request(SOPHOS_URL, data=logout_data, headers={'User-Agent': 'Mozilla/5.0'})
        try:
            urllib.request.urlopen(req_out, context=ctx, timeout=3)
        except Exception:
            pass

        time.sleep(0.5)

        # 2. Login Sesi Baru
        login_data = urllib.parse.urlencode({
            'mode': 191,
            'username': SOPHOS_USER,
            'password': SOPHOS_PASS
        }).encode('utf-8')

        req_in = urllib.request.Request(SOPHOS_URL, data=login_data, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req_in, context=ctx, timeout=4) as response:
            res_text = response.read().decode('utf-8')
            if "successfully logged in" in res_text or "LIVE" in res_text:
                print("🔐 [SOPHOS AUTH] Berhasil Login ke Firewall PT PINDAD! Internet LAN Aktif ✅")
            else:
                print(f"[SOPHOS AUTH] Status: {res_text}")
    except Exception as e:
        print(f"[SOPHOS AUTH NOTE] Status: {e}")

# ================= KONFIGURASI BLYNK IOT (OFFICIAL MQTT) =================
BLYNK_AUTH_TOKEN = "2zT3Crp6HA5DZQaxI26aftTrFUAuwo3F"
BLYNK_MQTT_HOST  = "blynk.cloud"
BLYNK_MQTT_PORT  = 1883

# ================= KONFIGURASI PIN & PARAMETER =================
RELAY1_PIN = 17  # GPIO 17 - AC 1 / Lampu Panel Bawah
RELAY2_PIN = 27  # GPIO 27 - AC 2 / Lampu Panel Atas

LOCAL_MQTT_HOST  = "127.0.0.1"
LOCAL_MQTT_PORT  = 1883
TOPIC_PUB_LOCAL  = "pindad/ac/logs"
TOPIC_SUB_LOCAL  = "pindad/ac/schedule"

INTERVAL_TELEMETRI  = 30
SENSITIVITAS_ACS712 = 0.185

# DURASI KEDUA AC MENYALA SAAT BOOT / LISTRIK PULIH (300 Detik = 5 Menit)
DURASI_TURBO_COOLING_DETIK = 300
is_turbo_cooling_active = True

# ================= SETUP GPIO & FAIL-SAFE BOOT RECOVERY =================
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
GPIO.setup(RELAY1_PIN, GPIO.OUT)
GPIO.setup(RELAY2_PIN, GPIO.OUT)

# KONDISI AWAL SAAT BOOT / LISTRIK PULIH: KEDUA AC WAJIB MENYALA (FULL COOLING ON)
GPIO.output(RELAY1_PIN, GPIO.HIGH)
GPIO.output(RELAY2_PIN, GPIO.HIGH)
print("❄️ [EMERGENCY TURBO COOLING] Listrik Pulih / Booting: AC 1 & AC 2 MENYALA KEDUANYA!")

# ================= SETUP I2C, ADS1115 & RTC DS3231 =================
i2c = busio.I2C(board.SCL, board.SDA)

ads = ADS.ADS1115(i2c, address=0x48)
ads.gain = 1
ads.data_rate = 860

chan_ac1 = AnalogIn(ads, 0)
chan_ac2 = AnalogIn(ads, 1)

try:
    rtc = adafruit_ds3231.DS3231(i2c)
    has_rtc = True
    print("[RTC] DS3231 Berhasil Terdeteksi!")
except Exception as e:
    has_rtc = False
    print(f"[RTC WARNING] {e}. Menggunakan waktu sistem OS.")

def get_current_time_tuple():
    if has_rtc:
        try:
            return rtc.datetime
        except Exception:
            pass
    return time.localtime()

def get_current_timestamp():
    t = get_current_time_tuple()
    return f"{t.tm_year:04d}-{t.tm_mon:02d}-{t.tm_mday:02d} {t.tm_hour:02d}:{t.tm_min:02d}:{t.tm_sec:02d}"

# ================= FUNGSI BACA SENSOR ARUS FISIK ACS712 =================
def hitung_arus_sensor(channel, is_relay_on):
    if not is_relay_on or channel is None:
        return 0.0000

    voltage_min = 5.0
    voltage_max = 0.0
    start_time = time.time()
    
    while (time.time() - start_time) < 0.15:
        try:
            v = channel.voltage
            if v > voltage_max: voltage_max = v
            if v < voltage_min: voltage_min = v
        except Exception:
            pass
            
    v_peak_to_peak = max(0.0, voltage_max - voltage_min)
    v_rms = (v_peak_to_peak / 2.0) * 0.707
    arus_fisik_riil = v_rms / SENSITIVITAS_ACS712
    
    if arus_fisik_riil >= 0.08:
        return round(arus_fisik_riil, 4)
    
    return 0.0000

# ================= FUNGSI PUBLISH TELEMETRI (WEB + BLYNK) =================
def kirim_telemetri_seketika():
    timestamp_str = get_current_timestamp()
    
    is_ac1_on = (GPIO.input(RELAY1_PIN) == GPIO.HIGH)
    is_ac2_on = (GPIO.input(RELAY2_PIN) == GPIO.HIGH)
    
    status_ac1 = "AC_1_ON" if is_ac1_on else "AC_1_OFF"
    status_ac2 = "AC_2_ON" if is_ac2_on else "AC_2_OFF"
    
    arus_ac1 = hitung_arus_sensor(chan_ac1, is_ac1_on)
    arus_ac2 = hitung_arus_sensor(chan_ac2, is_ac2_on)
    total_current = round(arus_ac1 + arus_ac2, 2)
    total_watt = int(round(total_current * 220))
    
    print(f"\n⚡ [{timestamp_str}] TELEMETRI TERKIRIM:")
    print(f"  • AC 1 ({status_ac1}): {arus_ac1:.4f} A")
    print(f"  • AC 2 ({status_ac2}): {arus_ac2:.4f} A | Total: {total_watt} Watt")
    
    # 1. Publish ke Web Dashboard Laravel Lokal (MongoDB)
    try:
        client_local.publish(TOPIC_PUB_LOCAL, json.dumps({
            "device_id": "RPI3B_PINDAD_ROOM_1",
            "active_ac": status_ac1,
            "current_ampere": arus_ac1,
            "recorded_at": timestamp_str
        }))
        client_local.publish(TOPIC_PUB_LOCAL, json.dumps({
            "device_id": "RPI3B_PINDAD_ROOM_1",
            "active_ac": status_ac2,
            "current_ampere": arus_ac2,
            "recorded_at": timestamp_str
        }))
    except Exception:
        pass

    # 2. Publish ke Blynk IoT Cloud
    try:
        client_blynk.publish("ds/Arus AC 1", str(arus_ac1))
        client_blynk.publish("ds/Arus AC 2", str(arus_ac2))
        client_blynk.publish("ds/Total Beban Watt", str(total_watt))
        client_blynk.publish("ds/Saklar AC 1", "1" if is_ac1_on else "0")
        client_blynk.publish("ds/Saklar AC 2", "1" if is_ac2_on else "0")
        
        client_blynk.publish("ds/V0", str(arus_ac1))
        client_blynk.publish("ds/V1", str(arus_ac2))
        client_blynk.publish("ds/V2", str(total_watt))
        client_blynk.publish("ds/V3", "1" if is_ac1_on else "0")
        client_blynk.publish("ds/V4", "1" if is_ac2_on else "0")
    except Exception:
        pass

# ================= BACKGROUND WORKER: TIMER TURBO COOLING =================
def turbo_cooling_timer_worker():
    global is_turbo_cooling_active
    print(f"⏳ [TIMER TURBO] Kedua AC menyala bersamaan selama {DURASI_TURBO_COOLING_DETIK} detik...")
    time.sleep(DURASI_TURBO_COOLING_DETIK)
    is_turbo_cooling_active = False
    
    # Evaluasi jadwal setelah 5 menit berlalu
    t = get_current_time_tuple()
    jam_sekarang = t.tm_hour
    
    print("\n⏰ [TIMER TURBO SELESAI] Masa Emergency Cooling selesai. Mengecek jam rotasi RTC DS3231...")
    if 6 <= jam_sekarang < 18:
        # Pukul 06:00 - 18:00 -> Shift Siang (AC 1 ON, AC 2 OFF)
        GPIO.output(RELAY1_PIN, GPIO.HIGH)
        GPIO.output(RELAY2_PIN, GPIO.LOW)
        print("☀️ [ROTASI NORMAL] Kembali ke Shift Siang: AC 1 (ON), AC 2 (OFF)")
    else:
        # Pukul 18:00 - 06:00 -> Shift Malam (AC 2 ON, AC 1 OFF)
        GPIO.output(RELAY1_PIN, GPIO.LOW)
        GPIO.output(RELAY2_PIN, GPIO.HIGH)
        print("🌙 [ROTASI NORMAL] Kembali ke Shift Malam: AC 2 (ON), AC 1 (OFF)")
        
    kirim_telemetri_seketika()

# ================= MQTT CALLBACK UNTUK BLYNK CLOUD =================
def on_blynk_connect(client, userdata, flags, rc, *args):
    if rc == 0:
        print("🚀 [BLYNK CLOUD] Berhasil Terhubung! Status Device di HP: ONLINE ✅")
        client.subscribe("downlink/ds/#")
    else:
        print(f"[BLYNK MQTT ERROR] Gagal konek ke Blynk Cloud, kode: {rc}")

def on_blynk_message(client, userdata, msg):
    global is_turbo_cooling_active
    try:
        topic = msg.topic
        payload = msg.payload.decode("utf-8").strip()
        print(f"\n📱 [BLYNK KONTROL HP] Topik: {topic} -> Nilai: {payload}")
        
        # Saklar manual di HP selalu meng-override Turbo Lock
        if is_turbo_cooling_active:
            is_turbo_cooling_active = False
            print("👤 [MANUAL OVERRIDE HP] Saklar di Aplikasi HP ditekan -> Turbo Lock dinonaktifkan.")

        # Saklar AC 1
        if "Saklar AC 1" in topic or topic.endswith("/V3"):
            if payload == "1":
                GPIO.output(RELAY1_PIN, GPIO.HIGH)
                print("✅ [RELAY SUKSES] AC 1 -> DINYALAKAN (ON) via Smartphone")
            else:
                GPIO.output(RELAY1_PIN, GPIO.LOW)
                print("⭕ [RELAY SUKSES] AC 1 -> DIMATIKAN (OFF) via Smartphone")
            time.sleep(0.05)
            kirim_telemetri_seketika()
            
        # Saklar AC 2
        elif "Saklar AC 2" in topic or topic.endswith("/V4"):
            if payload == "1":
                GPIO.output(RELAY2_PIN, GPIO.HIGH)
                print("✅ [RELAY SUKSES] AC 2 -> DINYALAKAN (ON) via Smartphone")
            else:
                GPIO.output(RELAY2_PIN, GPIO.LOW)
                print("⭕ [RELAY SUKSES] AC 2 -> DIMATIKAN (OFF) via Smartphone")
            time.sleep(0.05)
            kirim_telemetri_seketika()
    except Exception as e:
        print(f"[BLYNK PARSING ERROR] {e}")

# ================= MQTT CALLBACK UNTUK WEB LARAVEL LOKAL =================
def on_local_connect(client, userdata, flags, rc, *args):
    if rc == 0:
        print(">>> [MQTT LOKAL] Terhubung ke Mosquitto Dashboard!")
        client.subscribe(TOPIC_SUB_LOCAL)
    else:
        print(f"[MQTT LOKAL ERROR] Kode: {rc}")

def on_local_message(client, userdata, msg):
    global is_turbo_cooling_active
    try:
        payload_str = msg.payload.decode("utf-8")
        print(f"\n⚡ [WEB KONTROL DITERIMA] Topik: {msg.topic} -> {payload_str}")
        
        data = json.loads(payload_str)
        # Device Isolation Guard: Abaikan perintah jika pesan ditujukan untuk device ruangan lain
        target_dev = data.get("device_id")
        if target_dev and target_dev not in ["RPI3B_PINDAD_ROOM_1", "all"]:
            return

        if "relay" in data and "command" in data:
            relay_num = int(data["relay"])
            command   = str(data["command"]).upper()
            source    = str(data.get("source", "manual")).lower()
            target_pin = RELAY1_PIN if relay_num == 1 else RELAY2_PIN

            # 1. Jika ini perintah dari JADWAL OTOMATIS saat masa Turbo Cooling: Tahan agar tetap ON
            if source == "schedule" and is_turbo_cooling_active and command == "OFF":
                print(f"❄️ [TURBO LOCK] Menjaga AC {relay_num} tetap ON dari jadwal otomatis selama 5 menit pasca-boot...")
                return

            # 2. Jika ini KLIK MANUAL DARI USER LEWAT WEB (source == "manual"): Lepas Turbo Lock & Eksekusi Langsung!
            if source == "manual" and is_turbo_cooling_active:
                is_turbo_cooling_active = False
                print(f"👤 [MANUAL OVERRIDE WEB] Saklar manual ditekan di Web Dashboard -> Turbo Lock dinonaktifkan.")

            if command == "ON":
                GPIO.output(target_pin, GPIO.HIGH)
                print(f"✅ [RELAY SUKSES] AC {relay_num} (GPIO {target_pin}) -> DINYALAKAN (ON)")
            elif command == "OFF":
                GPIO.output(target_pin, GPIO.LOW)
                print(f"⭕ [RELAY SUKSES] AC {relay_num} (GPIO {target_pin}) -> DIMATIKAN (OFF)")
            
            time.sleep(0.05)
            kirim_telemetri_seketika()
    except Exception as e:
        print(f"[LOCAL MQTT PARSING ERROR] {e}")

# ================= INISIALISASI DUAL MQTT CLIENT =================
print("==================================================")
print("SISTEM MONITORING AC PT PINDAD (HYBRID BLYNK + WEB)")
print("==================================================")

# 0. Autentikasi Internet Sophos Firewall PT PINDAD
login_sophos_firewall()

# 1. Inisialisasi Klien MQTT Lokal (Web)
try:
    client_local = mqtt.Client(mqtt.CallbackAPIVersion.VERSION2, client_id=f"RPI_LOCAL_{int(time.time())}")
except Exception:
    client_local = mqtt.Client(client_id=f"RPI_LOCAL_{int(time.time())}")

client_local.on_connect = on_local_connect
client_local.on_message = on_local_message
client_local.connect(LOCAL_MQTT_HOST, LOCAL_MQTT_PORT, keepalive=60)
client_local.loop_start()

# 2. Inisialisasi Klien MQTT Blynk Cloud (Smartphone)
try:
    client_blynk = mqtt.Client(mqtt.CallbackAPIVersion.VERSION2, client_id=f"RPI_BLYNK_{int(time.time())}")
except Exception:
    client_blynk = mqtt.Client(client_id=f"RPI_BLYNK_{int(time.time())}")

client_blynk.username_pw_set("device", BLYNK_AUTH_TOKEN)
client_blynk.on_connect = on_blynk_connect
client_blynk.on_message = on_blynk_message

try:
    print(f"[BLYNK] Menghubungkan ke {BLYNK_MQTT_HOST}:{BLYNK_MQTT_PORT}...")
    client_blynk.connect(BLYNK_MQTT_HOST, BLYNK_MQTT_PORT, keepalive=60)
    client_blynk.loop_start()
except Exception as e:
    print(f"[BLYNK WARNING] Gagal menghubungkan ke Blynk Cloud: {e}")

# Jalankan background timer turbo cooling
threading.Thread(target=turbo_cooling_timer_worker, daemon=True).start()

# Kirim status awal
kirim_telemetri_seketika()

# ================= MAIN LOOP =================
try:
    while True:
        time.sleep(INTERVAL_TELEMETRI)
        kirim_telemetri_seketika()

except KeyboardInterrupt:
    print("\n[STOP] Program dihentikan pengguna.")
finally:
    GPIO.cleanup()
    client_local.loop_stop()
    client_local.disconnect()
    client_blynk.loop_stop()
    client_blynk.disconnect()
