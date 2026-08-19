<!-- SECTION 3: GRAFIK ARUS & ANALISIS BEBAN KONSUMSI LISTRIK -->
<div class="space-y-6" x-data="{ timeRange: '24j' }">
    
    <!-- PAGE HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <h2 class="font-outfit font-black text-2xl text-slate-800 uppercase tracking-wide flex items-center space-x-2">
                <span class="text-teal-600">📈</span>
                <span>Analisis Konsumsi Arus Listrik</span>
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Monitoring beban daya listrik ACS (Ampere) secara real-time & analisis historis penggunaan.
            </p>
        </div>

        <!-- TIMEFRAME SELECTOR PILLS -->
        <div class="flex items-center space-x-1.5 bg-slate-200/80 p-1.5 rounded-2xl">
            <button 
                @click="timeRange = '1j'" 
                :class="timeRange === '1j' ? 'bg-white text-teal-700 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-3 py-1.5 rounded-xl text-xs transition">
                1 Jam
            </button>
            <button 
                @click="timeRange = '6j'" 
                :class="timeRange === '6j' ? 'bg-white text-teal-700 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-3 py-1.5 rounded-xl text-xs transition">
                6 Jam
            </button>
            <button 
                @click="timeRange = '12j'" 
                :class="timeRange === '12j' ? 'bg-white text-teal-700 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-3 py-1.5 rounded-xl text-xs transition">
                12 Jam
            </button>
            <button 
                @click="timeRange = '24j'" 
                :class="timeRange === '24j' ? 'bg-white text-teal-700 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-3 py-1.5 rounded-xl text-xs transition">
                24 Jam
            </button>
            <button 
                @click="timeRange = '7h'" 
                :class="timeRange === '7h' ? 'bg-white text-teal-700 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-3 py-1.5 rounded-xl text-xs transition">
                7 Hari
            </button>
        </div>
    </div>

    <!-- 4 SUMMARY METRIC CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Arus Realtime -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm space-y-1">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Arus Real-time</span>
            <div class="flex items-baseline space-x-1">
                <span class="text-2xl font-black font-mono text-teal-600">0.5200</span>
                <span class="text-xs font-bold text-slate-500">Ampere</span>
            </div>
            <span class="text-[10px] text-emerald-600 font-bold flex items-center space-x-1">
                <span>🟢 Live Telemetry</span>
            </span>
        </div>

        <!-- Arus Minimum -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm space-y-1">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Arus Minimum</span>
            <div class="flex items-baseline space-x-1">
                <span class="text-2xl font-black font-mono text-slate-700">0.0000</span>
                <span class="text-xs font-bold text-slate-500">Ampere</span>
            </div>
            <span class="text-[10px] text-slate-400 font-semibold">Tercatat dalam 24j</span>
        </div>

        <!-- Arus Maksimum -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm space-y-1">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Arus Maksimum</span>
            <div class="flex items-baseline space-x-1">
                <span class="text-2xl font-black font-mono text-amber-600">0.8500</span>
                <span class="text-xs font-bold text-slate-500">Ampere</span>
            </div>
            <span class="text-[10px] text-amber-600 font-semibold">Beban puncak (Peak Load)</span>
        </div>

        <!-- Rata-rata Arus -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm space-y-1">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Rata-Rata Arus</span>
            <div class="flex items-baseline space-x-1">
                <span class="text-2xl font-black font-mono text-cyan-600">0.4850</span>
                <span class="text-xs font-bold text-slate-500">Ampere</span>
            </div>
            <span class="text-[10px] text-cyan-600 font-semibold">Estimasi Normal</span>
        </div>
    </div>

    <!-- MAIN CHART CONTAINER -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-outfit font-black text-lg text-slate-800 uppercase tracking-wide flex items-center space-x-2">
                <span class="text-indigo-500">📊</span>
                <span>Grafik Perbandingan Arus Listrik AC 1 vs AC 2</span>
            </h3>
            <span class="text-xs font-bold text-slate-400">Interval Sampel: 3 Detik</span>
        </div>

        <div class="h-80 w-full relative">
            <canvas id="chart-analisis-detail"></canvas>
        </div>
    </div>

</div>
