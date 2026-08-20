<!-- Top Header Bar (Modern Industrial GUI) -->
<header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 h-16 flex-shrink-0 z-40 shadow-xs">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        
        <!-- Left: Location & Server Info -->
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2 text-xs font-semibold text-slate-500">
                <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                <span>Ruang Server 1 • Divisi Sistem Informasi & Fasilitas PT PINDAD</span>
            </div>
        </div>

        <!-- Right Badges & Controls -->
        <div class="flex items-center space-x-3">
            
            <!-- MQTT Status Badge -->
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full inline-flex items-center space-x-2 px-3.5 py-1.5 text-xs font-semibold shadow-2xs">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="tracking-wider uppercase text-[11px] font-bold">MQTT Broker Connected</span>
            </div>

            <!-- Real-time Clock Badge -->
            <div class="bg-slate-50 border border-slate-200 text-slate-700 rounded-full inline-flex items-center space-x-2 px-3.5 py-1.5 text-xs shadow-2xs">
                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 hidden sm:inline">Jam Server:</span>
                <span id="server-clock" class="text-xs font-mono font-black text-teal-600">
                    {{ date('H:i:s') }} WIB
                </span>
            </div>

        </div>
    </div>
</header>
