<!-- SECTION 4: PUSAT INFORMASI AKUN & SISTEM IOT (PALETTE: #1D1616, #8E1616, #D84040, #EEEEEE) -->
<div class="space-y-4 w-full pb-24" x-data="{ openItem: null }">
    
    <!-- HEADER -->
    <div class="border-b border-[#8E1616]/20 pb-4">
        <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#8E1616] block">PUSAT INFORMASI</span>
        <h2 class="text-2xl sm:text-3xl font-black text-[#1D1616] tracking-tight mt-0.5">
            Informasi & System Hub
        </h2>
        <p class="text-xs font-semibold text-slate-500 mt-1">
            Klik menu di bawah ini untuk melihat detail profil operator, spesifikasi sistem, hardware, dan koneksi.
        </p>
    </div>

    <!-- CLEAN LIST ITEM 1: INFORMASI AKUN -->
    <div class="bg-white rounded-[28px] border border-[#8E1616]/20 shadow-[0_10px_30px_-10px_rgba(29,22,22,0.05)] overflow-hidden transition-all duration-300">
        <button 
            @click="openItem = openItem === 'akun' ? null : 'akun'" 
            type="button" 
            class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-xl shrink-0">
                    👤
                </div>
                <div>
                    <h3 class="text-base font-black text-[#1D1616]">Informasi Akun</h3>
                    <p class="text-xs font-semibold text-slate-500">Informasi pengguna yang sedang menggunakan sistem</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                 :class="openItem === 'akun' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                ➔
            </div>
        </button>

        <!-- ACCORDION CONTENT -->
        <div x-show="openItem === 'akun'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-4 bg-[#EEEEEE]/40">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-2">
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Nama Pengguna</span>
                    <span class="font-black text-[#1D1616] text-sm block mt-0.5">{{ session('user_name', 'Dicky Akbar Syahputra') }}</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">ID / NIP Operator</span>
                    <span class="font-mono font-black text-[#D84040] text-sm block mt-0.5">{{ session('user_nip', 'PINDAD-IOT-2026') }}</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Divisi</span>
                    <span class="font-bold text-[#1D1616] block mt-0.5">{{ session('user_division', 'Divisi Sistem Informasi & Fasilitas Gedung') }}</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Posisi / Peran</span>
                    <span class="font-bold text-[#1D1616] block mt-0.5">{{ session('user_role', 'Operator Monitoring & Kontrol AC') }}</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15 text-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Waktu Sesi Login</span>
                    <span class="font-black text-[#1D1616] font-mono">{{ session('login_time', now()->format('d M Y, H:i:s WIB')) }}</span>
                </div>

                <!-- FORM LOGOUT -->
                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sesi operator?')">
                    @csrf
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-2xl bg-[#D84040] hover:bg-[#8E1616] text-white text-xs font-black uppercase tracking-wider shadow-md shadow-[#D84040]/30 transition cursor-pointer flex items-center space-x-2">
                        <span>🚪</span>
                        <span>Keluar / Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- CLEAN LIST ITEM 2: INFORMASI SISTEM -->
    <div class="bg-white rounded-[28px] border border-[#8E1616]/20 shadow-[0_10px_30px_-10px_rgba(29,22,22,0.05)] overflow-hidden transition-all duration-300">
        <button 
            @click="openItem = openItem === 'sistem' ? null : 'sistem'" 
            type="button" 
            class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-xl shrink-0">
                    🖥️
                </div>
                <div>
                    <h3 class="text-base font-black text-[#1D1616]">Informasi Sistem</h3>
                    <p class="text-xs font-semibold text-slate-500">Informasi singkat mengenai website dan aplikasi dashboard</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                 :class="openItem === 'sistem' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                ➔
            </div>
        </button>

        <!-- ACCORDION CONTENT -->
        <div x-show="openItem === 'sistem'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-4 bg-[#EEEEEE]/40">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-xs pt-2">
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Nama Sistem</span>
                    <span class="font-black text-[#1D1616] block mt-0.5">Sistem Kontrol & Monitoring AC IoT</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Versi Sistem</span>
                    <span class="font-mono font-black text-[#D84040] block mt-0.5">v1.0.0 (Production Build)</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Tahun Pengembangan</span>
                    <span class="font-black text-[#1D1616] block mt-0.5">2026</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Mode Kontrol</span>
                    <span class="font-bold text-[#1D1616] block mt-0.5">Manual (MQTT) & Otomatis (RTC)</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Jumlah Unit AC</span>
                    <span class="font-bold text-[#1D1616] block mt-0.5">2 Unit (Panasonic 1 & 2)</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Lokasi Deployment</span>
                    <span class="font-bold text-[#1D1616] block mt-0.5">Ruang Server 1 — PT PINDAD</span>
                </div>
            </div>
        </div>
    </div>

    <!-- CLEAN LIST ITEM 3: INFORMASI PERANGKAT -->
    <div class="bg-white rounded-[28px] border border-[#8E1616]/20 shadow-[0_10px_30px_-10px_rgba(29,22,22,0.05)] overflow-hidden transition-all duration-300">
        <button 
            @click="openItem = openItem === 'hardware' ? null : 'hardware'" 
            type="button" 
            class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-xl shrink-0">
                    🔧
                </div>
                <div>
                    <h3 class="text-base font-black text-[#1D1616]">Informasi Perangkat</h3>
                    <p class="text-xs font-semibold text-slate-500">Informasi modul dan hardware yang digunakan pada sistem</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                 :class="openItem === 'hardware' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                ➔
            </div>
        </button>

        <!-- ACCORDION CONTENT -->
        <div x-show="openItem === 'hardware'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-3 bg-[#EEEEEE]/40">
            <div class="space-y-2.5 pt-2">
                
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15 flex items-start space-x-3">
                    <span class="font-mono text-xs font-black text-white bg-[#1D1616] px-2.5 py-1 rounded-lg shrink-0 mt-0.5">ESP32</span>
                    <div>
                        <h4 class="text-xs font-black text-[#1D1616]">Mikrokontroler ESP32</h4>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Controller utama untuk mengatur ON/OFF relay AC dan menangani komunikasi pesan MQTT.</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15 flex items-start space-x-3">
                    <span class="font-mono text-xs font-black text-white bg-[#8E1616] px-2.5 py-1 rounded-lg shrink-0 mt-0.5">ACS712</span>
                    <div>
                        <h4 class="text-xs font-black text-[#1D1616]">Sensor Arus ACS712</h4>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Sensor presisi untuk membaca beban konsumsi arus listrik AC (Ampere) secara real-time.</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15 flex items-start space-x-3">
                    <span class="font-mono text-xs font-black text-white bg-[#D84040] px-2.5 py-1 rounded-lg shrink-0 mt-0.5">RTC DS3231</span>
                    <div>
                        <h4 class="text-xs font-black text-[#1D1616]">Modul Waktu Real-Time DS3231</h4>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Modul pewaktu berpresisi tinggi untuk menjalankan rotasi jadwal otomatis 12 jam.</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15 flex items-start space-x-3">
                    <span class="font-mono text-xs font-black text-white bg-slate-700 px-2.5 py-1 rounded-lg shrink-0 mt-0.5">Relay 2CH</span>
                    <div>
                        <h4 class="text-xs font-black text-[#1D1616]">Relay Module 2-Channel</h4>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Saklar optocoupler elektronik pembuka & penutup arus daya beban AC 1 dan AC 2.</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15 flex items-start space-x-3">
                    <span class="font-mono text-xs font-black text-white bg-emerald-700 px-2.5 py-1 rounded-lg shrink-0 mt-0.5">Unit AC</span>
                    <div>
                        <h4 class="text-xs font-black text-[#1D1616]">2 Unit AC Panasonic Terhubung</h4>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Panasonic 1 (Pin GPIO 18) & Panasonic 2 (Pin GPIO 19) di Ruang Server 1.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- CLEAN LIST ITEM 4: STATUS KONEKSI -->
    <div class="bg-white rounded-[28px] border border-[#8E1616]/20 shadow-[0_10px_30px_-10px_rgba(29,22,22,0.05)] overflow-hidden transition-all duration-300">
        <button 
            @click="openItem = openItem === 'koneksi' ? null : 'koneksi'" 
            type="button" 
            class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-xl shrink-0">
                    📡
                </div>
                <div>
                    <h3 class="text-base font-black text-[#1D1616]">Status Koneksi</h3>
                    <p class="text-xs font-semibold text-slate-500">Informasi status jaringan dan telemetry broker MQTT</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                 :class="openItem === 'koneksi' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                ➔
            </div>
        </button>

        <!-- ACCORDION CONTENT -->
        <div x-show="openItem === 'koneksi'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-4 bg-[#EEEEEE]/40">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-2">
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Status MQTT</span>
                        <span class="font-mono text-xs font-black text-[#1D1616] block mt-0.5">broker.emqx.io:1883</span>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px]">🟢 Connected</span>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">ESP32 AC 1</span>
                        <span class="font-bold text-[#1D1616] text-xs block mt-0.5">GPIO Pin 18</span>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px]">🟢 Online</span>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">ESP32 AC 2</span>
                        <span class="font-bold text-[#1D1616] text-xs block mt-0.5">GPIO Pin 19</span>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px]">🟢 Online</span>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Interval Update</span>
                        <span class="font-black text-[#D84040] text-xs block mt-0.5">Every 3 Seconds</span>
                    </div>
                    <span class="px-3 py-1 bg-rose-100 text-[#8E1616] rounded-full font-bold text-[10px]">Real-time</span>
                </div>
            </div>
        </div>
    </div>

    <!-- CLEAN LIST ITEM 5: TENTANG SISTEM -->
    <div class="bg-white rounded-[28px] border border-[#8E1616]/20 shadow-[0_10px_30px_-10px_rgba(29,22,22,0.05)] overflow-hidden transition-all duration-300">
        <button 
            @click="openItem = openItem === 'about' ? null : 'about'" 
            type="button" 
            class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-xl shrink-0">
                    ℹ️
                </div>
                <div>
                    <h3 class="text-base font-black text-[#1D1616]">Tentang Sistem</h3>
                    <p class="text-xs font-semibold text-slate-500">Informasi umum mengenai latar belakang dan tujuan project</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                 :class="openItem === 'about' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                ➔
            </div>
        </button>

        <!-- ACCORDION CONTENT -->
        <div x-show="openItem === 'about'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-3 bg-[#EEEEEE]/40">
            <div class="space-y-3 pt-2 text-xs">
                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15 space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Deskripsi Sistem</span>
                    <p class="text-slate-700 font-semibold leading-relaxed">
                        Sistem Kontrol & Monitoring AC IoT merupakan platform pemantauan dan pengendalian pendingin udara (AC) berbasis mikrokontroler di PT PINDAD (Persero) untuk menjaga stabilitas suhu Ruang Server 1.
                    </p>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15 space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Tujuan Utama Project</span>
                    <p class="text-slate-700 font-semibold leading-relaxed">
                        • Mencegah overheating pada peladen (server) melalui rotasi pendinginan otomatis 12 jam.<br>
                        • Memantau beban arus listrik (Ampere) secara real-time untuk mencegah beban berlebih.<br>
                        • Memberikan kontrol saklar manual cepat bagi operator gedung secara terpusat.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Pengembang</span>
                        <span class="font-black text-[#1D1616] block mt-0.5">Tim Project Magang IoT PINDAD</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-[#8E1616]/15">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Tahun Pengembangan</span>
                        <span class="font-black text-[#1D1616] block mt-0.5">2026</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
         </button>
        </div>

    </div>

</div>

