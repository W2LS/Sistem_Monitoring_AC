<!-- Footer (Dual-Theme: Modern GUI & Retro Terminal CLI) -->
<footer :class="currentTheme === 'cli' ? 'bg-[#050505] border-t border-[#1f521f] text-[#1f521f] font-mono' : 'bg-white border-t border-slate-200/80 text-slate-500 font-sans'"
        class="py-4 text-center text-xs transition-colors">
    <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-2">
        <p :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="font-bold">
            <span x-text="currentTheme === 'cli' ? '// PT_PINDAD_PERSERO :: IOT_AC_MONITORING_SYSTEM_2026 //' : '© ' + {{ date('Y') }} + ' PT PINDAD (Persero) - Sistem Monitoring & Kontrol AC IoT.'"></span>
        </p>
        <p :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[11px] font-mono">
            <span x-text="currentTheme === 'cli' ? '[ ARCH: LARAVEL_11 + PHP_8.4 + MQTT_EMQX + ESP32 ]' : 'Magang IoT Infrastructure Team'"></span>
        </p>
    </div>
</footer>
