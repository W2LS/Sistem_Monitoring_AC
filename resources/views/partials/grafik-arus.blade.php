<!-- GRAFIK TREN ARUS LISTRIK (MODERN INDUSTRIAL GUI) -->
<section class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm font-sans space-y-4 transition-colors">
    <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <div>
            <h3 class="font-outfit font-black text-lg text-slate-800 tracking-wide flex items-center space-x-2">
                <span class="text-teal-600">📈</span>
                <span>Grafik Tren Arus Listrik (Ampere Real-time)</span>
            </h3>
            <p class="text-xs text-slate-400 font-medium">
                Pemantauan beban konsumsi arus listrik AC 1 & AC 2 secara otomatis via sensor ACS712.
            </p>
        </div>
        <span class="text-[11px] font-extrabold text-slate-500 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200">
            Interval: 30 Detik (Sync ESP32)
        </span>
    </div>

    <!-- CANVAS GRAFIK -->
    <div class="w-full relative h-[320px]">
        <canvas id="currentChart"></canvas>
    </div>
</section>
