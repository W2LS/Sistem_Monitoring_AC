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

import sys
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

# ================= 1. BACA FILE KONFIGURASI NODE & CLI ARGS =================
CONFIG_PATH = os.path.join(os.path.dirname(__file__), "node_config.json")

def load_config():
    default_config = {
        "device_id": "RPI3B_PINDAD_ROOM_1",
        "room_name": "Ruang Server Utama",
        "mqtt_broker_host": "127.0.0.1",
        "mqtt_broker_port": 1883,
        "sophos_auth": {"enabled": False},
        "relays": [
            {"ac_number": 1, "gpio_pin": 17, "name": "AC 1", "adc_channel": 0},
            {"ac_number": 2, "gpio_pin": 27, "name": "AC 2", "adc_channel": 1}
        ],
        "schedules": [],
        "turbo_cooling_seconds": 0,
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

# Cek argumen CLI (misal: python3 pindad_universal_node.py RPI3B_MONITORING_AC_RUANG_SERVER_2 192.168.1.50)
if len(sys.argv) > 1 and not sys.argv[1].startswith("-"):
    config["device_id"] = sys.argv[1].strip()
    config["room_name"] = sys.argv[1].strip().replace("_", " ")

for i, arg in enumerate(sys.argv):
    if arg in ["--broker", "--broker-host", "--host"] and i + 1 < len(sys.argv):
        config["mqtt_broker_host"] = sys.argv[i + 1].strip()
    elif len(sys.argv) > 2 and not sys.argv[2].startswith("-") and i == 2:
        config["mqtt_broker_host"] = sys.argv[2].strip()

DEVICE_ID = config["device_id"]
RELAYS = config["relays"]
active_schedules = config.get("schedules", [])
TURBO_COOLING_SEC = config.get("turbo_cooling_seconds", 0)
INTERVAL_SEC = config.get("telemetry_interval_seconds", 15)

# ================= AUTO-START INSTALLER FEATURE (--install / --autostart) =================
def setup_autostart():
    script_path = os.path.abspath(__file__)
    user = os.getenv("USER", "alex")
    home = os.getenv("HOME", f"/home/{user}")
    print(f"\n⚙️ [AUTO-START INSTALLER] Memasang layanan auto-start on boot untuk {DEVICE_ID}...")
    
    # 1. Setup Crontab Entry automatically
    try:
        cron_line = f"@reboot sleep 10 && cd {home} && /usr/bin/python3 {script_path} > {home}/node.log 2>&1 &"
        import subprocess
        p = subprocess.Popen(["crontab", "-l"], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        out, _ = p.communicate()
        existing = out.decode("utf-8", errors="ignore")
        
        base_name = os.path.basename(script_path)
        new_lines = [line for line in existing.splitlines() if base_name not in line and "pindad_node" not in line and line.strip()]
        new_lines.append(cron_line)
        new_crontab = "\n".join(new_lines) + "\n"
        
        p_write = subprocess.Popen(["crontab", "-"], stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        p_write.communicate(input=new_crontab.encode("utf-8"))
        print(f"✅ [CRONTAB] Berhasil mendaftarkan Auto-Start on Boot ke Crontab!")
    except Exception as e:
        print(f"⚠️ [CRONTAB NOTE] Status: {e}")

    # 2. Setup Systemd Service if root / sudo available
    try:
        service_content = f"""[Unit]
Description=PT Pindad IoT Node - {DEVICE_ID}
After=network-online.target
Wants=network-online.target

[Service]
Type=simple
User={user}
WorkingDirectory={home}
ExecStart=/usr/bin/python3 {script_path}
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
"""
        service_path = f"/etc/systemd/system/pindad_node_{DEVICE_ID.lower()}.service"
        if os.geteuid() == 0:
            with open(service_path, "w") as f:
                f.write(service_content)
            os.system("systemctl daemon-reload")
            os.system(f"systemctl enable pindad_node_{DEVICE_ID.lower()}.service")
            os.system(f"systemctl restart pindad_node_{DEVICE_ID.lower()}.service")
            print(f"✅ [SYSTEMD] Layanan systemd berhasil dipasang dan diaktifkan (Auto-Restart 24/7)!")
    except Exception as e:
        pass

    print(f"🎉 [SUKSES] Konfigurasi Auto-Start Selesai! Raspberry Pi akan otomatis menyalakan script ini setiap kali dicolok listrik.\n")
    sys.exit(0)

if "--install" in sys.argv or "--autostart" in sys.argv:
    setup_autostart()

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

TRIGGER_MODE = config.get("trigger_mode", "LATCHING") # "LATCHING" (Lampu LED / Relai) atau "TACTILE_PULSE" (Tombol AC)

if HAS_HARDWARE:
    GPIO.setmode(GPIO.BCM)
    GPIO.setwarnings(False)
    for r in RELAYS:
        GPIO.setup(r["gpio_pin"], GPIO.OUT)
        initial_val = GPIO.HIGH if TRIGGER_MODE == "LATCHING" else GPIO.LOW
        GPIO.output(r["gpio_pin"], initial_val)
    print(f"❄️ [BOOT INIT] Seluruh relai siap ({TRIGGER_MODE} Mode).")

    try:
        i2c = busio.I2C(board.SCL, board.SDA)
        ads1 = None
        ads2 = None

        try:
            ads1 = ADS.ADS1115(i2c, address=0x48)
            ads1.gain = 1
            ads1.data_rate = 860
        except Exception as e:
            print(f"⚠️ [ADS1115 #1 (0x48) ERROR] {e}")

        try:
            ads2 = ADS.ADS1115(i2c, address=0x49)
            ads2.gain = 1
            ads2.data_rate = 860
        except Exception:
            pass # Secondary ADC opsional jika menggunakan relay > 4 channel

        for r in RELAYS:
            ac_num = r["ac_number"]
            ch_idx = r.get("adc_channel", (ac_num - 1) % 4)
            if ac_num <= 4 and ads1:
                adc_channels[ac_num] = AnalogIn(ads1, ch_idx)
            elif ac_num > 4 and ads2:
                adc_channels[ac_num] = AnalogIn(ads2, ch_idx)
            elif ads1:
                adc_channels[ac_num] = AnalogIn(ads1, ch_idx)
        print(f"⚡ [ADS1115] ADC Sensor Arus ACS712 siap untuk {len(adc_channels)} Channel ({len(RELAYS)} Unit AC).")
    except Exception as e:
        print(f"⚠️ [ADS1115 SETUP ERROR] {e}")

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

def get_current_time_hm():
    if HAS_HARDWARE and has_rtc:
        try:
            t = rtc.datetime
            return f"{t.tm_hour:02d}:{t.tm_min:02d}"
        except Exception:
            pass
    return time.strftime("%H:%M")

def is_schedule_active_for_ac(sch, ac_num, now_hm):
    start = str(sch.get("start_time", "00:00"))[:5]
    end = str(sch.get("end_time", "00:00"))[:5]
    
    if start <= end:
        is_inside = (now_hm >= start and now_hm < end)
    else:
        # Overnight shift (misal: 18:00 - 06:00)
        is_inside = (now_hm >= start or now_hm < end)
        
    if not is_inside:
        return False
        
    target = str(sch.get("target_ac", "all")).lower().strip()
    if target in ["all", "seluruh", "2 unit", "semua", "0", "", "none", "null"]:
        return True
        
    if target == str(ac_num) or target == f"ac {ac_num}" or target == f"ac_{ac_num}":
        return True
        
    return False

last_schedule_state = {}

def evaluate_schedules(force=False):
    global active_schedules, is_turbo_cooling_active, last_schedule_state
    
    if not active_schedules:
        return
        
    if is_turbo_cooling_active and not force:
        return
        
    now_hm = get_current_time_hm()
    
    desired_states = {}
    active_labels = {}
    
    for r in RELAYS:
        ac_num = r["ac_number"]
        is_ac_on = False
        matched_label = ""
        
        for sch in active_schedules:
            if not sch.get("is_active", True):
                continue
            if is_schedule_active_for_ac(sch, ac_num, now_hm):
                is_ac_on = True
                matched_label = sch.get("label", "Rotasi Shift")
                break
                
        desired_states[ac_num] = is_ac_on
        active_labels[ac_num] = matched_label
        
    for r in RELAYS:
        ac_num = r["ac_number"]
        desired = desired_states.get(ac_num, False)
        prev_sched = last_schedule_state.get(ac_num)
        
        # Eksekusi switch hanya saat terjadi transisi waktu jadwal (masuk shift atau keluar shift)
        # ATAU saat inisialisasi boot pertama / pembaruan jadwal dari dashboard (force=True)
        is_transition = (prev_sched is not None and prev_sched != desired)
        is_initial_boot = (prev_sched is None or force)
        
        if is_transition or is_initial_boot:
            last_schedule_state[ac_num] = desired
            status_str = "ON 🟢 (MENYALA)" if desired else "OFF ⚪ (PADAM)"
            lbl = active_labels.get(ac_num) or "Standby / Diluar Shift"
            print(f"⏰ [RTC ROTASI JADWAL] Pukul {now_hm} WIB ➔ AC {ac_num} ({r['name']}) diatur ke {status_str} [Jadwal: {lbl}]")
            switch_relay(ac_num, desired)

def _pulse_tactile(gpio_pin, duration_sec):
    """Pemicu pulsa ke tombol taktikal AC (Non-blocking background thread)"""
    try:
        if HAS_HARDWARE:
            GPIO.output(gpio_pin, GPIO.HIGH) # Kontak NO-COM terhubung (menekan tombol)
        time.sleep(duration_sec)
        if HAS_HARDWARE:
            GPIO.output(gpio_pin, GPIO.LOW)  # Kontak NO-COM terbuka (melepas tombol)
    except Exception as e:
        print(f"⚠️ [PULSE ERROR] {e}")

def switch_relay(ac_num, state_bool):
    global is_turbo_cooling_active
    relay_states[ac_num] = state_bool
    for r in RELAYS:
        if r["ac_number"] == ac_num:
            pin = r["gpio_pin"]
            if TRIGGER_MODE == "TACTILE_PULSE":
                # Mode Tombol Taktikal AC: ON = Short Pulse 0.6s, OFF = Long Press 3.2s
                pulse_time = 0.6 if state_bool else 3.2
                action_desc = "SHORT PRESS 0.6s (CETUK NYALAKAN AC)" if state_bool else "LONG PRESS 3.2s (TAHAN PADAMKAN AC)"
                print(f"🔘 [TACTILE PULSE AC {ac_num}] {r['name']} ➔ {action_desc}")
                threading.Thread(target=_pulse_tactile, args=(pin, pulse_time), daemon=True).start()
            else:
                # Mode Latching (Default untuk Lampu LED & Relai Kontinu)
                if HAS_HARDWARE:
                    GPIO.output(pin, GPIO.HIGH if state_bool else GPIO.LOW)
                print(f"⚡ [RELAY {ac_num}] {r['name']} ➔ {'ON (LAMPU MENYALA)' if state_bool else 'OFF (LAMPU PADAM)'}")
            break
    
    # Kirim telemetri instan ke Dashboard seketika
    try:
        send_instant_telemetry(ac_num)
    except Exception:
        pass

# ================= 4. MQTT CLIENT & TELEMETRY ENGINE =================
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

        # Parse AC commands: AC_1_ON, AC_2_OFF, MASTER_ON, MASTER_OFF, or direct command & ac_number/relay
        if "MASTER_ON" in cmd:
            for r in RELAYS: switch_relay(r["ac_number"], True)
        elif "MASTER_OFF" in cmd:
            for r in RELAYS: switch_relay(r["ac_number"], False)
        elif cmd in ["ON", "OFF"]:
            ac_num = payload.get("ac_number") or payload.get("relay")
            if ac_num is not None:
                switch_relay(int(ac_num), cmd == "ON")
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

# ================= 4. SENSOR ARUS ACS712 & ADS1115 =================
SENSITIVITAS_ACS712 = 0.185 # V/A (185 mV/A untuk ACS712-05B, 100 mV/A untuk ACS712-20A)

def read_current_ampere(ac_num):
    is_on = relay_states.get(ac_num, False)
    if not is_on:
        return 0.0000

    chan = adc_channels.get(ac_num)
    arus_fisik_riil = 0.0
    if chan is not None and HAS_HARDWARE:
        voltage_min = 5.0
        voltage_max = 0.0
        start_time = time.time()
        
        while (time.time() - start_time) < 0.15:
            try:
                v = chan.voltage
                if v > voltage_max: voltage_max = v
                if v < voltage_min: voltage_min = v
            except Exception:
                pass
                
        v_peak_to_peak = max(0.0, voltage_max - voltage_min)
        v_rms = (v_peak_to_peak / 2.0) * 0.707
        arus_fisik_riil = v_rms / SENSITIVITAS_ACS712

    # Hanya laporkan arus riil terukur dari sensor fisik ACS712 & ADS1115
    # (Jika saklar ON tapi kabel beban AC belum dialiri listrik / < 0.05 A, laporkan 0.0000 A)
    if arus_fisik_riil >= 0.05:
        return round(arus_fisik_riil, 4)
    
    return 0.0000

last_telegram_alert = {}
relay_turned_on_time = {r["ac_number"]: time.time() for r in RELAYS}

def send_direct_telegram_alert(ac_num, unit_name, current_amp, failure_type="GAGAL_HIDUP"):
    tele = config.get("telegram", {})
    if not tele or not tele.get("enabled", True):
        return
    bot_token = tele.get("bot_token")
    chat_id = tele.get("chat_id")
    if not bot_token or not chat_id:
        return
        
    cooldown_sec = max(60, tele.get("cooldown_minutes", 15) * 60)
    now_ts = time.time()
    last_sent = last_telegram_alert.get((ac_num, failure_type), 0)
    if now_ts - last_sent < cooldown_sec:
        return
        
    last_telegram_alert[(ac_num, failure_type)] = now_ts
    
    ts_str = get_current_timestamp()
    room = config.get("room_name", DEVICE_ID)
    loc = config.get("location", "PT PINDAD (PERSERO)")
    
    msg = f"🚨 <b>[PERINGATAN KRITIS EDGE • PT PINDAD]</b>\n"
    msg += f"⚠️ <b>GANGGUAN: AC GAGAL MENYALA / MATI!</b>\n\n"
    msg += f"📍 <b>Ruangan:</b> <code>{room}</code>\n"
    msg += f"🏢 <b>Lokasi:</b> {loc}\n"
    msg += f"🆔 <b>ID Perangkat:</b> <code>{DEVICE_ID}</code> (Pengirim: Edge Node Mandiri)\n"
    msg += f"❄️ <b>Unit AC:</b> <b>Unit {ac_num} ({unit_name})</b>\n"
    msg += f"⚡ <b>Arus Terukur Sensor ACS712:</b> <code>{current_amp:.4f} A</code> (0 Watt)\n"
    msg += f"⚙️ <b>Status Relai:</b> ON (Jadwal Aktif)\n"
    msg += f"⏰ <b>Waktu Deteksi:</b> {ts_str} WIB\n\n"
    msg += f"🔍 <b>DIAGNOSA SENSOR EDGE (RASPBERRY PI MANDIRI):</b>\n"
    msg += f"Relai ON tetapi sensor ACS712 mendeteksi 0 Ampere (Kompresor mati / MCB trip / Kapasitor rusak).\n"
    msg += f"<i>(Peringatan darurat ini dikirim langsung oleh Raspberry Pi tanpa membutuhkan PC Dashboard)</i>\n\n"
    msg += f"👨‍🔧 <b>TINDAKAN:</b> Mohon teknisi segera lakukan pengecekan di lokasi <b>{room}</b>!"

    try:
        url = f"https://api.telegram.org/bot{bot_token}/sendMessage"
        req_body = json.dumps({
            "chat_id": chat_id,
            "text": msg,
            "parse_mode": "HTML",
            "disable_web_page_preview": True
        }).encode('utf-8')
        req = urllib.request.Request(url, data=req_body, headers={'Content-Type': 'application/json', 'User-Agent': 'PindadEdgeNode/1.0'})
        ctx = ssl.create_default_context()
        ctx.check_hostname = False
        ctx.verify_mode = ssl.CERT_NONE
        with urllib.request.urlopen(req, context=ctx, timeout=5) as resp:
            print(f"📲 [TELEGRAM DIRECT] Berhasil mengirim pesan darurat anomali langsung dari Raspberry Pi!")
    except Exception as e:
        pass

def send_http_telemetry(payload):
    """Kirim telemetri HTTP REST langsung ke Laravel Web Dashboard (Dual-Sync Real-Time)"""
    global active_schedules
    dash_url = config.get("dashboard_http_url")
    if not dash_url:
        return
    try:
        req_data = json.dumps(payload).encode('utf-8')
        req = urllib.request.Request(
            f"{dash_url}/api/telemetry", 
            data=req_data, 
            headers={'Content-Type': 'application/json', 'User-Agent': 'PindadIoTNode/1.0'}
        )
        with urllib.request.urlopen(req, timeout=3) as resp:
            resp_bytes = resp.read()
            if resp_bytes:
                resp_json = json.loads(resp_bytes.decode('utf-8'))
                # Dual-Sync Telegram Settings
                if "telegram" in resp_json and isinstance(resp_json["telegram"], dict):
                    config["telegram"] = resp_json["telegram"]

                # Dual-Sync Jadwal dari Web Dashboard
                if "schedules" in resp_json and isinstance(resp_json["schedules"], list):
                    new_scheds = resp_json["schedules"]
                    if json.dumps(new_scheds, sort_keys=True) != json.dumps(active_schedules, sort_keys=True):
                        active_schedules = new_scheds
                        config["schedules"] = new_scheds
                        print(f"🔄 [SYNC JADWAL WEB] Menerima {len(active_schedules)} aturan jadwal terbaru dari Dashboard!")
                        try:
                            with open(CONFIG_PATH, "w") as f:
                                json.dump(config, f, indent=2)
                        except Exception:
                            pass
                        evaluate_schedules(force=True)
    except Exception as e:
        pass

def send_instant_telemetry(target_ac_num=None):
    """Kirim telemetri instan seketika saat saklar relay berganti status (Zero Delay)"""
    try:
        ts = get_current_timestamp()
        units_to_send = [r for r in RELAYS if r["ac_number"] == target_ac_num] if target_ac_num else RELAYS
        for r in units_to_send:
            ac_num = r["ac_number"]
            is_on = relay_states.get(ac_num, False)
            current_amp = read_current_ampere(ac_num)
            payload = {
                "device_id": DEVICE_ID,
                "active_ac": f"AC_{ac_num}_{'ON' if is_on else 'OFF'}",
                "ac_number": ac_num,
                "state": "ON" if is_on else "OFF",
                "current_ampere": current_amp,
                "watt": round(current_amp * 220),
                "recorded_at": ts,
                "turbo_active": is_turbo_cooling_active
            }
            local_client.publish("pindad/ac/logs", json.dumps(payload))
            local_client.publish(f"pindad/devices/{DEVICE_ID}/telemetry", json.dumps(payload))
            send_http_telemetry(payload)
    except Exception as e:
        print(f"⚠️ [INSTANT TELEMETRY ERROR] {e}")

# ================= 5. MAIN TELEMETRY LOOP =================
def telemetry_loop():
    global is_turbo_cooling_active
    start_time = time.time()

    # Evaluasi jadwal awal saat boot
    evaluate_schedules(force=True)

    while True:
        try:
            # Check Turbo Cooling Expiry
            if is_turbo_cooling_active and (time.time() - start_time > TURBO_COOLING_SEC):
                is_turbo_cooling_active = False
                print("⏱️ [TURBO COOLING SELESAI] Masa pendinginan boot berakhir. Menjalankan rotasi jadwal RTC.")
                evaluate_schedules(force=True)

            # Evaluasi jadwal RTC DS3231 setiap siklus telemetri
            evaluate_schedules()

            ts = get_current_timestamp()

            for r in RELAYS:
                ac_num = r["ac_number"]
                is_on = relay_states.get(ac_num, False)
                
                # Baca arus dari sensor fisik ACS712
                current_amp = read_current_ampere(ac_num)

                payload = {
                    "device_id": DEVICE_ID,
                    "active_ac": f"AC_{ac_num}_{'ON' if is_on else 'OFF'}",
                    "ac_number": ac_num,
                    "state": "ON" if is_on else "OFF",
                    "current_ampere": current_amp,
                    "watt": round(current_amp * 220),
                    "recorded_at": ts,
                    "turbo_active": is_turbo_cooling_active
                }

                # 1. Publish ke local broker untuk MQTT
                local_client.publish("pindad/ac/logs", json.dumps(payload))
                local_client.publish(f"pindad/devices/{DEVICE_ID}/telemetry", json.dumps(payload))

                # 2. Dual-Sync HTTP REST langsung ke Web Dashboard
                send_http_telemetry(payload)

                # 3. Direct Edge Anomaly Alert (Tetap Mengirim Peringatan ke Telegram Walaupun Komputer Dashboard Dimatikan)
                if is_on and current_amp < 0.05 and not is_turbo_cooling_active:
                    send_direct_telegram_alert(ac_num, r.get('name', f'AC {ac_num}'), current_amp, 'GAGAL_HIDUP')

                # 4. Tampilkan log pembacaan sensor ke terminal
                print(f"📊 [TELEMETRI] AC {ac_num} ({r.get('name', 'Unit')}): {'ON 🟢' if is_on else 'OFF ⚪'} | Sensor ACS712: {current_amp:.4f} A ({payload['watt']} W) | RTC: {ts}")

            time.sleep(INTERVAL_SEC)
        except Exception as e:
            print(f"❌ [LOOP ERROR] {e}")
            time.sleep(5)

# ================= 6. START PROGRAM =================
if __name__ == "__main__":
    login_sophos()
    try:
        broker_target = config.get("mqtt_broker_host", "127.0.0.1")
        broker_p = config.get("mqtt_broker_port", 1883)
        local_client.connect(broker_target, broker_p, 60)
        local_client.loop_start()
        print(f"📡 [MQTT CONNECT SUCCESS] Terhubung ke Broker MQTT: {broker_target}:{broker_p}")
    except Exception as e:
        print(f"❌ [MQTT CONNECT ERROR] Gagal tersambung ke broker ({config.get('mqtt_broker_host')}): {e}")
        if config.get("mqtt_broker_host") != "127.0.0.1":
            print("🔄 [MQTT AUTO-RETRY] Mencoba koneksi fallback ke broker lokal 127.0.0.1:1883...")
            try:
                local_client.connect("127.0.0.1", 1883, 60)
                local_client.loop_start()
                print("📡 [MQTT CONNECT SUCCESS] Berhasil terhubung ke Broker Lokal (127.0.0.1)!")
            except Exception as e2:
                print(f"❌ [MQTT LOCAL ERROR] Broker lokal 127.0.0.1 juga tidak aktif: {e2}")

    # Start telemetry thread
    t = threading.Thread(target=telemetry_loop, daemon=True)
    t.start()

    print(f"✅ [ONLINE] Node Controller {DEVICE_ID} aktif & berjalan normal.")
    while True:
        time.sleep(1)
