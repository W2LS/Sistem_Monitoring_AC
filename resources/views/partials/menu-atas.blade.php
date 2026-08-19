<!-- Clean White Top Header Bar -->
<header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-end space-x-3">
        
        <!-- MQTT Status Badge -->
        <div class="inline-flex items-center space-x-2 bg-emerald-50 border border-emerald-200 px-3.5 py-1.5 rounded-full text-xs font-semibold text-emerald-700 shadow-2xs">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="tracking-wider uppercase text-[11px] font-bold">MQTT Live</span>
        </div>

        <!-- Real-time Clock Badge -->
        <div class="inline-flex items-center space-x-2 bg-slate-50 border border-slate-200 px-4 py-1.5 rounded-full text-xs text-slate-700 shadow-2xs">
            <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider hidden sm:inline">Jam Server</span>
            <span id="server-clock" class="font-mono font-black text-teal-600 text-xs">{{ date('H:i:s') }} WIB</span>
        </div>

    </div>
</header>
