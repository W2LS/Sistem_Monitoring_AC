<!-- Header / Navbar -->
<header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-slate-200/60">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-teal-500 to-cyan-500 flex items-center justify-center font-outfit font-extrabold text-xl text-white shadow-md shadow-teal-500/20">
                P
            </div>
            <div>
                <h1 class="font-outfit font-extrabold text-lg leading-tight tracking-wider text-slate-800">
                    PT PINDAD (PERSERO)
                </h1>
                <p class="text-[10px] text-slate-400 font-semibold tracking-widest uppercase">IoT AC Monitoring System</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2 bg-teal-50 px-3.5 py-1.5 rounded-full border border-teal-100">
                <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                <span class="text-xs font-bold text-teal-600 uppercase tracking-wider">MQTT Live</span>
            </div>
            <div class="text-xs text-slate-400 font-medium hidden sm:block">
                Server Time: <span id="server-time" class="font-mono text-slate-600 font-bold">{{ now()->format('H:i:s') }}</span>
            </div>
        </div>
    </div>
</header>
