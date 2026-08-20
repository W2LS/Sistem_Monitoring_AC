<!-- SECTION 3: GRAFIK ARUS & ANALISIS BEBAN KONSUMSI LISTRIK (MODERN INDUSTRIAL GUI) -->
<div class="space-y-6" x-data="{ timeRange: '24j' }">
    
    <!-- PAGE HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-4 font-sans text-slate-800">
        <div>
            <h2 class="font-outfit font-black text-2xl uppercase tracking-wide flex items-center space-x-2">
                <span>📈</span>
                <span>Analisis Konsumsi Arus Listrik</span>
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Monitoring beban daya listrik ACS (Ampere) secara real-time & analisis historis penggunaan.
            </p>
        </div>

        <!-- TIMEFRAME SELECTOR PILLS -->
        <div class="bg-slate-200/80 p-1.5 rounded-2xl font-sans flex items-center space-x-1">
            <template x-for="r in ['1j', '6j', '12j', '24j', '7h']">
                <button 
                    @click="timeRange = r" 
                    :class="timeRange === r ? 'bg-white text-teal-700 shadow-sm font-bold rounded-xl' : 'text-slate-600 hover:text-slate-900 rounded-xl'"
                    class="px-3 py-1 text-xs transition cursor-pointer"
                    x-text="r.toUpperCase()">
                </button>
            </template>
        </div>
    </div>

    <!-- 4 SUMMARY METRIC CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Arus Realtime -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm font-sans p-4 space-y-1">
            <span class="text-slate-400 text-[11px] font-bold uppercase tracking-wider block">Arus Real-time</span>
            <div class="flex items-baseline space-x-1">
                <span class="text-2xl font-black text-teal-600 font-mono">0.5200</span>
                <span class="text-xs font-bold text-slate-500">Ampere</span>
            </div>
            <span class="text-[10px] text-emerald-600 font-bold">
                🟢 Live Telemetry
            </span>
        </div>

        <!-- Arus Minimum -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm font-sans p-4 space-y-1">
            <span class="text-slate-400 text-[11px] font-bold uppercase tracking-wider block">Arus Minimum</span>
            <div class="flex items-baseline space-x-1">
                <span class="text-2xl font-black text-slate-700 font-mono">0.0000</span>
                <span class="text-xs font-bold text-slate-500">Ampere</span>
            </div>
            <span class="text-[10px] text-slate-400 font-semibold">Tercatat dalam 24j</span>
        </div>

        <!-- Arus Maksimum -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm font-sans p-4 space-y-1">
            <span class="text-slate-400 text-[11px] font-bold uppercase tracking-wider block">Arus Maksimum</span>
            <div class="flex items-baseline space-x-1">
                <span class="text-2xl font-black text-amber-600 font-mono">0.8500</span>
                <span class="text-xs font-bold text-slate-500">Ampere</span>
            </div>
            <span class="text-[10px] text-amber-600 font-semibold">Beban puncak (Peak Load)</span>
        </div>

        <!-- Rata-rata Arus -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm font-sans p-4 space-y-1">
            <span class="text-slate-400 text-[11px] font-bold uppercase tracking-wider block">Rata-Rata Arus</span>
            <div class="flex items-baseline space-x-1">
                <span class="text-2xl font-black text-cyan-600 font-mono">0.4850</span>
                <span class="text-xs font-bold text-slate-500">Ampere</span>
            </div>
            <span class="text-[10px] text-cyan-600 font-semibold">Estimasi Normal</span>
        </div>
    </div>

    <!-- MAIN CHART CONTAINER -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm font-sans p-6 space-y-4">
        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
            <h3 class="font-outfit font-black text-lg text-slate-800 uppercase tracking-wide flex items-center space-x-2">
                <span>📊</span>
                <span>Grafik Perbandingan Arus Listrik AC 1 vs AC 2</span>
            </h3>
            <span class="text-slate-400 text-xs font-bold font-mono">
                Sampling: 30s
            </span>
        </div>

        <div class="h-80 w-full relative">
            <canvas id="chart-analisis-detail"></canvas>
        </div>
    </div>

</div>
