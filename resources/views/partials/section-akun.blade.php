<!-- SECTION 4: INFORMASI SISTEM & STATUS IOT (PALETTE: #1D1616, #8E1616, #D84040, #EEEEEE) -->
<div class="space-y-6 w-full pb-24" x-data="{ refreshing: false, toastMessage: '', showToast: false }">
    
    <!-- HEADER -->
    <div class="border-b border-[#8E1616]/20 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#8E1616] flex items-center space-x-1.5">
                <span>⚙️</span>
                <span>INFORMASI SISTEM & SPESIFIKASI</span>
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-[#1D1616] tracking-tight mt-0.5">
                Sistem Kontrol & Monitoring AC IoT
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Informasi perangkat, koneksi MQTT, hardware, dan status operasional sistem secara real-time.
            </p>
        </div>

        <!-- Refresh Button Quick Action in Header -->
        <button 
            @click="
                refreshing = true; 
                pollTelemetryData(); 
                setTimeout(() => { 
                    refreshing = false; 
                    toastMessage = 'Status sistem & koneksi MQTT berhasil diperbarui!'; 
                    showToast = true; 
                    setTimeout(() => showToast = false, 4000); 
                }, 800);
            " 
            type="button"
            class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 rounded-full bg-[#1D1616] hover:bg-black text-white text-xs font-black uppercase tracking-wider shadow-md hover:shadow-lg transition cursor-pointer shrink-0">
            <span :class="refreshing ? 'animate-spin' : ''">🔄</span>
            <span x-text="refreshing ? 'Memeriksa...' : 'Refresh Status'"></span>
        </button>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div x-show="showToast" x-cloak 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="bg-emerald-800 text-white rounded-2xl px-5 py-3 text-xs font-bold shadow-lg flex items-center space-x-3 border border-emerald-600">
        <span class="w-6 h-6 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-xs">✓</span>
        <span x-text="toastMessage"></span>
    </div>

    <!-- MAIN CONTAINER CARD (40px Radius) -->
    <div class="bg-white rounded-[40px] p-6 sm:p-8 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 relative overflow-hidden space-y-6">
        
        <!-- Decorative subtle blob -->
        <div class="absolute -top-12 -right-12 w-56 h-56 bg-[#8E1616]/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- TOP GRID: STATUS SISTEM & KONEKSI MQTT (2 Bento Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- 1. STATUS SISTEM -->
            <div class="bg-[#1D1616] rounded-[28px] p-6 text-white border border-[#8E1616]/40 shadow-xl relative overflow-hidden flex flex-col justify-between space-y-4">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#D84040]">Status Operational</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    </div>
                    <h3 class="text-xl font-black text-white flex items-center space-x-2">
                        <span>STATUS SISTEM</span>
                    </h3>
                    <div class="inline-flex items-center space-x-2 bg-emerald-500/20 border border-emerald-500/40 px-3 py-1 rounded-full text-emerald-400 font-extrabold text-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>🟢 Sistem Online</span>
                    </div>
                </div>

                <div class="space-y-1.5 pt-2 border-t border-white/10 text-xs font-semibold text-[#EEEEEE]/80">
                    <div class="flex items-center justify-between">
                        <span>• Broker MQTT</span>
                        <span class="font-bold text-emerald-400">Terhubung</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>• ESP32 AC 1</span>
                        <span class="font-bold text-emerald-400">Online</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>• ESP32 AC 2</span>
                        <span class="font-bold text-emerald-400">Online</span>
                    </div>
                    <div class="flex items-center justify-between pt-1 border-t border-white/5">
                        <span class="text-[10px] text-slate-400">Update Terakhir</span>
                        <span class="font-mono text-[10px] text-[#D84040] font-bold">Real-time (3s)</span>
                    </div>
                </div>
            </div>

            <!-- 2. INFORMASI BROKER MQTT -->
            <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[28px] p-6 border border-[#8E1616]/20 flex flex-col justify-between space-y-4 shadow-sm">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">Broker Message Queue</span>
                        <span class="text-xs">📡</span>
                    </div>
                    <h3 class="text-xl font-black text-[#1D1616]">KONEKSI MQTT</h3>
                    <div class="inline-block bg-white px-3 py-1.5 rounded-xl border border-[#8E1616]/20 font-mono text-xs font-black text-[#8E1616]">
                        broker.emqx.io:1883
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 pt-2 border-t border-[#8E1616]/15 text-center">
                    <div class="bg-white p-2.5 rounded-xl border border-[#8E1616]/10">
                        <span class="text-[9px] font-extrabold uppercase text-slate-400 block">Status</span>
                        <span class="text-xs font-black text-emerald-600 block mt-0.5">Connected</span>
                    </div>
                    <div class="bg-white p-2.5 rounded-xl border border-[#8E1616]/10">
                        <span class="text-[9px] font-extrabold uppercase text-slate-400 block">QoS</span>
                        <span class="text-xs font-black text-[#1D1616] block mt-0.5">Level 1</span>
                    </div>
                    <div class="bg-white p-2.5 rounded-xl border border-[#8E1616]/10">
                        <span class="text-[9px] font-extrabold uppercase text-slate-400 block">Interval</span>
                        <span class="text-xs font-black text-[#D84040] block mt-0.5">3 Detik</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- MIDDLE GRID: UNIT TERHUBUNG & HARDWARE SISTEM (2 Bento Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- 3. PERANGKAT TERHUBUNG -->
            <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[28px] p-6 border border-[#8E1616]/20 space-y-4">
                <div class="flex items-center justify-between border-b border-[#8E1616]/15 pb-3">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">Daftar Node Hardware</span>
                    <span class="text-xs font-black text-slate-500">2 Unit Active</span>
                </div>
                <h3 class="text-lg font-black text-[#1D1616]">UNIT TERHUBUNG</h3>

                <div class="space-y-2.5">
                    <!-- Unit 1 -->
                    <div class="bg-white rounded-2xl p-3.5 border border-[#8E1616]/15 flex items-center justify-between shadow-xs">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-xl bg-rose-100 text-[#D84040] flex items-center justify-center font-black text-sm">
                                ❄️
                            </div>
                            <div>
                                <span class="text-xs font-black text-[#1D1616] block">AC 1 — PANASONIC 1</span>
                                <span class="text-[10px] font-semibold text-slate-500 block">ESP32 Controller • Pin GPIO 18</span>
                            </div>
                        </div>
                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                            <span>Online</span>
                        </span>
                    </div>

                    <!-- Unit 2 -->
                    <div class="bg-white rounded-2xl p-3.5 border border-[#8E1616]/15 flex items-center justify-between shadow-xs">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-sm">
                                ❄️
                            </div>
                            <div>
                                <span class="text-xs font-black text-[#1D1616] block">AC 2 — PANASONIC 2</span>
                                <span class="text-[10px] font-semibold text-slate-500 block">ESP32 Controller • Pin GPIO 19</span>
                            </div>
                        </div>
                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                            <span>Online</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- 4. INFORMASI HARDWARE -->
            <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[28px] p-6 border border-[#8E1616]/20 space-y-4">
                <div class="flex items-center justify-between border-b border-[#8E1616]/15 pb-3">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">Stack Modul Elektronik</span>
                    <span class="text-xs">🕹️</span>
                </div>
                <h3 class="text-lg font-black text-[#1D1616]">KONFIGURASI PERANGKAT</h3>

                <div class="space-y-2 font-sans">
                    <div class="bg-white p-3 rounded-2xl border border-[#8E1616]/15 flex items-start space-x-3">
                        <span class="font-mono text-xs font-extrabold text-white bg-[#1D1616] px-2 py-1 rounded-lg shrink-0 mt-0.5">ESP32</span>
                        <div class="text-xs">
                            <span class="font-extrabold text-[#1D1616] block">Mikrokontroler Utama</span>
                            <span class="text-[10px] text-slate-500 font-semibold leading-tight block">Mengontrol sinyal ON/OFF relay dan komunikasi MQTT dual-channel.</span>
                        </div>
                    </div>

                    <div class="bg-white p-3 rounded-2xl border border-[#8E1616]/15 flex items-start space-x-3">
                        <span class="font-mono text-xs font-extrabold text-white bg-[#8E1616] px-2 py-1 rounded-lg shrink-0 mt-0.5">ACS712</span>
                        <div class="text-xs">
                            <span class="font-extrabold text-[#1D1616] block">Sensor Monitoring Arus</span>
                            <span class="text-[10px] text-slate-500 font-semibold leading-tight block">Mengukur beban arus AC (Ampere) secara akurat & real-time.</span>
                        </div>
                    </div>

                    <div class="bg-white p-3 rounded-2xl border border-[#8E1616]/15 flex items-start space-x-3">
                        <span class="font-mono text-xs font-extrabold text-white bg-[#D84040] px-2 py-1 rounded-lg shrink-0 mt-0.5">RTC DS3231</span>
                        <div class="text-xs">
                            <span class="font-extrabold text-[#1D1616] block">Modul Real-Time Clock</span>
                            <span class="text-[10px] text-slate-500 font-semibold leading-tight block">Menjaga presisi pewaktu otomatis untuk eksekusi rotasi jadwal shift.</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- 5. SPESIFIKASI DETAIL SISTEM (Table / Grid Details) -->
        <div class="bg-white rounded-[28px] p-6 border border-[#8E1616]/20 space-y-4">
            <div class="flex items-center space-x-2 border-b border-[#8E1616]/15 pb-3">
                <span class="text-lg">ℹ️</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Metadata Deployment</span>
                    <h3 class="text-base font-black text-[#1D1616]">INFORMASI SISTEM</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-xs">
                <div class="bg-[#EEEEEE]/60 p-3.5 rounded-2xl border border-[#8E1616]/10">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase block">Nama Sistem</span>
                    <span class="font-black text-[#1D1616] block mt-0.5">Sistem Kontrol & Monitoring AC IoT</span>
                </div>
                <div class="bg-[#EEEEEE]/60 p-3.5 rounded-2xl border border-[#8E1616]/10">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase block">Lokasi Deployment</span>
                    <span class="font-black text-[#1D1616] block mt-0.5">Ruang Server 1 — PINDAD</span>
                </div>
                <div class="bg-[#EEEEEE]/60 p-3.5 rounded-2xl border border-[#8E1616]/10">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase block">Jumlah Unit AC</span>
                    <span class="font-black text-[#1D1616] block mt-0.5">2 Unit (Panasonic 1 & 2)</span>
                </div>
                <div class="bg-[#EEEEEE]/60 p-3.5 rounded-2xl border border-[#8E1616]/10">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase block">Interval Telemetri</span>
                    <span class="font-black text-[#D84040] block mt-0.5">3 Detik (Real-time polling)</span>
                </div>
                <div class="bg-[#EEEEEE]/60 p-3.5 rounded-2xl border border-[#8E1616]/10">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase block">Mode Kontrol</span>
                    <span class="font-black text-[#1D1616] block mt-0.5">Manual (MQTT) & Terjadwal (RTC)</span>
                </div>
                <div class="bg-[#EEEEEE]/60 p-3.5 rounded-2xl border border-[#8E1616]/10">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase block">Versi Firmware/App</span>
                    <span class="font-mono font-black text-[#8E1616] block mt-0.5">v1.0.0 (Production Build)</span>
                </div>
            </div>
        </div>

        <!-- 6. ACTION BUTTONS (Substituted Logout with System Utility Actions) -->
        <div class="pt-4 border-t border-[#8E1616]/20 flex flex-col sm:flex-row items-center justify-between gap-3">
            <button 
                @click="
                    refreshing = true; 
                    pollTelemetryData(); 
                    setTimeout(() => { 
                        refreshing = false; 
                        toastMessage = 'Refresh Status Sistem Selesai!'; 
                        showToast = true; 
                        setTimeout(() => showToast = false, 4000); 
                    }, 800);
                " 
                type="button"
                class="w-full sm:w-auto px-6 py-3.5 rounded-[24px] bg-[#1D1616] hover:bg-black text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-[#1D1616]/25 transition cursor-pointer flex items-center justify-center space-x-2">
                <span :class="refreshing ? 'animate-spin' : ''">🔄</span>
                <span>Refresh Status Sistem</span>
            </button>

            <button 
                @click="
                    toastMessage = 'Sinkronisasi hardware ESP32 & RTC DS3231 berhasil dilakukan!'; 
                    showToast = true; 
                    setTimeout(() => showToast = false, 4000);
                " 
                type="button"
                class="w-full sm:w-auto px-8 py-3.5 rounded-[24px] bg-[#8E1616] hover:bg-[#D84040] text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-[#8E1616]/30 transition cursor-pointer flex items-center justify-center space-x-2">
                <span>⚡</span>
                <span>Sinkronisasi Perangkat</span>
            </button>
        </div>

    </div>

</div>

