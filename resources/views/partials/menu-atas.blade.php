<!-- Top Header Bar with Dual-Theme Toggle -->
<header :class="currentTheme === 'cli' ? 'bg-[#050505] border-b border-[#1f521f] text-[#33ff00]' : 'bg-white/90 backdrop-blur-md border-b border-slate-200/80'" 
        class="h-16 flex-shrink-0 z-40 shadow-xs transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        
        <!-- Left: Hostname & Subsystem Info (especially in CLI mode) -->
        <div class="flex items-center space-x-3">
            <template x-if="currentTheme === 'cli'">
                <div class="flex items-center space-x-2 text-xs font-mono text-[#33ff00]">
                    <span class="text-[#ffb000] font-black">root@pindad-server-1:</span>
                    <span class="text-[#33ff00]">/dev/ttyUSB0</span>
                    <span class="text-[#1f521f] font-bold hidden sm:inline">[BAUD: 115200]</span>
                </div>
            </template>
            <template x-if="currentTheme === 'gui'">
                <div class="text-xs font-semibold text-slate-500 hidden sm:block">
                    Ruang Server 1 • Gedung Produksi PT PINDAD
                </div>
            </template>
        </div>

        <!-- Right Badges & Controls -->
        <div class="flex items-center space-x-3">
            
            <!-- THEME SWITCHER TOGGLE BUTTON -->
            <button 
                @click="toggleTheme()" 
                type="button"
                :class="currentTheme === 'cli' 
                    ? 'border border-[#33ff00] bg-[#0a0a0a] text-[#33ff00] cli-glow cli-btn-invert rounded-none font-mono' 
                    : 'bg-slate-100 hover:bg-teal-50 border border-slate-200 hover:border-teal-300 text-slate-700 hover:text-teal-700 rounded-full font-sans'"
                class="inline-flex items-center space-x-2 px-3.5 py-1.5 text-xs font-black transition-all cursor-pointer shadow-sm">
                
                <template x-if="currentTheme === 'gui'">
                    <div class="flex items-center space-x-1.5">
                        <span class="text-sm">💻</span>
                        <span class="tracking-wide">MODE: <strong class="text-teal-600">GUI MODERN</strong></span>
                        <span class="text-[10px] text-slate-400 font-bold bg-white px-1.5 py-0.5 rounded border border-slate-200">Ganti ke CLI</span>
                    </div>
                </template>
                <template x-if="currentTheme === 'cli'">
                    <div class="flex items-center space-x-1.5 font-mono">
                        <span class="cli-blink">⚡</span>
                        <span class="tracking-wider">[ THEME: TERMINAL_CLI ]</span>
                        <span class="text-[10px] border border-[#33ff00] px-1 bg-[#1f521f]/40 text-[#33ff00]">➔ TO_GUI</span>
                    </div>
                </template>
            </button>

            <!-- MQTT Status Badge -->
            <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] text-[#33ff00] rounded-none font-mono' : 'bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full font-sans'"
                 class="inline-flex items-center space-x-2 px-3.5 py-1.5 text-xs font-semibold shadow-2xs">
                <span class="relative flex h-2 w-2">
                    <span :class="currentTheme === 'cli' ? 'bg-[#33ff00]' : 'bg-emerald-400'" class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"></span>
                    <span :class="currentTheme === 'cli' ? 'bg-[#33ff00]' : 'bg-emerald-500'" class="relative inline-flex rounded-full h-2 w-2"></span>
                </span>
                <span class="tracking-wider uppercase text-[11px] font-bold" x-text="currentTheme === 'cli' ? 'EMQX://OK' : 'MQTT Live'"></span>
            </div>

            <!-- Real-time Clock Badge -->
            <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] text-[#33ff00] rounded-none font-mono' : 'bg-slate-50 border border-slate-200 text-slate-700 rounded-full font-sans'"
                 class="inline-flex items-center space-x-2 px-3.5 py-1.5 text-xs shadow-2xs">
                <template x-if="currentTheme === 'gui'">
                    <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <span :class="currentTheme === 'cli' ? 'text-[#ffb000] font-mono' : 'text-slate-400 font-sans'" 
                      class="text-[11px] font-extrabold uppercase tracking-wider hidden sm:inline"
                      x-text="currentTheme === 'cli' ? 'RTC:' : 'Jam Server'"></span>
                <span id="server-clock" :class="currentTheme === 'cli' ? 'text-[#33ff00] font-mono font-bold cli-glow' : 'text-teal-600 font-mono font-black'" class="text-xs">
                    {{ date('H:i:s') }} WIB
                </span>
            </div>

        </div>
    </div>
</header>
