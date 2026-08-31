<!-- ================= MODUL 4: PUSAT INFORMASI AKUN & SISTEM IOT ================= -->
<div class="space-y-6 pb-20" x-data="{ 
    openItem: 'tutorial',
    modalPassword: false,
}">
    
    <!-- 1. PAGE HEADER -->
    <div class="border-b border-[#8E1616]/20 pb-4">
        <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#8E1616] flex items-center gap-1.5">
            <span>⚙️</span>
            <span>PUSAT PENGATURAN & INFORMASI SISTEM</span>
        </span>
        <h2 class="text-2xl sm:text-3xl font-black text-[#1D1616] tracking-tight mt-0.5">
            Akun Operator & Informasi Sistem
        </h2>
        <p class="text-xs font-semibold text-slate-500 mt-1">
            Panduan lengkap penggunaan sistem, manajemen kredensial operator, dan spesifikasi arsitektur IoT PT PINDAD.
        </p>
    </div>

    <!-- 2. ACCORDIONS SECTION -->
    <div class="space-y-4">

        <!-- ITEM 0: TUTORIAL & PANDUAN PENGGUNAAN SISTEM (STEP-BY-STEP SOP) -->
        <div class="bg-white rounded-[32px] border-2 border-[#D84040]/40 shadow-md overflow-hidden transition-all duration-300">
            <button @click="openItem = openItem === 'tutorial' ? null : 'tutorial'" 
                    type="button" 
                    class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer bg-gradient-to-r from-rose-50/50 to-white">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#D84040] text-white flex items-center justify-center font-black text-xl shrink-0 shadow-md shadow-[#D84040]/30">
                        📖
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-black text-[#1D1616]">Tutorial & Panduan Penggunaan Platform</h3>
                            <span class="bg-[#D84040] text-white text-[9px] font-black uppercase px-2 py-0.5 rounded-full">PENTING</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-500">Panduan langkah demi langkah dari membuat template hingga mengontrol AC di ruangan</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'tutorial' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT: STEP BY STEP GUIDE -->
            <div x-show="openItem === 'tutorial'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-4 border-t border-[#8E1616]/10 space-y-4 bg-slate-50/70">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    
                    <!-- Step 1: DevZone -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2 relative overflow-hidden">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-[#8E1616] text-white flex items-center justify-center font-black text-xs shrink-0">1</span>
                            <h4 class="font-black text-sm text-[#1D1616]">Rancang Template di DevZone (Modul 2)</h4>
                        </div>
                        <p class="text-slate-600 leading-relaxed">
                            Buka menu <b>DevZone</b> untuk membuat atau memilih blueprint hardware (misal <i>Raspberry Pi 3B+</i>). Konfigurasikan saluran <b>Virtual Pin (Datastreams)</b> seperti <code class="bg-slate-100 px-1 py-0.5 rounded font-bold text-[#8E1616]">V0</code> untuk Relay AC 1, <code class="bg-slate-100 px-1 py-0.5 rounded font-bold text-[#8E1616]">V1</code> untuk Relay AC 2, dan <code class="bg-slate-100 px-1 py-0.5 rounded font-bold text-[#8E1616]">V2</code> untuk Sensor Arus.
                        </p>
                    </div>

                    <!-- Step 2: Tambah Device -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2 relative overflow-hidden">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-[#8E1616] text-white flex items-center justify-center font-black text-xs shrink-0">2</span>
                            <h4 class="font-black text-sm text-[#1D1616]">Tambahkan Perangkat Baru di Home (Modul 1)</h4>
                        </div>
                        <p class="text-slate-600 leading-relaxed">
                            Di halaman <b>Home</b>, klik tombol <b>`+ Tambah Perangkat Baru`</b>. Pilih Template yang sudah dibuat, tentukan Nama Ruangan (misal <i>Ruang Server Internet</i>), dan masukkan <b>Device ID</b> unik (misal <code class="bg-slate-100 px-1 py-0.5 rounded font-bold text-[#1D1616]">RPI3B_RUANG_INTERNET</code>).
                        </p>
                    </div>

                    <!-- Step 3: Setup Hardware -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2 relative overflow-hidden">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-[#8E1616] text-white flex items-center justify-center font-black text-xs shrink-0">3</span>
                            <h4 class="font-black text-sm text-[#1D1616]">Jalankan Skrip di Raspberry Pi Ruangan</h4>
                        </div>
                        <p class="text-slate-600 leading-relaxed">
                            Di Raspberry Pi fisik ruangan tersebut, copy folder <code class="bg-slate-100 px-1 py-0.5 rounded font-mono">scripts/</code>, isi file <code class="bg-slate-100 px-1 py-0.5 rounded font-mono">node_config.json</code> dengan Device ID yang cocok, lalu jalankan:
                            <code class="block mt-1 bg-[#1D1616] text-emerald-400 p-2 rounded-lg font-mono text-[11px]">python3 pindad_universal_node.py</code>
                            Perangkat akan otomatis terhubung ke MQTT dan lampu status di web menjadi <b>🟢 Online</b>.
                        </p>
                        <div class="pt-1.5 flex items-center gap-2">
                            <a href="{{ route('scripts.download', 'universal_node') }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[10px] uppercase tracking-wider flex items-center gap-1">
                                <span>📥 Unduh .py</span>
                            </a>
                            <a href="{{ route('scripts.download', 'config') }}" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-[#D84040] font-bold text-[10px] uppercase tracking-wider flex items-center gap-1">
                                <span>📥 Unduh .json</span>
                            </a>
                        </div>
                    </div>

                    <!-- Step 4: Kontrol & Jadwal -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2 relative overflow-hidden">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-[#8E1616] text-white flex items-center justify-center font-black text-xs shrink-0">4</span>
                            <h4 class="font-black text-sm text-[#1D1616]">Kontrol Saklar & Atur Jadwal Shift</h4>
                        </div>
                        <p class="text-slate-600 leading-relaxed">
                            Di kartu perangkat ruangan, klik <b>`Buka Kontrol & Jadwal ➔`</b>. Anda dapat menyalakan/mematikan AC secara manual atau mengatur <b>Jadwal Rotasi Shift 12 Jam Otomatis</b> berbasis RTC DS3231 tanpa khawatir padam listrik.
                        </p>
                    </div>

                </div>

                <!-- Summary Tips Banner -->
                <div class="bg-gradient-to-r from-[#1D1616] to-[#8E1616] text-white p-4 rounded-2xl flex items-center gap-3 text-xs">
                    <span class="text-2xl">💡</span>
                    <div>
                        <span class="font-black block text-sm">Hubungan Template vs Device:</span>
                        <p class="text-slate-200 text-[11px] mt-0.5">
                            <b>Template</b> adalah cetak biru spesifikasi hardware (dibuat sekali di DevZone). <b>Device</b> adalah wujud fisik alat yang terpasang di masing-masing ruangan gedung PT PINDAD.
                        </p>
                    </div>
                </div>

                <!-- Hardware Node Multi-Room Guide (2-Channel vs 4-Channel Relay) -->
                <div class="bg-white p-5 rounded-2xl border-2 border-indigo-200 shadow-xs space-y-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black text-base shrink-0">
                            ⚙️
                        </div>
                        <div>
                            <h4 class="font-black text-sm text-[#1D1616]">Konfigurasi Hardware Multi-Ruangan (2-Channel vs 4-Channel)</h4>
                            <p class="text-xs text-slate-500">Program Python <b>100% SAMA PERSIS</b> di seluruh Raspberry Pi. Cukup ubah file <code class="bg-slate-100 px-1 py-0.5 rounded font-mono font-bold text-indigo-600">node_config.json</code> di masing-masing alat:</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-[11px] font-mono">
                        <!-- Server A (2 Relay) -->
                        <div class="bg-slate-900 text-slate-200 p-3.5 rounded-xl border border-slate-800 space-y-1">
                            <span class="text-emerald-400 font-bold font-sans block text-xs">📌 Ruang Server A (Relay 2 Channel):</span>
                            <pre class="text-[10.5px] leading-tight text-slate-300 overflow-x-auto">{
  "device_id": "RPI3B_SERVER_A",
  "relays": [
    {"ac_number": 1, "gpio_pin": 17, "name": "AC 1"},
    {"ac_number": 2, "gpio_pin": 27, "name": "AC 2"}
  ]
}</pre>
                        </div>

                        <!-- Server B (4 Relay) -->
                        <div class="bg-slate-900 text-slate-200 p-3.5 rounded-xl border border-slate-800 space-y-1">
                            <span class="text-amber-400 font-bold font-sans block text-xs">📌 Ruang Server B (Relay 4 Channel):</span>
                            <pre class="text-[10.5px] leading-tight text-slate-300 overflow-x-auto">{
  "device_id": "RPI3B_SERVER_B",
  "relays": [
    {"ac_number": 1, "gpio_pin": 17, "name": "AC 1"},
    {"ac_number": 2, "gpio_pin": 27, "name": "AC 2"},
    {"ac_number": 3, "gpio_pin": 22, "name": "AC 3"},
    {"ac_number": 4, "gpio_pin": 23, "name": "AC 4"}
  ]
}</pre>
                        </div>
                    </div>
                </div>

                <!-- Terminal Setup & Dependency Guide -->
                <div class="bg-white p-5 rounded-2xl border-2 border-emerald-300 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-base shrink-0">
                                💻
                            </div>
                            <div>
                                <h4 class="font-black text-sm text-[#1D1616]">Perintah Terminal Setup Raspberry Pi Baru</h4>
                                <p class="text-xs text-slate-500">Jalankan perintah ini di terminal Raspberry Pi OS sebelum menjalankan program:</p>
                            </div>
                        </div>
                        <a href="{{ route('scripts.download', 'setup') }}" class="px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-[11px] uppercase tracking-wider transition shrink-0 hidden sm:inline-flex items-center gap-1">
                            <span>📥 Unduh setup.sh</span>
                        </a>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <!-- Step A -->
                        <div class="bg-slate-900 text-slate-200 p-3 rounded-xl border border-slate-800 space-y-1">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-emerald-400 font-sans">1. Update OS Linux & Aktifkan Port I2C Hardware:</span>
                                <span class="text-slate-400 font-mono text-[10px]">Linux Shell</span>
                            </div>
                            <code class="block font-mono text-slate-100 text-[11px] select-all bg-black/40 p-2 rounded-lg">
                                sudo apt update && sudo apt install -y python3-pip python3-smbus i2c-tools git && sudo raspi-config nonint do_i2c 0
                            </code>
                        </div>

                        <!-- Step B -->
                        <div class="bg-slate-900 text-slate-200 p-3 rounded-xl border border-slate-800 space-y-1">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-amber-400 font-sans">2. Install Library Sensor Python (MQTT, ADS1115, DS3231):</span>
                                <span class="text-slate-400 font-mono text-[10px]">Python Pip</span>
                            </div>
                            <code class="block font-mono text-slate-100 text-[11px] select-all bg-black/40 p-2 rounded-lg">
                                pip3 install paho-mqtt adafruit-circuitpython-ads1x15 adafruit-circuitpython-ds3231 RPi.GPIO --break-system-packages
                            </code>
                        <!-- Step C -->
                        <div class="bg-slate-900 text-slate-200 p-3 rounded-xl border border-slate-800 space-y-1">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-sky-400 font-sans">3. Clone Repo & Jalankan Node:</span>
                                <span class="text-slate-400 font-mono text-[10px]">Execute</span>
                            </div>
                            <code class="block font-mono text-slate-100 text-[11px] select-all bg-black/40 p-2 rounded-lg">
                                git clone https://github.com/W2LS/Sistem_Monitoring_AC.git && cd Sistem_Monitoring_AC/scripts && python3 pindad_universal_node.py
                            </code>
                        </div>
                    </div>
                </div>

                <!-- 3 Easy Deployment Methods -->
                <div class="bg-white p-5 rounded-2xl border-2 border-amber-300 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-black text-base shrink-0">
                            🚀
                        </div>
                        <div>
                            <h4 class="font-black text-sm text-[#1D1616]">3 Cara Termudah Menjalankan Program di Raspberry Pi Baru</h4>
                            <p class="text-xs text-slate-500">Pilih salah satu cara di bawah ini (Tidak perlu copy-paste manual via nano):</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                        <!-- Option 1: Git Clone -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2 flex flex-col justify-between">
                            <div class="space-y-1">
                                <span class="font-black text-xs text-[#1D1616] flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px]">1</span>
                                    <span>Cara 1: Git Clone (3 Detik)</span>
                                </span>
                                <p class="text-[11px] text-slate-600 leading-relaxed">Download folder lengkap dari repo resmi:</p>
                            </div>
                            <code class="block font-mono text-[10.5px] bg-slate-900 text-emerald-400 p-2.5 rounded-lg select-all overflow-x-auto leading-tight">
                                git clone https://github.com/W2LS/Sistem_Monitoring_AC.git<br>
                                cd Sistem_Monitoring_AC/scripts<br>
                                python3 pindad_universal_node.py
                            </code>
                        </div>

                        <!-- Option 2: Wget Direct -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2 flex flex-col justify-between">
                            <div class="space-y-1">
                                <span class="font-black text-xs text-[#1D1616] flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px]">2</span>
                                    <span>Cara 2: Wget Download</span>
                                </span>
                                <p class="text-[11px] text-slate-600 leading-relaxed">Download 2 file langsung dari web dashboard:</p>
                            </div>
                            <code class="block font-mono text-[10.5px] bg-slate-900 text-blue-300 p-2.5 rounded-lg select-all overflow-x-auto leading-tight">
                                wget http://IP-SERVER:8000/scripts/download/node -O node.py<br>
                                wget http://IP-SERVER:8000/scripts/download/config -O node_config.json<br>
                                python3 node.py
                            </code>
                        </div>

                        <!-- Option 3: Flashdisk -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2 flex flex-col justify-between">
                            <div class="space-y-1">
                                <span class="font-black text-xs text-[#1D1616] flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-full bg-purple-600 text-white flex items-center justify-center text-[10px]">3</span>
                                    <span>Cara 3: Flashdisk (Offline)</span>
                                </span>
                                <p class="text-[11px] text-slate-600 leading-relaxed">Copy folder scripts dari laptop ke USB drive:</p>
                            </div>
                            <div class="bg-slate-900 text-slate-200 p-2.5 rounded-lg text-[10.5px] leading-tight space-y-1">
                                <p>1. Copy folder <code class="text-amber-400">scripts/</code> ke Flashdisk.</p>
                                <p>2. Colok Flashdisk ke Raspberry Pi.</p>
                                <p>3. Buka terminal & jalankan <code class="text-emerald-400">python3 pindad_universal_node.py</code></p>
                            </div>
                        </div>
                    </div>
                <!-- Auto-Start Systemd Service Guide -->
                <div class="bg-white p-5 rounded-2xl border-2 border-rose-300 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-800 flex items-center justify-center font-black text-base shrink-0">
                            🔌
                        </div>
                        <div>
                            <h4 class="font-black text-sm text-[#1D1616]">Konfigurasi Auto-Start Service (Jalan Otomatis Saat Booting / Listrik Pulih)</h4>
                            <p class="text-xs text-slate-500">Agar Raspberry Pi baru otomatis menjalankan program saat dicolokkan ke listrik (persis seperti unit Alex):</p>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs">
                        <!-- Step 1 -->
                        <div class="bg-slate-900 text-slate-200 p-3.5 rounded-xl border border-slate-800 space-y-1.5">
                            <span class="text-emerald-400 font-bold block text-xs font-sans">1. Buat file service Linux systemd:</span>
                            <code class="block font-mono text-[11px] bg-black/40 p-2 rounded-lg text-slate-100 select-all">sudo nano /etc/systemd/system/pindad-iot.service</code>
                        </div>

                        <!-- Step 2 -->
                        <div class="bg-slate-900 text-slate-200 p-3.5 rounded-xl border border-slate-800 space-y-1.5">
                            <span class="text-amber-400 font-bold block text-xs font-sans">2. Paste konfigurasi service ini (Tekan Ctrl+O &rarr; Enter &rarr; Ctrl+X):</span>
                            <pre class="text-[10.5px] leading-tight text-slate-300 bg-black/40 p-3 rounded-lg overflow-x-auto font-mono">[Unit]
Description=PINDAD IoT Node Controller Daemon
After=network.target network-online.target
Wants=network-online.target

[Service]
Type=simple
ExecStart=/usr/bin/python3 /home/pi/Sistem_Monitoring_AC/scripts/pindad_universal_node.py
WorkingDirectory=/home/pi/Sistem_Monitoring_AC/scripts
Restart=always
RestartSec=5
User=pi
Environment=PYTHONUNBUFFERED=1

[Install]
WantedBy=multi-user.target</pre>
                        </div>

                        <!-- Step 3 -->
                        <div class="bg-slate-900 text-slate-200 p-3.5 rounded-xl border border-slate-800 space-y-1.5">
                            <span class="text-sky-400 font-bold block text-xs font-sans">3. Aktifkan dan jalankan service otomatis:</span>
                            <code class="block font-mono text-[11px] bg-black/40 p-2 rounded-lg text-slate-100 select-all">sudo systemctl daemon-reload && sudo systemctl enable pindad-iot.service && sudo systemctl start pindad-iot.service</code>
                            <p class="text-[11px] text-slate-400 font-sans mt-1">Cek status service kapan saja dengan: <code class="text-emerald-400 font-mono">sudo systemctl status pindad-iot.service</code></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ITEM DOWNLOAD: PUSAT UNDUHAN SKRIP & FIRMWARE NODE -->
        <div class="bg-white rounded-[32px] border border-[#8E1616]/20 shadow-xs overflow-hidden transition-all duration-300">
            <button @click="openItem = openItem === 'download' ? null : 'download'" 
                    type="button" 
                    class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-xl shrink-0">
                        📥
                    </div>
                    <div>
                        <h3 class="text-base font-black text-[#1D1616]">Pusat Unduhan Skrip & Firmware Raspberry Pi</h3>
                        <p class="text-xs font-semibold text-slate-500">Unduh langsung file skrip Python universal dan template konfigurasi JSON untuk setup alat</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'download' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT -->
            <div x-show="openItem === 'download'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-4 bg-slate-50/60">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 pt-2 text-xs">
                    
                    <!-- 1. Universal Node Script -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-3 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2">
                                <span class="text-xl">🐍</span>
                                <h4 class="font-black text-sm text-[#1D1616]">Universal Client</h4>
                            </div>
                            <p class="text-slate-500 text-[11px] leading-relaxed">
                                Skrip Python universal untuk semua Raspberry Pi di berbagai ruangan.
                            </p>
                            <span class="text-[10px] font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-700 block truncate">pindad_universal_node.py</span>
                        </div>
                        <a href="{{ route('scripts.download', 'universal_node') }}" 
                           class="inline-flex items-center justify-center gap-1.5 bg-[#1D1616] hover:bg-[#8E1616] text-white py-2 px-3 rounded-xl font-bold text-[11px] uppercase tracking-wider shadow-sm transition active:scale-95">
                            <span>📥 Unduh .py</span>
                        </a>
                    </div>

                    <!-- 2. Node Config JSON -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-3 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2">
                                <span class="text-xl">⚙️</span>
                                <h4 class="font-black text-sm text-[#1D1616]">Template Config</h4>
                            </div>
                            <p class="text-slate-500 text-[11px] leading-relaxed">
                                File pengaturan JSON untuk Device ID, GPIO pin relay, dan sensor.
                            </p>
                            <span class="text-[10px] font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-700 block truncate">node_config.json</span>
                        </div>
                        <a href="{{ route('scripts.download', 'config') }}" 
                           class="inline-flex items-center justify-center gap-1.5 bg-[#D84040] hover:bg-[#8E1616] text-white py-2 px-3 rounded-xl font-bold text-[11px] uppercase tracking-wider shadow-sm transition active:scale-95">
                            <span>📥 Unduh .json</span>
                        </a>
                    </div>

                    <!-- 3. Installer Script -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-3 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2">
                                <span class="text-xl">⚡</span>
                                <h4 class="font-black text-sm text-[#1D1616]">Auto Installer</h4>
                            </div>
                            <p class="text-slate-500 text-[11px] leading-relaxed">
                                Shell script otomatis untuk install semua dependency di Linux OS.
                            </p>
                            <span class="text-[10px] font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-700 block truncate">setup_raspberry_pi.sh</span>
                        </div>
                        <a href="{{ route('scripts.download', 'setup') }}" 
                           class="inline-flex items-center justify-center gap-1.5 bg-emerald-700 hover:bg-emerald-800 text-white py-2 px-3 rounded-xl font-bold text-[11px] uppercase tracking-wider shadow-sm transition active:scale-95">
                            <span>📥 Unduh .sh</span>
                        </a>
                    </div>

                    <!-- 4. Legacy AC Monitoring -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-3 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2">
                                <span class="text-xl">❄️</span>
                                <h4 class="font-black text-sm text-[#1D1616]">Dual AC Monitor</h4>
                            </div>
                            <p class="text-slate-500 text-[11px] leading-relaxed">
                                Skrip khusus kontrol 2 AC Ruang Server 1 dengan fail-safe RTC DS3231.
                            </p>
                            <span class="text-[10px] font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-700 block truncate">pindad_ac_monitoring.py</span>
                        </div>
                        <a href="{{ route('scripts.download', 'pindad_ac') }}" 
                           class="inline-flex items-center justify-center gap-1.5 bg-slate-800 hover:bg-slate-900 text-white py-2 px-3 rounded-xl font-bold text-[11px] uppercase tracking-wider shadow-sm transition active:scale-95">
                            <span>📥 Unduh .py</span>
                        </a>
                    </div>

                </div>

                <!-- Terminal Quick Setup Command -->
                <div class="bg-[#1D1616] text-white p-4 rounded-2xl space-y-2 border border-slate-800">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#D84040]">Setup Cepat via Terminal Raspberry Pi:</span>
                        <span class="text-[10px] text-slate-400 font-mono">Terminal CLI</span>
                    </div>
                    <code class="block bg-black/50 p-2.5 rounded-xl font-mono text-emerald-400 text-[11px] select-all overflow-x-auto">
                        git clone https://github.com/W2LS/Sistem_Monitoring_AC.git && cd Sistem_Monitoring_AC/scripts
                    </code>
                </div>
            </div>
        </div>
        
        <!-- ITEM 1: INFORMASI AKUN & PROFIL OPERATOR -->
        <div class="bg-white rounded-[32px] border border-[#8E1616]/20 shadow-xs overflow-hidden transition-all duration-300">
            <button @click="openItem = openItem === 'akun' ? null : 'akun'" 
                    type="button" 
                    class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-xl shrink-0">
                        👤
                    </div>
                    <div>
                        <h3 class="text-base font-black text-[#1D1616]">Profil & Akun Operator</h3>
                        <p class="text-xs font-semibold text-slate-500">Informasi pengguna aktif dan hak akses kontrol dashboard</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'akun' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT -->
            <div x-show="openItem === 'akun'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-4 bg-slate-50/60">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs pt-2">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Nama Lengkap</span>
                        <span class="font-black text-[#1D1616] text-sm block mt-0.5">{{ $user->name ?? 'Dicky Akbar Syahputra' }}</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Email Operator</span>
                        <span class="font-mono font-bold text-slate-700 text-xs block mt-0.5">{{ $user->email ?? 'dicky.akbar@pindad.com' }}</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Divisi</span>
                        <span class="font-bold text-[#1D1616] block mt-0.5">Mutu & TI / Fasilitas Gedung</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Peran Sistem</span>
                        <span class="font-bold text-emerald-600 block mt-0.5">● Super Administrator</span>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-slate-200 text-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <button @click="modalPassword = true" 
                                type="button"
                                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition cursor-pointer">
                            🔒 Ubah Kata Sandi
                        </button>
                    </div>

                    <!-- FORM LOGOUT -->
                    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sesi operator?')">
                        @csrf
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-[#D84040] hover:bg-[#8E1616] text-white text-xs font-black uppercase tracking-wider shadow-md transition cursor-pointer flex items-center space-x-2">
                            <span>🚪</span>
                            <span>Keluar / Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ITEM 2: INFORMASI SISTEM & SPESIFIKASI WEB ENGINE -->
        <div class="bg-white rounded-[32px] border border-[#8E1616]/20 shadow-xs overflow-hidden transition-all duration-300">
            <button @click="openItem = openItem === 'sistem' ? null : 'sistem'" 
                    type="button" 
                    class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-xl shrink-0">
                        🖥️
                    </div>
                    <div>
                        <h3 class="text-base font-black text-[#1D1616]">Informasi Web & Server Platform</h3>
                        <p class="text-xs font-semibold text-slate-500">Spesifikasi software engine, database MongoDB, dan broker MQTT</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'sistem' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT -->
            <div x-show="openItem === 'sistem'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-4 bg-slate-50/60">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-xs pt-2">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Versi Dashboard</span>
                        <span class="font-black text-[#1D1616] text-sm block mt-0.5">v2.5.0 (Blynk IoT Edition)</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Database Mesin</span>
                        <span class="font-bold text-emerald-600 block mt-0.5">🟢 MongoDB Atlas / Local</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">MQTT Broker</span>
                        <span class="font-bold text-emerald-600 block mt-0.5">🟢 Mosquitto TCP 1883</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Cloud IoT Protocol</span>
                        <span class="font-bold text-[#1D1616] block mt-0.5">Blynk REST & MQTT Bridge</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Firewall Integration</span>
                        <span class="font-bold text-[#1D1616] block mt-0.5">Sophos Captive Portal Auto-Auth</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Instansi Pemilik</span>
                        <span class="font-bold text-[#1D1616] block mt-0.5">PT PINDAD (Persero) Bandung</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ITEM 3: SPESIFIKASI PERANGKAT KERAS IOT -->
        <div class="bg-white rounded-[32px] border border-[#8E1616]/20 shadow-xs overflow-hidden transition-all duration-300">
            <button @click="openItem = openItem === 'hardware' ? null : 'hardware'" 
                    type="button" 
                    class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-xl shrink-0">
                        📟
                    </div>
                    <div>
                        <h3 class="text-base font-black text-[#1D1616]">Spesifikasi Hardware & Pinout</h3>
                        <p class="text-xs font-semibold text-slate-500">Daftar komponen sensor arus ACS712, RTC DS3231, dan modul relay</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'hardware' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT -->
            <div x-show="openItem === 'hardware'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-4 bg-slate-50/60">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-2">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Kontroler Utama</span>
                        <span class="font-black text-[#1D1616] text-sm block mt-0.5">Raspberry Pi 3 Model B+</span>
                        <p class="text-[11px] text-slate-500 mt-1">Quad Core 1.4GHz Broadcom BCM2837B0, 1GB LPDDR2 SDRAM.</p>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Sensor Arus Listrik</span>
                        <span class="font-black text-[#1D1616] text-sm block mt-0.5">Allegro ACS712 30A Hall-Effect</span>
                        <p class="text-[11px] text-slate-500 mt-1">Sensitivitas 66 mV/A, pembacaan ADC ADS1115 I2C 16-Bit presisi tinggi.</p>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Hardware Clock (RTC)</span>
                        <span class="font-black text-[#1D1616] text-sm block mt-0.5">Maxim DS3231 High-Precision RTC</span>
                        <p class="text-[11px] text-slate-500 mt-1">Baterai CR2032 terintegrasi untuk menjamin akurasi jadwal saat offline.</p>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Modul Saklar Relai</span>
                        <span class="font-black text-[#1D1616] text-sm block mt-0.5">Dual-Channel 5V Relay Optocoupler</span>
                        <p class="text-[11px] text-slate-500 mt-1">GPIO 17 (Relay AC 1 / Lampu Bawah), GPIO 27 (Relay AC 2 / Lampu Atas).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ================= MODAL UBAH PASSWORD ================= -->
    <div x-show="modalPassword" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="modalPassword = false" 
             class="bg-white rounded-[40px] p-7 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 space-y-5 relative">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-[20px] bg-[#8E1616]/10 text-[#8E1616] flex items-center justify-center font-black text-xl">
                        🔒
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-[#1D1616]">Ubah Kata Sandi Akun</h4>
                        <p class="text-xs text-slate-500">Perbarui kata sandi login operator</p>
                    </div>
                </div>
                <button @click="modalPassword = false" class="text-slate-400 hover:text-[#8E1616] text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Kata Sandi Saat Ini *</label>
                    <input type="password" name="current_password" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Kata Sandi Baru *</label>
                    <input type="password" name="new_password" required minlength="6" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Konfirmasi Kata Sandi Baru *</label>
                    <input type="password" name="new_password_confirmation" required minlength="6" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button @click="modalPassword = false" type="button" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#8E1616] to-[#1D1616] text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
