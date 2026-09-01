<!-- ================= MODUL 4: PUSAT INFORMASI AKUN & SISTEM IOT ================= -->
<div class="space-y-6 pb-20" x-data="{ 
    openItem: null,
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
                            Buka menu <b>DevZone</b> untuk membuat atau memilih blueprint hardware (misal <i>Dual AC Controller (Raspberry Pi 3B+)</i>). Konfigurasikan saluran <b>Virtual Pin</b> seperti <code class="bg-slate-100 px-1 py-0.5 rounded font-bold text-[#8E1616]">V0/V1</code> untuk Saklar Relay, dan <code class="bg-slate-100 px-1 py-0.5 rounded font-bold text-[#8E1616]">V2/V3</code> untuk Sensor Arus ACS712.
                        </p>
                    </div>

                    <!-- Step 2: Tambah Device -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2 relative overflow-hidden">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-[#8E1616] text-white flex items-center justify-center font-black text-xs shrink-0">2</span>
                            <h4 class="font-black text-sm text-[#1D1616]">Daftarkan Ruangan Baru di Home (Modul 1)</h4>
                        </div>
                        <p class="text-slate-600 leading-relaxed">
                            Di halaman <b>Home</b>, klik tombol <b>`+ Tambah Perangkat Baru`</b>. Tentukan Nama Ruangan (misal <i>Monitoring AC Ruang Server 2</i>) dan tentukan jumlah <b>Kapasitas AC</b> (misal <i>2, 4, 6, atau 8 AC</i>).
                        </p>
                    </div>

                    <!-- Step 3: Unduh Skrip Otomatis -->
                    <div class="bg-white p-5 rounded-2xl border-2 border-emerald-400/80 shadow-xs space-y-2 relative overflow-hidden bg-emerald-50/20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-xs shrink-0">3</span>
                                <h4 class="font-black text-sm text-[#1D1616]">Unduh Skrip Python (.py) Siap Pakai</h4>
                            </div>
                            <span class="text-[9px] font-black uppercase bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">100% Otomatis</span>
                        </div>
                        <p class="text-slate-600 leading-relaxed">
                            Buka detail ruangan tersebut, lalu klik tombol <b>`📥 Unduh Skrip (.py)`</b>. Web langsung men-generate 1 file Python mandiri (misal <code class="bg-slate-100 px-1 py-0.5 rounded font-mono font-bold text-emerald-800">pindad_node_ruang_server_2.py</code>) yang <b>sudah otomatis terkonfigurasi dengan ID Ruangan, channel relay, dan pin sensor ACS712</b>. <i>Tanpa perlu file json sama sekali!</i>
                        </p>
                    </div>

                    <!-- Step 4: Jalankan di Raspberry Pi -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2 relative overflow-hidden">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-[#8E1616] text-white flex items-center justify-center font-black text-xs shrink-0">4</span>
                            <h4 class="font-black text-sm text-[#1D1616]">Jalankan Skrip di Raspberry Pi via SSH</h4>
                        </div>
                        <p class="text-slate-600 leading-relaxed">
                            Salin 1 file <code class="bg-slate-100 px-1 py-0.5 rounded font-mono text-[11px]">.py</code> hasil unduhan tadi ke Raspberry Pi (via SSH/WinSCP), lalu jalankan:
                            <code class="block mt-1 bg-[#1D1616] text-emerald-400 p-2 rounded-lg font-mono text-[11px]">python3 pindad_node_ruang_server_2.py</code>
                            Perangkat langsung terhubung ke MQTT dan lampu status di web menjadi <b>🟢 Online</b>!
                        </p>
                    </div>

                </div>

                <!-- Hardware Pinout Mapping Guide (2-Channel s/d 8-Channel Relay & ACS712) -->
                <div class="bg-white p-5 rounded-2xl border-2 border-indigo-200 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black text-base shrink-0">
                                ⚡
                            </div>
                            <div>
                                <h4 class="font-black text-sm text-[#1D1616]">Alokasi Hardware Otomatis (Relay & Sensor Arus ACS712)</h4>
                                <p class="text-xs text-slate-500">Skrip yang diunduh dari web sudah otomatis memetakan pin GPIO dan kanal ADC ADS1115:</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-lg border border-indigo-200">
                            1 s/d 8 Channel AC
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2.5 text-[11px]">
                        <!-- Channel 1-2 -->
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-1">
                            <span class="text-indigo-700 font-bold font-sans block text-xs">❄️ AC 1 & AC 2 (2 Channel):</span>
                            <div class="text-[10.5px] text-slate-600 font-mono space-y-0.5">
                                <p>• AC 1: GPIO 17 | ADS1115 #1 (A0)</p>
                                <p>• AC 2: GPIO 27 | ADS1115 #1 (A1)</p>
                            </div>
                        </div>

                        <!-- Channel 3-4 -->
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-1">
                            <span class="text-indigo-700 font-bold font-sans block text-xs">❄️ AC 3 & AC 4 (4 Channel):</span>
                            <div class="text-[10.5px] text-slate-600 font-mono space-y-0.5">
                                <p>• AC 3: GPIO 22 | ADS1115 #1 (A2)</p>
                                <p>• AC 4: GPIO 23 | ADS1115 #1 (A3)</p>
                            </div>
                        </div>

                        <!-- Channel 5-6 -->
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-1">
                            <span class="text-indigo-700 font-bold font-sans block text-xs">❄️ AC 5 & AC 6 (6 Channel):</span>
                            <div class="text-[10.5px] text-slate-600 font-mono space-y-0.5">
                                <p>• AC 5: GPIO 24 | ADS1115 #2 (A0)</p>
                                <p>• AC 6: GPIO 25 | ADS1115 #2 (A1)</p>
                            </div>
                        </div>

                        <!-- Channel 7-8 -->
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-1">
                            <span class="text-indigo-700 font-bold font-sans block text-xs">❄️ AC 7 & AC 8 (8 Channel):</span>
                            <div class="text-[10.5px] text-slate-600 font-mono space-y-0.5">
                                <p>• AC 7: GPIO 5 | ADS1115 #2 (A2)</p>
                                <p>• AC 8: GPIO 6 | ADS1115 #2 (A3)</p>
                            </div>
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
                                <p class="text-xs text-slate-500">Jalankan 2 perintah ini di terminal Raspberry Pi OS saat pertama kali menginstal alat:</p>
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
                                <span class="font-bold text-emerald-400 font-sans">1. Aktifkan Driver Hardware I2C & Install Dependensi:</span>
                                <span class="text-slate-400 font-mono text-[10px]">Linux Shell</span>
                            </div>
                            <code class="block font-mono text-slate-100 text-[11px] select-all bg-black/40 p-2 rounded-lg">
                                sudo apt update && sudo apt install -y python3-pip python3-smbus i2c-tools && sudo raspi-config nonint do_i2c 0
                            </code>
                        </div>

                        <!-- Step B -->
                        <div class="bg-slate-900 text-slate-200 p-3 rounded-xl border border-slate-800 space-y-1">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-amber-400 font-sans">2. Install Library Sensor (MQTT, ADS1115 ADC, DS3231 RTC):</span>
                                <span class="text-slate-400 font-mono text-[10px]">Python Pip</span>
                            </div>
                            <code class="block font-mono text-slate-100 text-[11px] select-all bg-black/40 p-2 rounded-lg">
                                pip3 install paho-mqtt adafruit-circuitpython-ads1x15 adafruit-circuitpython-ds3231 RPi.GPIO --break-system-packages
                            </code>
                        </div>
                    </div>
                </div>

                <!-- One-Command Interactive Wizard Spotlight -->
                <div class="bg-gradient-to-r from-[#1D1616] to-[#8E1616] text-white p-5 rounded-2xl border border-rose-400/30 shadow-md space-y-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-white/20 text-white flex items-center justify-center font-black text-lg shrink-0">⚡</span>
                        <div>
                            <h4 class="font-black text-sm text-white">Fitur 1-Perintah Auto Setup Wizard (Paling Cepat & Anti-Ribet)</h4>
                            <p class="text-xs text-rose-100/80">User cukup menjalankan 1 perintah ini, lalu terminal akan memandu mengisi pin GPIO relay secara interaktif:</p>
                        </div>
                    </div>

                    <div class="bg-black/50 p-3 rounded-xl border border-white/10 space-y-1.5">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-emerald-400 font-bold font-mono">1 Baris Perintah Terminal Raspberry Pi:</span>
                            <span class="text-slate-400 font-mono text-[10px]">Auto-Wizard</span>
                        </div>
                        <code class="block font-mono text-[11.5px] text-emerald-300 p-2 rounded-lg bg-black/60 select-all overflow-x-auto">
                            cd Sistem_Monitoring_AC/scripts && bash setup_raspberry_pi.sh
                        </code>
                        <p class="text-[10.5px] text-slate-300 pt-1">
                            👉 Terminal akan bertanya secara ramah: <i>"Berapa jumlah AC? (2/4)"</i> &rarr; <i>"Pin GPIO AC 1? (17)"</i> &rarr; <i>"Pin GPIO AC 2? (27)"</i> &rarr; <b>Selesai!</b> File config langsung dibuat & Auto-start langsung aktif tanpa perlu buka nano sama sekali.
                        </p>
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
                            <p class="text-xs text-slate-500">Pilihan metode deployment IoT yang paling praktis:</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                        <!-- Option 1: Direct Download Standalone .py -->
                        <div class="p-4 rounded-xl bg-emerald-50/50 border-2 border-emerald-300 space-y-2 flex flex-col justify-between">
                            <div class="space-y-1">
                                <span class="font-black text-xs text-[#1D1616] flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px]">1</span>
                                        <span>Cara 1: Unduh Skrip (.py)</span>
                                    </span>
                                    <span class="text-[9px] font-black uppercase bg-emerald-200 text-emerald-900 px-1.5 py-0.5 rounded">Terbaik</span>
                                </span>
                                <p class="text-[11px] text-slate-600 leading-relaxed">Unduh 1 file Python mandiri dari tombol web, lalu jalankan:</p>
                            </div>
                            <code class="block font-mono text-[10.5px] bg-slate-900 text-emerald-400 p-2.5 rounded-lg select-all overflow-x-auto leading-tight">
                                python3 pindad_node_ruang_server.py
                            </code>
                        </div>

                        <!-- Option 2: CLI Parameter -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2 flex flex-col justify-between">
                            <div class="space-y-1">
                                <span class="font-black text-xs text-[#1D1616] flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px]">2</span>
                                    <span>Cara 2: Lewat Terminal CLI</span>
                                </span>
                                <p class="text-[11px] text-slate-600 leading-relaxed">Jalankan universal node dengan argumen ID Perangkat:</p>
                            </div>
                            <code class="block font-mono text-[10.5px] bg-slate-900 text-blue-300 p-2.5 rounded-lg select-all overflow-x-auto leading-tight">
                                python3 pindad_universal_node.py RPI3B_RUANG_SERVER_2
                            </code>
                        </div>

                        <!-- Option 3: Git Clone -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2 flex flex-col justify-between">
                            <div class="space-y-1">
                                <span class="font-black text-xs text-[#1D1616] flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-full bg-purple-600 text-white flex items-center justify-center text-[10px]">3</span>
                                    <span>Cara 3: Git Clone Repository</span>
                                </span>
                                <p class="text-[11px] text-slate-600 leading-relaxed">Clone seluruh repo untuk pengembangan/backup:</p>
                            </div>
                            <code class="block font-mono text-[10.5px] bg-slate-900 text-purple-300 p-2.5 rounded-lg select-all overflow-x-auto leading-tight">
                                git clone https://github.com/W2LS/Sistem_Monitoring_AC.git
                            </code>
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
