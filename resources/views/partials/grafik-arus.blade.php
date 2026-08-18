<!-- TAB 2: GRAFIK ARUS FULL CONTAINER -->
<section class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
        <div>
            <h3 class="font-outfit font-black text-xl text-slate-800 tracking-wide flex items-center space-x-2">
                <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                <span>Grafik Tren Arus Listrik (Ampere)</span>
            </h3>
            <p class="text-xs text-slate-400 font-semibold mt-1">Pemantauan grafik gelombang beban arus listrik secara live dari sensor ACS712.</p>
        </div>

        <div class="flex items-center space-x-2">
            <span class="text-xs bg-slate-100 px-3 py-1.5 rounded-xl font-extrabold text-slate-600 border border-slate-200">
                Updates Every 3s
            </span>
        </div>
    </div>

    <!-- CANVAS GRAFIK FULL SIZE -->
    <div class="w-full relative h-[380px] bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
        <canvas id="currentChart"></canvas>
    </div>
</section>
