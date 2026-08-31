# ❄️ Sistem Monitoring & Otomasi AC Multi-Ruangan Berbasis IoT
### 🏢 PT PINDAD (PERSERO) • Divisi Mutu & TI (Gedung TI Lt. 1)

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js" />
  <img src="https://img.shields.io/badge/MQTT-Mosquitto_Broker-660066?style=for-the-badge&logo=eclipse-mosquitto&logoColor=white" alt="MQTT Mosquitto" />
  <img src="https://img.shields.io/badge/Database-MongoDB_&_MySQL-47A248?style=for-the-badge&logo=mongodb&logoColor=white" alt="Database" />
  <img src="https://img.shields.io/badge/Hardware-Raspberry_Pi_3B-C51A4A?style=for-the-badge&logo=raspberry-pi&logoColor=white" alt="Raspberry Pi" />
</p>

---

## 📑 Daftar Isi
1. [Ringkasan Proyek](#-ringkasan-proyek)
2. [Arsitektur Sistem (Enterprise Multi-Tenancy)](#-arsitektur-sistem-enterprise-multi-tenancy)
3. [Fitur Utama Platform Web](#-fitur-utama-platform-web)
4. [Spesifikasi Hardware & Pinout GPIO](#-spesifikasi-hardware--pinout-gpio)
5. [Panduan Instalasi & Deployment](#-panduan-instalasi--deployment)
   - [Setup Server Web Laravel](#1-setup-server-web-laravel-laptop--server)
   - [Setup Node Raspberry Pi Baru (1 Perintah)](#2-setup-raspberry-pi-node-baru-metode-1-perintah)
6. [Daftar Skrip Python & Shell](#-daftar-skrip-python--shell-di-folder-scripts)
7. [Protokol Topik MQTT](#-protokol-topik-mqtt--struktur-data)
8. [Struktur Direktori Repositori](#-struktur-direktori-repositori)
9. [Catatan Rilis & Changelog](#-catatan-rilis--changelog)

---

## 📌 Ringkasan Proyek
**Sistem Monitoring & Otomasi AC Multi-Ruangan** adalah platform IoT industri yang dirancang khusus untuk memantau beban arus listrik, konsumsi daya listrik (Watt/Ampere), serta mengotomasi pergantian shift 12 jam pada unit AC di berbagai ruangan gedung PT PINDAD (PERSERO) secara *real-time* dan *fail-safe* menggunakan RTC hardware DS3231.

Platform ini mengadopsi arsitektur **Enterprise Multi-Tenancy** (mirip *Blynk IoT / AWS IoT Core*) di mana **1 Template Cetak Biru Hardware** dapat digunakan oleh **banyak perangkat ruangan (*Multi-Device Fleet*)** secara terisolasi tanpa risiko tabrakan data atau salah kendali.

---

## 🏛️ Arsitektur Sistem (Enterprise Multi-Tenancy)

```
                                    ┌─────────────────────────────────────────────────────────┐
                                    │                   CENTRAL MQTT BROKER                   │
                                    │                (Mosquitto : 127.0.0.1:1883)             │
                                    └───────────▲─────────────────────────────────▲───────────┘
                                                │                                 │
                   Telemetry: pindad/devices/{id}/logs             Command: pindad/devices/{id}/control
                                                │                                 │
               ┌────────────────────────────────┴────────┐       ┌────────────────┴────────────────────────┐
               ▼                                         ▼       ▼                                         ▼
   ┌──────────────────────┐                  ┌──────────────────────┐                  ┌──────────────────────┐
   │  NODE RUANG SERVER 1 │                  │  NODE RUANG SERVER 2 │                  │  NODE RUANG LAINNYA  │
   │  ID: RPI3B_ROOM_1    │                  │  ID: RPI3B_ROOM_2    │                  │  ID: RPI3B_ROOM_X    │
   │  IP: 192.168.197.64  │                  │  IP: 192.168.197.65  │                  │  IP: 192.168.197.xx  │
   └───────────┬──────────┘                  └───────────┬──────────┘                  └───────────┬──────────┘
               │                                         │                                         │
       [Sensor & Relai]                          [Sensor & Relai]                          [Sensor & Relai]
       • ACS712 Current                          • ACS712 Current                          • ACS712 Current
       • ADS1115 ADC (I2C)                       • ADS1115 ADC (I2C)                       • ADS1115 ADC (I2C)
       • DS3231 RTC (I2C)                        • DS3231 RTC (I2C)                        • DS3231 RTC (I2C)
       • Relai AC 1 & AC 2                       • Relai AC 1 & AC 2                       • Relai AC Multi-Ch
```

### 🔁 Alur Komunikasi Data:
1. **Telemetry Uplink:** Raspberry Pi membaca arus sensor ACS712 & status relai, kemudian mempublikasikannya setiap interval (5-15 detik) ke broker MQTT dengan identitas `device_id`.
2. **Subscriber Daemon (`php artisan mqtt:subscribe`):** Menerima pesan MQTT dan menyimpannya secara asinkron ke database MongoDB / MySQL (`ac_logs`).
3. **Web Dashboard:** Melakukan *zero-reload polling* via AJAX (`/api/logs?device_id=...`) untuk memperbarui angka Ampere, Watt, dan status saklar tanpa me-refresh halaman.
4. **Downlink Control:** Operator menekan saklar taktil pada dashboard $\rightarrow$ sinyal diteruskan ke topik kontrol spesifik per-device $\rightarrow$ relai Raspberry Pi langsung merespon seketika.

---

## 🚀 Fitur Utama Platform Web

### 📱 Modul 1: Home & Universal IoT Fleet Overview
* **Central Fleet Dashboard:** Menampilkan total konsumsi daya seluruh gedung, total arus gabungan, dan kartu status online/standby untuk seluruh ruangan yang terdaftar.
* **Device Drilldown Detail:**
  * **Saklar Taktil IoT (Zero-Reload AJAX):** Saklar ON/OFF animasi fisik (*tactile slider*) yang bergeser instan dalam 0ms tanpa ada kedipan atau *re-log* halaman.
  * **Pembacaan Arus Real-Time:** Ampere presisi 4 desimal & estimasi Watt dihitung langsung per-unit AC.
  * **Isolasi Data Ketat (*Strict Device Isolation*):** Ruang Server 1 dan Ruang Server 2 memiliki data telemetri dan jalur kontrol terpisah 100%.
  * **Otomasi Shift RTC DS3231:** Menampilkan shift aktif (06:00 - 18:00 WIB) dengan tombol **Edit Jam Shift (`✏️`)** dan tombol **Hapus (`🗑️`)**.

### 🛠️ Modul 2: DevZone (Developer Console ala Blynk IoT)
* **Template Blueprint Manager:** Buat cetak biru spesifikasi hardware (nama, tipe hardware, deskripsi, icon).
* **Virtual Datastreams Editor:** Atur Virtual Pin (V0, V1, V2, dst.), tipe data (Integer, Double, String), nilai min/max, dan unit satuan.
* **Auto-Instantiate Device:** Buat perangkat fisik baru dengan memilih salah satu template yang tersedia.
* **Auto-Generated Device ID:** Field ID perangkat MQTT dibuat otomatis, terkunci (*readonly*), dan tersanitasi standar huruf besar (`RPI3B_...`).

### 📊 Modul 3: Log Telemetri & Audit Trail
* **Riwayat Telemetri Lengkap:** Tabel log pembacaan arus, status saklar, sumber pemicu (*manual / schedule / boot*), dan timestamp WIB.
* **Multi-Device Filter:** Filter log berdasarkan ruangan tertentu atau lihat seluruh data armada.
* **Ekspor Data CSV:** Download rekaman telemetri langsung ke format file Excel / CSV.

### ⚙️ Modul 4: Sistem, Akun & Pusat Edukasi Lengkap
* **1-Perintah Interactive Setup Wizard:** Panduan terminal interaktif untuk menyiapkan Raspberry Pi baru tanpa perlu mengetik kodingan secara manual.
* **SOP Multi-Room Hardware:** Panduan lengkap konfigurasi 2-Channel vs 4-Channel Relay via `node_config.json`.
* **Auto-Start Systemd Daemon Guide:** Panduan konfigurasi service Linux (`pindad-iot.service`) agar program jalan otomatis saat listrik pulih/booting.
* **Pusat Unduhan Skrip & Firmware:** Download langsung skrip Python universal, file template JSON, script installer `.sh`, dan skrip legacy.

---

## 📟 Spesifikasi Hardware & Pinout GPIO

| Komponen Hardware | Interface / Pin Raspberry Pi | Keterangan / Fungsi |
| :--- | :--- | :--- |
| **Relay Channel 1 (AC 1)** | `GPIO 17` (Pin Fisik 11) | Kontrol Saklar Panasonic 1 (Lampu Panel Bawah) |
| **Relay Channel 2 (AC 2)** | `GPIO 27` (Pin Fisik 13) | Kontrol Saklar Panasonic 2 (Lampu Panel Atas) |
| **Relay Channel 3 (AC 3)** | `GPIO 22` (Pin Fisik 15) | Kontrol AC 3 (Khusus Modul 4-Channel) |
| **Relay Channel 4 (AC 4)** | `GPIO 23` (Pin Fisik 16) | Kontrol AC 4 (Khusus Modul 4-Channel) |
| **ADC ADS1115** | `I2C SDA (GPIO 2)` / `SCL (GPIO 3)` | Alamat I2C: `0x48` • Konversi analog ACS712 ke digital |
| **Sensor Arus ACS712 (AC 1)** | Masuk ke Channel `AIN0` ADS1115 | Pembacaan Arus AC 1 (Sensitivitas 185 mV/A) |
| **Sensor Arus ACS712 (AC 2)** | Masuk ke Channel `AIN1` ADS1115 | Pembacaan Arus AC 2 (Sensitivitas 185 mV/A) |
| **RTC Module DS3231** | `I2C SDA (GPIO 2)` / `SCL (GPIO 3)` | Alamat I2C: `0x68` • Pewaktu presisi offline anti mati listrik |

---

## 💻 Panduan Instalasi & Deployment

### 1. Setup Server Web Laravel (Laptop / Server)
Pastikan PHP 8.2+, Composer, Mosquitto MQTT Broker, dan MongoDB/MySQL sudah terpasang:

```bash
# 1. Clone repository
git clone https://github.com/W2LS/Sistem_Monitoring_AC.git
cd Sistem_Monitoring_AC/Dashboard

# 2. Install dependencies PHP
composer install

# 3. Salin environment & generate key
cp .env.example .env
php artisan key:generate

# 4. Jalankan migrasi database
php artisan migrate

# 5. Jalankan server Laravel & background workers
php artisan serve --host=0.0.0.0 --port=8000
php artisan mqtt:subscribe
php artisan ac:schedule-worker
```

---

### 2. Setup Raspberry Pi Node Baru (Metode 1-Perintah)
Pada Raspberry Pi OS yang baru diinstal, jalankan perintah terminal berikut:

```bash
# 1. Clone repository & masuk ke folder scripts
git clone https://github.com/W2LS/Sistem_Monitoring_AC.git
cd Sistem_Monitoring_AC/scripts

# 2. Jalankan script setup wizard otomatis
bash setup_raspberry_pi.sh
```

**Alur yang Dilakukan Setup Wizard:**
1. Otomatis update sistem & mengaktifkan port I2C hardware (`raspi-config`).
2. Otomatis install seluruh library Python (`paho-mqtt`, `ads1x15`, `ds3231`, `RPi.GPIO`).
3. Menjalankan CLI Wizard interaktif:
   * Menanyakan ID Perangkat (contoh: `RPI3B_MONITORING_AC_RUANG_SERVER_2`).
   * Menanyakan Alamat IP Broker MQTT.
   * Menanyakan Jumlah AC (2 atau 4 unit) dan nomor Pin GPIO Relay.
4. Menghasilkan file konfigurasi `node_config.json` secara otomatis.
5. Mendaftarkan dan mengaktifkan service Linux Systemd (`pindad-iot.service`) sehingga program otomatis menyala saat Raspberry Pi dicolokkan ke listrik.

---

## 📂 Daftar Skrip Python & Shell di Folder `scripts/`

| File Skrip | Fungsi & Deskripsi |
| :--- | :--- |
| [`scripts/setup_raspberry_pi.sh`](file:///c:/Users/dicky/Documents/Prototype/Project%20Maggang%201/Dashboard/scripts/setup_raspberry_pi.sh) | **Auto Installer Shell:** 1-klik instalasi dependensi, eksekusi wizard, dan konfigurasi auto-start systemd service. |
| [`scripts/pindad_setup_wizard.py`](file:///c:/Users/dicky/Documents/Prototype/Project%20Maggang%201/Dashboard/scripts/pindad_setup_wizard.py) | **Interactive CLI Wizard:** Memandu pengisian GPIO pin dan menghasilkan file `node_config.json` secara otomatis. |
| [`scripts/pindad_universal_node.py`](file:///c:/Users/dicky/Documents/Prototype/Project%20Maggang%201/Dashboard/scripts/pindad_universal_node.py) | **Universal Client Python:** Skrip node standar untuk seluruh Raspberry Pi (membaca konfigurasi dari `node_config.json`). |
| [`scripts/node_config.json`](file:///c:/Users/dicky/Documents/Prototype/Project%20Maggang%201/Dashboard/scripts/node_config.json) | **File Konfigurasi Node:** Menyimpan pengaturan `device_id`, IP broker, daftar AC, dan pemetaan GPIO pin. |
| [`scripts/pindad_ac_monitoring.py`](file:///c:/Users/dicky/Documents/Prototype/Project%20Maggang%201/Dashboard/scripts/pindad_ac_monitoring.py) | **Legacy Node Client:** Skrip khusus unit Ruang Server 1 (kompatibilitas dual Blynk & MQTT lokal). |

---

## 📡 Protokol Topik MQTT & Struktur Data

### 1. Topik Telemetri (Uplink: Node $\rightarrow$ Server)
* **Topik:** `pindad/devices/{device_id}/logs` atau `pindad/ac/logs`
* **Payload JSON:**
```json
{
  "device_id": "RPI3B_MONITORING_AC_RUANG_SERVER_2",
  "active_ac": "AC_1_ON",
  "current_ampere": 2.1874,
  "watt_approx": 481.23,
  "trigger_source": "schedule",
  "recorded_at": "2026-08-31T15:00:00+07:00"
}
```

### 2. Topik Kontrol Saklar (Downlink: Server $\rightarrow$ Node)
* **Topik:** `pindad/devices/{device_id}/control`
* **Payload JSON:**
```json
{
  "device_id": "RPI3B_MONITORING_AC_RUANG_SERVER_2",
  "relay": 1,
  "command": "ON",
  "ac_number": 1,
  "state": "ON",
  "source": "manual",
  "timestamp": "2026-08-31T15:10:00+07:00"
}
```

---

## 📁 Struktur Direktori Repositori

```text
Dashboard/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          # Autentikasi operator
│   │   └── DashboardController.php     # Controller utama Fleet, Telemetri, DevZone, Jadwal
│   ├── Models/
│   │   ├── AcLog.php                   # Model log telemetri arus
│   │   ├── Device.php                  # Model instance perangkat ruangan
│   │   ├── Template.php                # Model blueprint template hardware
│   │   ├── Schedule.php                # Model jadwal shift rotasi RTC
│   │   └── User.php                    # Model akun pengguna
│   └── Services/
│       └── MqttService.php             # Service publikasi & subscriber MQTT
├── resources/
│   └── views/
│       ├── dashboard.blade.php         # Layout utama dashboard
│       └── partials/
│           ├── section-home.blade.php  # Modul 1: Fleet & Detail Kontrol Ruangan
│           ├── section-devzone.blade.php # Modul 2: Developer Zone (Template & Datastream)
│           ├── section-log.blade.php   # Modul 3: Riwayat Log Telemetri & Ekspor CSV
│           ├── section-akun.blade.php  # Modul 4: SOP Edukasi, Pusat Unduhan, Info Akun
│           └── floating-nav.blade.php  # Navigasi melayang responsif
├── routes/
│   └── web.php                         # Definisi rute web & API AJAX
└── scripts/
    ├── node_config.json                # Template konfigurasi node
    ├── pindad_ac_monitoring.py         # Skrip node legacy Ruang Server 1
    ├── pindad_universal_node.py        # Skrip universal client multi-room
    ├── pindad_setup_wizard.py          # Interactive CLI setup wizard
    ├── setup_raspberry_pi.sh           # Auto-installer shell script
    └── requirements.txt                # Daftar dependensi pip Python
```

---

## 📝 Catatan Rilis & Changelog

### Versi 2.4.0 (Agustus 2026) - *Enterprise Fleet & UX Polish*
* ✨ **Zero-Reload Tactile Slider:** Saklar AC mengadopsi kontrol reaktif Alpine.js AJAX (animasi instan 0ms tanpa reload/re-log halaman).
* 🔒 **Strict Device Isolation:** Isolasi ketat telemetri dan perintah MQTT antar ruangan (Ruang Server 1 & Ruang Server 2 tidak saling mempengaruhi).
* ✏️ **Edit Jam Jadwal Shift:** Penambahan modal edit jam mulai, jam berakhir, nama shift, dan unit target AC.
* ⚡ **1-Command Interactive Setup Wizard:** Skrip `setup_raspberry_pi.sh` dan `pindad_setup_wizard.py` untuk pendaftaran node baru anti-ribet.
* 📥 **Pusat Unduhan Skrip Terintegrasi:** Endpoint unduhan langsung untuk file skrip `.py`, template `.json`, dan installer `.sh`.
* 🛡️ **Auto-Generated Readonly Device ID:** ID perangkat MQTT otomatis dibuat dan distandarisasi saat nama ruangan diketik.
* 🗑️ **Konsistensi Manajemen Armada:** Tombol hapus perangkat seragam di seluruh kartu dengan dialog konfirmasi aman.

---

<p align="center">
  <b>Developed with ❤️ for PT PINDAD (PERSERO) • Divisi Mutu & TI</b><br>
  <i>Sistem Monitoring AC Multi-Ruangan Berbasis IoT Terintegrasi</i>
</p>
