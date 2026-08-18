<!-- PANEL KONTROL & DETAIL ALAT (Presisi Sesuai Wireframe) -->
<section class="bg-slate-300/80 border border-slate-300/80 rounded-3xl p-6 shadow-sm space-y-4" x-data="{ selectedAc: 1 }">
    
    <!-- Title Section -->
    <div class="flex justify-between items-center mb-2">
        <h3 class="font-outfit font-black text-sm text-slate-800 uppercase tracking-wider flex items-center space-x-2">
            <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            <span>Daftar Kontrol Alat IoT</span>
        </h3>
        <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-widest">Pilih alat untuk melihat detail</span>
    </div>

    <!-- MAIN GRID: KIRI (Daftar Tombol Alat) & KANAN (Panel Detail Alat) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- SISI KIRI: DAFTAR TOMBOL ALAT (4/12 width) -->
        <div class="lg:col-span-4 space-y-3">
            
            <!-- Tombol AC 1 -->
            <button @click="selectedAc = 1" 
                    :class="selectedAc === 1 ? 'bg-slate-700 text-white shadow-md ring-2 ring-slate-800' : 'bg-slate-500 hover:bg-slate-600 text-white'"
                    class="w-full py-4 px-6 rounded-2xl font-outfit font-extrabold text-base tracking-wider uppercase transition-all duration-200 flex items-center justify-between group">
                <span class="flex items-center space-x-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-400"></span>
                    <span>AC 1 (Panel Bawah)</span>
                </span>
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <!-- Tombol AC 2 -->
            <button @click="selectedAc = 2" 
                    :class="selectedAc === 2 ? 'bg-slate-700 text-white shadow-md ring-2 ring-slate-800' : 'bg-slate-500 hover:bg-slate-600 text-white'"
                    class="w-full py-4 px-6 rounded-2xl font-outfit font-extrabold text-base tracking-wider uppercase transition-all duration-200 flex items-center justify-between group">
                <span class="flex items-center space-x-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                    <span>AC 2 (Panel Atas)</span>
                </span>
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <!-- Tombol AlatTambahan 1: Exhaust Fan -->
            <button onclick="alert('Exhaust Fan terhubung ke Pin Standby')" 
                    class="w-full py-3.5 px-6 bg-slate-400/80 hover:bg-slate-500 text-slate-800 font-extrabold text-sm tracking-wider uppercase rounded-2xl transition-all flex items-center justify-between">
                <span>Exhaust Fan Server</span>
                <span class="text-[10px] bg-slate-300 px-2 py-0.5 rounded-md text-slate-700">Pin 21</span>
            </button>

            <!-- Tombol AlatTambahan 2: Sensor Utama -->
            <button onclick="alert('ACS712 Sensor Arus Aktif')" 
                    class="w-full py-3.5 px-6 bg-slate-400/80 hover:bg-slate-500 text-slate-800 font-extrabold text-sm tracking-wider uppercase rounded-2xl transition-all flex items-center justify-between">
                <span>Sensor Arus ACS712</span>
                <span class="text-[10px] bg-slate-300 px-2 py-0.5 rounded-md text-slate-700">ADC 34/35</span>
            </button>

            <!-- Tombol AlatTambahan 3: Smart Plug -->
            <button onclick="alert('Power Monitoring Active')" 
                    class="w-full py-3.5 px-6 bg-slate-400/80 hover:bg-slate-500 text-slate-800 font-extrabold text-sm tracking-wider uppercase rounded-2xl transition-all flex items-center justify-between">
                <span>Main Power Gateway</span>
                <span class="text-[10px] bg-slate-300 px-2 py-0.5 rounded-md text-slate-700">Online</span>
            </button>

        </div>

        <!-- SISI KANAN: PANEL DETAIL ALAT TERPILIH (8/12 width) -->
        <div class="lg:col-span-8 bg-slate-700 rounded-3xl p-6 text-white shadow-md flex flex-col justify-between space-y-6">
            
            <!-- HEADER PANEL DETAIL (Nama AC & Switch ON/OFF) -->
            <div class="flex justify-between items-center border-b border-slate-600 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="p-3 bg-slate-600 rounded-2xl text-slate-200">
                        <!-- Gear icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-outfit font-black text-xl text-white tracking-wide" 
                            x-text="selectedAc === 1 ? 'AC PANASONIC 1 (Panel Bawah)' : 'AC PANASONIC 2 (Panel Atas)'">
                            AC PANASONIC 1
                        </h4>
                        <p class="text-xs text-slate-400 font-medium">Pin GPIO ESP32: <span class="font-bold text-teal-300" x-text="selectedAc === 1 ? 'Pin 18' : 'Pin 19'">Pin 18</span></p>
                    </div>
                </div>

                <!-- TOGGLE SWITCH ON/OFF KANAN -->
                <div class="flex items-center space-x-3 bg-slate-800/80 px-4 py-2 rounded-2xl border border-slate-600">
                    <span :id="'ac' + selectedAc + '-switch-text'" class="text-xs font-black uppercase tracking-wider text-slate-300">OFF</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <!-- Switch untuk AC 1 -->
                        <input type="checkbox" id="ac1-switch" x-show="selectedAc === 1" onchange="sendAcControlViaSwitch(1, this)" class="sr-only peer">
                        <!-- Switch untuk AC 2 -->
                        <input type="checkbox" id="ac2-switch" x-show="selectedAc === 2" onchange="sendAcControlViaSwitch(2, this)" class="sr-only peer">
                        
                        <div class="w-14 h-7 bg-slate-600 rounded-full peer peer-focus:ring-2 peer-focus:ring-teal-400 transition-all peer-checked:bg-teal-500 after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-7"></div>
                    </label>
                </div>
            </div>

            <!-- 2 SUB-CARDS: ARUS LISTRIK (PETIR KUNING ⚡) & JADWAL PENJADWALAN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- SUB-CARD 1: ARUS LISTRIK (Presisi Icon Petir Kuning Wireframe) -->
                <div class="bg-slate-300 text-slate-800 rounded-2xl p-5 shadow-inner flex flex-col justify-between">
                    <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider block">Arus Listrik (Current)</span>
                    
                    <div class="flex items-center space-x-3 my-3">
                        <!-- ICON PETIR KUNING ⚡ Sesuai Wireframe -->
                        <div class="w-12 h-12 rounded-xl bg-amber-400 text-slate-900 flex items-center justify-center shadow-sm shrink-0">
                            <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M13 2L3 14h7v8l10-12h-7z"/></svg>
                        </div>
                        <div>
                            <span x-show="selectedAc === 1" id="ac1-current" class="font-outfit font-black text-3xl text-slate-900 font-mono">3.0812</span>
                            <span x-show="selectedAc === 2" id="ac2-current" class="font-outfit font-black text-3xl text-slate-900 font-mono">0.9070</span>
                            <span class="text-sm font-bold text-slate-700">Ampere</span>
                        </div>
                    </div>

                    <div class="text-[10px] text-slate-600 font-semibold flex justify-between">
                        <span>Status: <span x-show="selectedAc === 1" id="ac1-badge-label" class="font-bold text-slate-800 uppercase">Offline</span><span x-show="selectedAc === 2" id="ac2-badge-label" class="font-bold text-slate-800 uppercase">Offline</span></span>
                        <span>Updated: <span x-show="selectedAc === 1" id="ac1-time">Live</span><span x-show="selectedAc === 2" id="ac2-time">Live</span></span>
                    </div>
                </div>

                <!-- SUB-CARD 2: JADWAL PENJADWALAN (Presisi Wireframe 08:00 - 18:00) -->
                <div class="bg-slate-300 text-slate-800 rounded-2xl p-5 shadow-inner flex flex-col justify-between">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider block">Jadwal Penjadwalan</span>
                        <button @click="activeTab = 'penjadwalan'" class="text-[10px] font-bold text-teal-700 hover:underline uppercase">+ Edit</button>
                    </div>

                    <div class="my-3">
                        <div class="font-outfit font-black text-2xl text-slate-900 font-mono tracking-tight">
                            @if(count($schedules) > 0)
                                {{ \Illuminate\Support\Carbon::parse($schedules[0]->start_time)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($schedules[0]->end_time)->format('H:i') }}
                            @else
                                08:00 - 18:00
                            @endif
                        </div>
                        <p class="text-xs text-slate-600 font-medium mt-1">
                            @if(count($schedules) > 0)
                                {{ $schedules[0]->label }} (Aktif)
                            @else
                                Schedule Otomatis Shift Kantor
                            @endif
                        </p>
                    </div>

                    <div class="text-[10px] text-slate-600 font-semibold flex justify-between">
                        <span>Mode: Penjadwalan Otomatis</span>
                        <span class="text-emerald-700 font-bold">Active</span>
                    </div>
                </div>

            </div>

        </div>

    </div>

</section>
