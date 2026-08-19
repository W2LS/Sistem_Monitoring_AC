<!-- Clean Header Bar -->
<header class="bg-slate-900 text-white shadow-md border-b border-slate-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
        
        <!-- BRAND & LOGO -->
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-teal-500 text-slate-950 font-black text-xl flex items-center justify-center shadow-lg font-outfit">
                P
            </div>
            <div>
                <h1 class="font-outfit font-black text-lg tracking-wider text-white uppercase leading-none">
                    PT PINDAD <span class="text-teal-400 font-extrabold text-sm">(PERSERO)</span>
                </h1>
                <p class="text-[11px] font-semibold text-slate-400 tracking-wide mt-1">
                    Sistem Kontrol & Monitoring AC IoT
                </p>
            </div>
        </div>

        <!-- RIGHT METRICS & STATUS -->
        <div class="flex items-center space-x-4">
            <!-- MQTT Status Badge -->
            <div class="hidden sm:flex items-center space-x-2 bg-slate-800/90 border border-slate-700 px-3.5 py-1.5 rounded-xl shadow-inner">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-extrabold text-slate-200 tracking-wide uppercase">MQTT Live</span>
            </div>

            <!-- Real-time Clock Badge -->
            <div class="bg-slate-800/90 border border-slate-700 px-4 py-1.5 rounded-xl text-right">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Jam Server</span>
                <span class="text-xs font-black font-mono text-teal-300">{{ date('H:i:s') }} WIB</span>
            </div>
        </div>

    </div>
</header>
