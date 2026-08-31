#!/bin/bash
# =============================================================================
# PINDAD IOT ENGINE - AUTO INSTALLER & DEPENDENCY SETUP SCRIPT
# PT PINDAD (PERSERO) - DIVISI MUTU & TI
# =============================================================================

echo "======================================================="
echo "🚀 MEMULAI SETUP DEPENDENCY RASPBERRY PI PT PINDAD IOT"
echo "======================================================="

# 1. Update OS Package Repository
echo "📦 [1/4] Mengupdate package repository Linux..."
sudo apt update && sudo apt install -y python3-pip python3-smbus i2c-tools git

# 2. Aktifkan I2C & GPIO Interface
echo "🔌 [2/4] Mengaktifkan hardware interface I2C & GPIO..."
sudo raspi-config nonint do_i2c 0

# 3. Install Python Libraries
echo "🐍 [3/4] Menginstall library Python sensor (MQTT, ADS1115, DS3231)..."
pip3 install paho-mqtt adafruit-circuitpython-ads1x15 adafruit-circuitpython-ds3231 RPi.GPIO --break-system-packages 2>/dev/null || pip3 install paho-mqtt adafruit-circuitpython-ads1x15 adafruit-circuitpython-ds3231 RPi.GPIO

# 4. Verifikasi Hardware Sensor I2C
echo "🔍 [4/5] Memeriksa deteksi hardware I2C (ADS1115 = 0x48, DS3231 = 0x68)..."
i2cdetect -y 1

# 5. Konfigurasi Auto-Start Systemd Service (Booting Daemon)
echo "⚙️ [5/5] Memasang service auto-start Linux (pindad-iot.service)..."
CURRENT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SERVICE_FILE="/etc/systemd/system/pindad-iot.service"

sudo bash -c "cat > $SERVICE_FILE" <<EOF
[Unit]
Description=PINDAD IoT Node Controller Daemon
After=network.target network-online.target
Wants=network-online.target

[Service]
Type=simple
ExecStart=/usr/bin/python3 $CURRENT_DIR/pindad_universal_node.py
WorkingDirectory=$CURRENT_DIR
Restart=always
RestartSec=5
User=$USER
Environment=PYTHONUNBUFFERED=1

[Install]
WantedBy=multi-user.target
EOF

sudo systemctl daemon-reload
sudo systemctl enable pindad-iot.service
sudo systemctl restart pindad-iot.service

echo "======================================================="
echo "✅ SETUP DEPENDENCY & AUTO-START SELESAI!"
echo "Service pindad-iot.service sekarang AKTIF di latar belakang."
echo "Setiap kali Raspberry Pi dinyalakan/dicolok, program otomatis jalan!"
echo "Cek status service: sudo systemctl status pindad-iot.service"
echo "======================================================="
