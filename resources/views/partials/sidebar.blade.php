<!-- Vertical Sidebar Navigation (Dual-Theme: Modern Slate GUI & Retro Terminal CLI) -->
<aside :class="currentTheme === 'cli' ? 'bg-[#050505] border-r border-[#1f521f] text-[#33ff00] font-mono' : 'bg-slate-900 text-slate-300 border-r border-slate-800 font-sans'"
       class="w-64 h-screen flex flex-col justify-between p-5 flex-shrink-0 shadow-xl overflow-y-auto z-40 transition-colors duration-200">
    
    <div class="space-y-6">
        
        <!-- BRAND & LOGO HEADER -->
        <div class="px-2 pt-2">
            <!-- GUI Logo -->
            <template x-if="currentTheme === 'gui'">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-teal-500 to-emerald-400 text-slate-950 font-black text-xl flex items-center justify-center shadow-lg shadow-teal-500/20 font-outfit shrink-0">
                        P
                    </div>
                    <div class="overflow-hidden">
                        <h1 class="font-outfit font-black text-base tracking-wide text-white uppercase leading-none truncate">
                            PT PINDAD <span class="text-[10px] text-teal-400 font-extrabold">(PERSERO)</span>
                        </h1>
                        <p class="text-[11px] font-medium text-slate-400 tracking-wide mt-1 truncate">
                            Sistem Kontrol & Monitoring AC IoT
                        </p>
                    </div>
                </div>
            </template>

            <!-- CLI Logo (ASCII Frame) -->
            <template x-if="currentTheme === 'cli'">
                <div class="border border-[#1f521f] bg-[#0a0a0a] p-3 text-[#33ff00] space-y-1">
                    <div class="text-[11px] font-bold text-[#ffb000] tracking-wider leading-none">
                        // PT_PINDAD_PERSERO //
                    </div>
                    <div class="text-xs font-black cli-glow uppercase tracking-wider">
                        &gt; IOT_AC_TERMINAL_V1
                    </div>
                    <div class="text-[10px] text-[#1f521f] font-mono">
                        HOST: 192.168.1.100:8000
                    </div>
                </div>
            </template>
        </div>

        <!-- NAVIGATION MENU LIST -->
        <nav class="space-y-2">
            
            <!-- 1. DASHBOARD -->
            <button 
                @click="activeTab = 'dashboard'"
                :class="currentTheme === 'cli'
                    ? (activeTab === 'dashboard' ? 'bg-[#33ff00] text-[#0a0a0a] font-bold font-mono border border-[#33ff00] shadow-[0_0_10px_rgba(51,255,0,0.5)]' : 'text-[#33ff00] hover:bg-[#1f521f]/30 border border-transparent hover:border-[#1f521f] font-mono')
                    : (activeTab === 'dashboard' ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg shadow-teal-600/30 font-bold rounded-2xl' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200 rounded-2xl')"
                class="w-full flex items-center space-x-3.5 px-4 py-3 transition-all duration-200 text-left group cursor-pointer">
                
                <template x-if="currentTheme === 'gui'">
                    <div class="p-2 rounded-xl" :class="activeTab === 'dashboard' ? 'bg-teal-500/20 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                </template>
                <template x-if="currentTheme === 'cli'">
                    <span class="text-xs font-mono font-bold" x-text="activeTab === 'dashboard' ? '► [01]' : '  [01]'"></span>
                </template>

                <div>
                    <div class="text-sm font-bold leading-tight" x-text="currentTheme === 'cli' ? '> DASHBOARD_SYS' : 'Dashboard'"></div>
                    <div class="text-[11px] opacity-75 font-normal" x-text="currentTheme === 'cli' ? 'TELEMETRY_SUMMARY' : 'Ringkasan Sistem'"></div>
                </div>
            </button>

            <!-- 2. UNIT AC -->
            <button 
                @click="activeTab = 'unit-ac'"
                :class="currentTheme === 'cli'
                    ? (activeTab === 'unit-ac' ? 'bg-[#33ff00] text-[#0a0a0a] font-bold font-mono border border-[#33ff00] shadow-[0_0_10px_rgba(51,255,0,0.5)]' : 'text-[#33ff00] hover:bg-[#1f521f]/30 border border-transparent hover:border-[#1f521f] font-mono')
                    : (activeTab === 'unit-ac' ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg shadow-teal-600/30 font-bold rounded-2xl' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200 rounded-2xl')"
                class="w-full flex items-center space-x-3.5 px-4 py-3 transition-all duration-200 text-left group cursor-pointer">
                
                <template x-if="currentTheme === 'gui'">
                    <div class="p-2 rounded-xl" :class="activeTab === 'unit-ac' ? 'bg-teal-500/20 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </template>
                <template x-if="currentTheme === 'cli'">
                    <span class="text-xs font-mono font-bold" x-text="activeTab === 'unit-ac' ? '► [02]' : '  [02]'"></span>
                </template>

                <div>
                    <div class="text-sm font-bold leading-tight" x-text="currentTheme === 'cli' ? '> UNIT_AC_CTRL' : 'Unit AC'"></div>
                    <div class="text-[11px] opacity-75 font-normal" x-text="currentTheme === 'cli' ? 'RELAY_PINS_18_19' : 'Status & Kontrol'"></div>
                </div>
            </button>

            <!-- 3. GRAFIK ARUS -->
            <button 
                @click="activeTab = 'grafik-arus'"
                :class="currentTheme === 'cli'
                    ? (activeTab === 'grafik-arus' ? 'bg-[#33ff00] text-[#0a0a0a] font-bold font-mono border border-[#33ff00] shadow-[0_0_10px_rgba(51,255,0,0.5)]' : 'text-[#33ff00] hover:bg-[#1f521f]/30 border border-transparent hover:border-[#1f521f] font-mono')
                    : (activeTab === 'grafik-arus' ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg shadow-teal-600/30 font-bold rounded-2xl' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200 rounded-2xl')"
                class="w-full flex items-center space-x-3.5 px-4 py-3 transition-all duration-200 text-left group cursor-pointer">
                
                <template x-if="currentTheme === 'gui'">
                    <div class="p-2 rounded-xl" :class="activeTab === 'grafik-arus' ? 'bg-teal-500/20 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                </template>
                <template x-if="currentTheme === 'cli'">
                    <span class="text-xs font-mono font-bold" x-text="activeTab === 'grafik-arus' ? '► [03]' : '  [03]'"></span>
                </template>

                <div>
                    <div class="text-sm font-bold leading-tight" x-text="currentTheme === 'cli' ? '> AMPERE_CHART' : 'Grafik Arus'"></div>
                    <div class="text-[11px] opacity-75 font-normal" x-text="currentTheme === 'cli' ? 'SINE_WAVE_ANALYSIS' : 'Monitoring Arus'"></div>
                </div>
            </button>

            <!-- 4. PENJADWALAN -->
            <button 
                @click="activeTab = 'penjadwalan'"
                :class="currentTheme === 'cli'
                    ? (activeTab === 'penjadwalan' ? 'bg-[#33ff00] text-[#0a0a0a] font-bold font-mono border border-[#33ff00] shadow-[0_0_10px_rgba(51,255,0,0.5)]' : 'text-[#33ff00] hover:bg-[#1f521f]/30 border border-transparent hover:border-[#1f521f] font-mono')
                    : (activeTab === 'penjadwalan' ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg shadow-teal-600/30 font-bold rounded-2xl' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200 rounded-2xl')"
                class="w-full flex items-center space-x-3.5 px-4 py-3 transition-all duration-200 text-left group cursor-pointer">
                
                <template x-if="currentTheme === 'gui'">
                    <div class="p-2 rounded-xl" :class="activeTab === 'penjadwalan' ? 'bg-teal-500/20 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </template>
                <template x-if="currentTheme === 'cli'">
                    <span class="text-xs font-mono font-bold" x-text="activeTab === 'penjadwalan' ? '► [04]' : '  [04]'"></span>
                </template>

                <div>
                    <div class="text-sm font-bold leading-tight" x-text="currentTheme === 'cli' ? '> CRON_SCHEDULE' : 'Penjadwalan'"></div>
                    <div class="text-[11px] opacity-75 font-normal" x-text="currentTheme === 'cli' ? 'AUTO_ROTATION_RULES' : 'Jadwal ON/OFF'"></div>
                </div>
            </button>

            <!-- 5. RIWAYAT -->
            <button 
                @click="activeTab = 'riwayat'"
                :class="currentTheme === 'cli'
                    ? (activeTab === 'riwayat' ? 'bg-[#33ff00] text-[#0a0a0a] font-bold font-mono border border-[#33ff00] shadow-[0_0_10px_rgba(51,255,0,0.5)]' : 'text-[#33ff00] hover:bg-[#1f521f]/30 border border-transparent hover:border-[#1f521f] font-mono')
                    : (activeTab === 'riwayat' ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg shadow-teal-600/30 font-bold rounded-2xl' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200 rounded-2xl')"
                class="w-full flex items-center space-x-3.5 px-4 py-3 transition-all duration-200 text-left group cursor-pointer">
                
                <template x-if="currentTheme === 'gui'">
                    <div class="p-2 rounded-xl" :class="activeTab === 'riwayat' ? 'bg-teal-500/20 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0zM12 4v.01M12 20v.01M4 12v.01M20 12v.01M6.343 6.343l.01.01M17.657 17.657l.01.01M6.343 17.657l.01.01M17.657 6.343l.01.01" />
                        </svg>
                    </div>
                </template>
                <template x-if="currentTheme === 'cli'">
                    <span class="text-xs font-mono font-bold" x-text="activeTab === 'riwayat' ? '► [05]' : '  [05]'"></span>
                </template>

                <div>
                    <div class="text-sm font-bold leading-tight" x-text="currentTheme === 'cli' ? '> TELEMETRY_LOG' : 'Riwayat'"></div>
                    <div class="text-[11px] opacity-75 font-normal" x-text="currentTheme === 'cli' ? 'DATABASE_STREAM' : 'Log & Aktivitas'"></div>
                </div>
            </button>

            <!-- 6. PENGATURAN -->
            <button 
                @click="activeTab = 'pengaturan'"
                :class="currentTheme === 'cli'
                    ? (activeTab === 'pengaturan' ? 'bg-[#33ff00] text-[#0a0a0a] font-bold font-mono border border-[#33ff00] shadow-[0_0_10px_rgba(51,255,0,0.5)]' : 'text-[#33ff00] hover:bg-[#1f521f]/30 border border-transparent hover:border-[#1f521f] font-mono')
                    : (activeTab === 'pengaturan' ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg shadow-teal-600/30 font-bold rounded-2xl' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200 rounded-2xl')"
                class="w-full flex items-center space-x-3.5 px-4 py-3 transition-all duration-200 text-left group cursor-pointer">
                
                <template x-if="currentTheme === 'gui'">
                    <div class="p-2 rounded-xl" :class="activeTab === 'pengaturan' ? 'bg-teal-500/20 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </template>
                <template x-if="currentTheme === 'cli'">
                    <span class="text-xs font-mono font-bold" x-text="activeTab === 'pengaturan' ? '► [06]' : '  [06]'"></span>
                </template>

                <div>
                    <div class="text-sm font-bold leading-tight" x-text="currentTheme === 'cli' ? '> SYS_CONFIG' : 'Pengaturan'"></div>
                    <div class="text-[11px] opacity-75 font-normal" x-text="currentTheme === 'cli' ? 'EMQX_BROKER_SETUP' : 'Konfigurasi Sistem'"></div>
                </div>
            </button>

        </nav>
    </div>

    <!-- BOTTOM INFO / SUPPORT WIDGET -->
    <div>
        <template x-if="currentTheme === 'gui'">
            <div class="bg-slate-800/80 border border-slate-700/60 rounded-2xl p-4 mt-6 space-y-3 shadow-inner">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-200">PT PINDAD IoT</h4>
                        <p class="text-[10px] text-slate-400">Server Room 1</p>
                    </div>
                </div>
                <button onclick="alert('Layanan Dukungan Teknis IoT PT PINDAD (Persero)\nEmail: support@pindad.com\nTelepon: (022) 737001')" class="w-full bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold py-2 px-3 rounded-xl transition duration-200 shadow-md">
                    Hubungi Kami
                </button>
            </div>
        </template>

        <template x-if="currentTheme === 'cli'">
            <div class="border border-[#1f521f] bg-[#0a0a0a] p-3 text-[11px] font-mono text-[#33ff00] space-y-1">
                <div class="text-[#ffb000] font-bold">[ SYSTEM_DIAGNOSTICS ]</div>
                <div class="text-[#33ff00]">ESP32: <span class="cli-glow font-bold">[ONLINE]</span></div>
                <div class="text-[#1f521f] text-[10px]">UPTIME: 99.98% // NO_ERR</div>
            </div>
        </template>
    </div>

</aside>
