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
