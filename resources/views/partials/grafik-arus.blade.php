<!-- GRAFIK TREN ARUS LISTRIK (DUAL THEME) -->
<section :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] rounded-none p-5 text-[#33ff00] font-mono' : 'bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm font-sans'" 
         class="space-y-4 transition-colors">
    <div :class="currentTheme === 'cli' ? 'border-b border-[#1f521f] pb-3' : 'border-b border-slate-100 pb-4'" 
         class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <div>
            <h3 :class="currentTheme === 'cli' ? 'text-base font-mono font-bold text-[#33ff00] cli-glow' : 'font-outfit font-black text-lg text-slate-800'" 
                class="tracking-wide flex items-center space-x-2">
                <span x-text="currentTheme === 'cli' ? '> OSCILLOSCOPE_STREAM :' : '📈'"></span>
                <span x-text="currentTheme === 'cli' ? 'AMPERE_LOAD_TELEMETRY (ACS712)' : 'Grafik Tren Arus Listrik (Ampere Real-time)'"></span>
            </h3>
            <p :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-xs text-slate-400 font-medium'">
                Pemantauan beban konsumsi arus listrik AC 1 & AC 2 secara otomatis.
            </p>
        </div>
        <span :class="currentTheme === 'cli' ? 'border border-[#33ff00] bg-[#0a0a0a] text-[#33ff00] rounded-none text-[10px] font-mono cli-glow' : 'text-[11px] font-extrabold text-slate-500 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200'"
              class="px-2.5 py-1">
            <span x-text="currentTheme === 'cli' ? '[ SAMPLING: 30s // SYNC_ESP32 ]' : 'Interval: 30 Detik'"></span>
        </span>
    </div>

    <!-- CANVAS GRAFIK -->
    <div class="w-full relative h-[320px]">
        <canvas id="currentChart"></canvas>
    </div>
</section>
