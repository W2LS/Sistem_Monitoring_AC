<!-- SECTION 3: GRAFIK ARUS & ANALISIS BEBAN KONSUMSI LISTRIK (DUAL THEME) -->
<div class="space-y-6" x-data="{ timeRange: '24j' }">
    
    <!-- PAGE HEADER -->
    <div :class="currentTheme === 'cli' ? 'border-b border-[#1f521f] pb-3 text-[#33ff00] font-mono' : 'border-b border-slate-200 pb-4 font-sans text-slate-800'"
         class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 :class="currentTheme === 'cli' ? 'text-xl font-mono font-bold cli-glow' : 'font-outfit font-black text-2xl'" class="uppercase tracking-wide flex items-center space-x-2">
                <span x-text="currentTheme === 'cli' ? '> TELEMETRY_ANALYTICS :' : '📈'"></span>
                <span x-text="currentTheme === 'cli' ? 'CURRENT_LOAD_SPECTRUM' : 'Analisis Konsumsi Arus Listrik'"></span>
            </h2>
            <p :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-xs font-semibold text-slate-500 mt-1'">
                Monitoring beban daya listrik ACS (Ampere) secara real-time & analisis historis penggunaan.
            </p>
        </div>

        <!-- TIMEFRAME SELECTOR PILLS -->
        <div :class="currentTheme === 'cli' ? 'border border-[#1f521f] bg-[#050505] p-1 text-[#33ff00] font-mono' : 'bg-slate-200/80 p-1.5 rounded-2xl font-sans'" 
             class="flex items-center space-x-1">
            <template x-for="r in ['1j', '6j', '12j', '24j', '7h']">
                <button 
                    @click="timeRange = r" 
                    :class="currentTheme === 'cli' 
                        ? (timeRange === r ? 'bg-[#33ff00] text-[#0a0a0a] font-bold border border-[#33ff00]' : 'text-[#33ff00] hover:bg-[#1f521f]/40') 
                        : (timeRange === r ? 'bg-white text-teal-700 shadow-sm font-bold rounded-xl' : 'text-slate-600 hover:text-slate-900 rounded-xl')"
                    class="px-3 py-1 text-xs transition cursor-pointer"
                    x-text="r.toUpperCase()">
                </button>
            </template>
        </div>
    </div>

    <!-- 4 SUMMARY METRIC CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Arus Realtime -->
        <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] rounded-none text-[#33ff00] font-mono' : 'bg-white rounded-2xl border border-slate-200 shadow-sm font-sans'" class="p-4 space-y-1">
            <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[11px] font-bold uppercase tracking-wider block">Arus Real-time</span>
            <div class="flex items-baseline space-x-1">
                <span :class="currentTheme === 'cli' ? 'text-2xl text-[#33ff00] cli-glow font-bold' : 'text-2xl font-black text-teal-600'" class="font-mono">0.5200</span>
                <span :class="currentTheme === 'cli' ? 'text-xs text-[#1f521f]' : 'text-xs font-bold text-slate-500'">Ampere</span>
            </div>
            <span :class="currentTheme === 'cli' ? 'text-[10px] text-[#33ff00]' : 'text-[10px] text-emerald-600 font-bold'">
                <span x-text="currentTheme === 'cli' ? '[ STREAM: LIVE ]' : '🟢 Live Telemetry'"></span>
            </span>
        </div>

        <!-- Arus Minimum -->
        <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] rounded-none text-[#33ff00] font-mono' : 'bg-white rounded-2xl border border-slate-200 shadow-sm font-sans'" class="p-4 space-y-1">
            <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[11px] font-bold uppercase tracking-wider block">Arus Minimum</span>
            <div class="flex items-baseline space-x-1">
                <span :class="currentTheme === 'cli' ? 'text-2xl text-[#33ff00] font-bold' : 'text-2xl font-black text-slate-700'" class="font-mono">0.0000</span>
                <span :class="currentTheme === 'cli' ? 'text-xs text-[#1f521f]' : 'text-xs font-bold text-slate-500'">Ampere</span>
            </div>
            <span :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-[10px] text-slate-400 font-semibold'">Tercatat dalam 24j</span>
        </div>

        <!-- Arus Maksimum -->
        <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] rounded-none text-[#33ff00] font-mono' : 'bg-white rounded-2xl border border-slate-200 shadow-sm font-sans'" class="p-4 space-y-1">
            <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[11px] font-bold uppercase tracking-wider block">Arus Maksimum</span>
            <div class="flex items-baseline space-x-1">
                <span :class="currentTheme === 'cli' ? 'text-2xl text-[#ffb000] cli-amber-glow font-bold' : 'text-2xl font-black text-amber-600'" class="font-mono">0.8500</span>
                <span :class="currentTheme === 'cli' ? 'text-xs text-[#1f521f]' : 'text-xs font-bold text-slate-500'">Ampere</span>
            </div>
            <span :class="currentTheme === 'cli' ? 'text-[10px] text-[#ffb000]' : 'text-[10px] text-amber-600 font-semibold'">Beban puncak (Peak Load)</span>
        </div>

        <!-- Rata-rata Arus -->
        <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] rounded-none text-[#33ff00] font-mono' : 'bg-white rounded-2xl border border-slate-200 shadow-sm font-sans'" class="p-4 space-y-1">
            <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[11px] font-bold uppercase tracking-wider block">Rata-Rata Arus</span>
            <div class="flex items-baseline space-x-1">
                <span :class="currentTheme === 'cli' ? 'text-2xl text-[#33ff00] font-bold' : 'text-2xl font-black text-cyan-600'" class="font-mono">0.4850</span>
                <span :class="currentTheme === 'cli' ? 'text-xs text-[#1f521f]' : 'text-xs font-bold text-slate-500'">Ampere</span>
            </div>
            <span :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-[10px] text-cyan-600 font-semibold'">Estimasi Normal</span>
        </div>
    </div>

    <!-- MAIN CHART CONTAINER -->
    <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] rounded-none text-[#33ff00] font-mono' : 'bg-white rounded-3xl border border-slate-200 shadow-sm font-sans'" class="p-6 space-y-4">
        <div :class="currentTheme === 'cli' ? 'border-b border-[#1f521f] pb-3' : 'border-b border-slate-100 pb-3'" class="flex items-center justify-between">
            <h3 :class="currentTheme === 'cli' ? 'font-mono text-base font-bold text-[#33ff00] cli-glow' : 'font-outfit font-black text-lg text-slate-800'" class="uppercase tracking-wide flex items-center space-x-2">
                <span x-text="currentTheme === 'cli' ? '> MULTI_CHANNEL_COMPARISON :' : '📊'"></span>
                <span x-text="currentTheme === 'cli' ? 'PANASONIC_1 vs PANASONIC_2' : 'Grafik Perbandingan Arus Listrik AC 1 vs AC 2'"></span>
            </h3>
            <span :class="currentTheme === 'cli' ? 'text-[#1f521f]' : 'text-slate-400'" class="text-xs font-bold font-mono">
                Sampling: 30s
            </span>
        </div>

        <div class="h-80 w-full relative">
            <canvas id="chart-analisis-detail"></canvas>
        </div>
    </div>

</div>
