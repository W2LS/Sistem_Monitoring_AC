<!-- KARTU RUANGAN SERVER & STATUS PILAR -->
<section class="space-y-6">
    
    <!-- GRID 4 KARTU RUANGAN (Sesuai Wireframe) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Ruangan 1: Ruang Server Telpon -->
        <div class="bg-slate-300/80 border border-slate-300/60 rounded-3xl p-6 h-36 flex flex-col justify-between shadow-sm transition-all hover:shadow-md cursor-pointer group">
            <div class="flex justify-between items-start">
                <span class="w-3 h-3 rounded-full bg-teal-500 ring-4 ring-teal-100"></span>
                <span class="text-[10px] font-extrabold text-slate-600 bg-white/70 px-2 py-0.5 rounded-md uppercase">2 AC Aktif</span>
            </div>
            <div>
                <h3 class="font-outfit font-black text-slate-800 text-base uppercase tracking-wider group-hover:text-slate-900">
                    RUANG SERVER TELPON
                </h3>
                <p class="text-xs text-slate-600 font-medium mt-1">Status: Normal | 24°C</p>
            </div>
        </div>

        <!-- Ruangan 2: Ruang Server Komputer -->
        <div class="bg-slate-300/80 border border-slate-300/60 rounded-3xl p-6 h-36 flex flex-col justify-between shadow-sm transition-all hover:shadow-md cursor-pointer group">
            <div class="flex justify-between items-start">
                <span class="w-3 h-3 rounded-full bg-cyan-500 ring-4 ring-cyan-100"></span>
                <span class="text-[10px] font-extrabold text-slate-600 bg-white/70 px-2 py-0.5 rounded-md uppercase">1 AC Aktif</span>
            </div>
            <div>
                <h3 class="font-outfit font-black text-slate-800 text-base uppercase tracking-wider group-hover:text-slate-900">
                    RUANG SERVER KOMPUTER
                </h3>
                <p class="text-xs text-slate-600 font-medium mt-1">Status: Normal | 23°C</p>
            </div>
        </div>

        <!-- Ruangan 3: Ruang Server IoT -->
        <div class="bg-slate-300/80 border border-slate-300/60 rounded-3xl p-6 h-36 flex flex-col justify-between shadow-sm transition-all hover:shadow-md cursor-pointer group">
            <div class="flex justify-between items-start">
                <span class="w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-emerald-100"></span>
                <span class="text-[10px] font-extrabold text-slate-600 bg-white/70 px-2 py-0.5 rounded-md uppercase">2 AC Aktif</span>
            </div>
            <div>
                <h3 class="font-outfit font-black text-slate-800 text-base uppercase tracking-wider group-hover:text-slate-900">
                    RUANG SERVER IOT
                </h3>
                <p class="text-xs text-slate-600 font-medium mt-1">Status: Monitoring | 24°C</p>
            </div>
        </div>

        <!-- Ruangan 4: Pindad Room 1 -->
        <div class="bg-slate-300/80 border border-slate-300/60 rounded-3xl p-6 h-36 flex flex-col justify-between shadow-sm transition-all hover:shadow-md cursor-pointer group">
            <div class="flex justify-between items-start">
                <span class="w-3 h-3 rounded-full bg-indigo-500 ring-4 ring-indigo-100"></span>
                <span class="text-[10px] font-extrabold text-slate-600 bg-white/70 px-2 py-0.5 rounded-md uppercase">Demo Room</span>
            </div>
            <div>
                <h3 class="font-outfit font-black text-slate-800 text-base uppercase tracking-wider group-hover:text-slate-900">
                    PINDAD ROOM 1
                </h3>
                <p class="text-xs text-slate-600 font-medium mt-1">ESP32 Device | Online</p>
            </div>
        </div>

    </div>

    <!-- MIDDLE STATUS PILLS (Penyesuaian untuk area 'bingung di isi apa' pada wireframe) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-slate-300/70 border border-slate-300/50 rounded-2xl py-2.5 px-3 text-center shadow-2xs">
            <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider block">Daya Listrik</span>
            <span id="total-current-pill" class="text-xs font-black text-slate-800 font-mono mt-0.5 block">⚡ 3.988 A</span>
        </div>

        <div class="bg-slate-300/70 border border-slate-300/50 rounded-2xl py-2.5 px-3 text-center shadow-2xs">
            <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider block">Suhu Ruangan</span>
            <span class="text-xs font-black text-slate-800 mt-0.5 block">🌡️ 24°C Normal</span>
        </div>

        <div class="bg-slate-300/70 border border-slate-300/50 rounded-2xl py-2.5 px-3 text-center shadow-2xs">
            <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider block">Status AC</span>
            <span class="text-xs font-black text-teal-700 mt-0.5 block">🟢 2 Unit Standby</span>
        </div>

        <div class="bg-slate-300/70 border border-slate-300/50 rounded-2xl py-2.5 px-3 text-center shadow-2xs">
            <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider block">Broker MQTT</span>
            <span class="text-xs font-black text-slate-800 mt-0.5 block">📡 EMQX Connected</span>
        </div>

        <div class="bg-slate-300/70 border border-slate-300/50 rounded-2xl py-2.5 px-3 text-center shadow-2xs">
            <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider block">Penjadwalan</span>
            <span class="text-xs font-black text-slate-800 mt-0.5 block">⏱️ Shift Otomatis</span>
        </div>

        <div class="bg-slate-300/70 border border-slate-300/50 rounded-2xl py-2.5 px-3 text-center shadow-2xs">
            <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider block">Kondisi Alat</span>
            <span class="text-xs font-black text-emerald-700 mt-0.5 block">✅ 100% Fit</span>
        </div>
    </div>

</section>
