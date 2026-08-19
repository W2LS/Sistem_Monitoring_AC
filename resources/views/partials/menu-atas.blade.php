<!-- Clean Professional Header Bar -->
<header class="sticky top-0 z-50 bg-slate-900/95 backdrop-blur-md text-white border-b border-slate-800/80 shadow-sm">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
        
        <!-- BRAND & LOGO -->
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-teal-500 to-emerald-400 text-slate-950 font-black text-lg flex items-center justify-center shadow-md shadow-teal-500/20 font-outfit">
                P
            </div>
            <div>
                <div class="flex items-center space-x-2">
                    <h1 class="font-outfit font-black text-base tracking-wide text-white uppercase leading-none">
                        PT PINDAD
                    </h1>
                    <span class="text-[10px] font-extrabold text-teal-400 bg-teal-500/10 border border-teal-500/20 px-2 py-0.5 rounded-full uppercase tracking-wider">PERSERO</span>
                </div>
                <p class="text-[11px] font-medium text-slate-400 tracking-wide mt-0.5">
                    Sistem Kontrol & Monitoring AC IoT
                </p>
            </div>
        </div>

        <!-- RIGHT METRICS & STATUS BADGES -->
        <div class="flex items-center space-x-3">
            <!-- MQTT Status Badge -->
            <div class="hidden sm:inline-flex items-center space-x-2 bg-emerald-500/10 border border-emerald-500/20 px-3.5 py-1.5 rounded-full text-xs font-semibold text-emerald-400 shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="tracking-wider uppercase text-[11px] font-bold">MQTT Live</span>
            </div>

            <!-- Real-time Clock Badge -->
            <div class="inline-flex items-center space-x-2 bg-slate-800/80 border border-slate-700/80 px-3.5 py-1.5 rounded-full text-xs text-slate-300 shadow-sm">
                <svg class="w-3.5 h-3.5 text-teal-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden md:inline">Jam Server</span>
                <span id="server-clock" class="font-mono font-bold text-teal-300 text-xs">{{ date('H:i:s') }} WIB</span>
            </div>
        </div>

    </div>
</header>
