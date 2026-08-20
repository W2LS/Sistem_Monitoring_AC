<!-- GRAFIK TREN ARUS LISTRIK (SOPHISTICATED NEO-CARD - 40px RADIUS) -->
<section class="bg-white rounded-[40px] p-7 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.08)] border border-[#b7c6c2]/30 space-y-4">
    <div class="border-b border-[#b7c6c2]/20 pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#b7c6c2] block">Analisis Spektrum Beban</span>
            <h3 class="text-2xl font-black text-[#171e19] tracking-tight flex items-center space-x-2">
                <span>Grafik Tren Beban Arus Listrik</span>
            </h3>
            <p class="text-xs text-slate-400 font-semibold mt-0.5">
                Monitoring perbandingan konsumsi daya AC 1 vs AC 2 via sensor ACS712.
            </p>
        </div>
        
        <div class="flex items-center space-x-2">
            <span class="text-[10px] font-black uppercase tracking-wider text-[#171e19] bg-[#eeebe3] px-3.5 py-1.5 rounded-full border border-[#b7c6c2]/30">
                ⚡ Sampling: 30s
            </span>
            <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800 bg-emerald-100 px-3 py-1.5 rounded-full">
                ● Live Stream
            </span>
        </div>
    </div>

    <!-- CANVAS GRAFIK -->
    <div class="w-full relative h-[320px]">
        <canvas id="currentChart"></canvas>
    </div>
</section>
