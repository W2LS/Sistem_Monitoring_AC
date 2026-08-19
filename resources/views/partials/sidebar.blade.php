<!-- Vertical Dark Sidebar Navigation -->
<aside class="w-64 h-screen bg-slate-900 text-slate-300 flex flex-col justify-between p-5 flex-shrink-0 border-r border-slate-800 shadow-xl overflow-y-auto z-50">
    
    <div class="space-y-8">
        <!-- BRAND & LOGO HEADER -->
        <div class="flex items-center space-x-3 px-2 pt-2">
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

        <!-- NAVIGATION MENU LIST -->
        <nav class="space-y-2">
            <!-- 1. DASHBOARD -->
            <button 
                @click="activeTab = 'dashboard'"
                :class="activeTab === 'dashboard' ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg shadow-teal-600/30 font-bold' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200'"
                class="w-full flex items-center space-x-3.5 px-4 py-3 rounded-2xl transition-all duration-200 text-left group">
                <div class="p-2 rounded-xl" :class="activeTab === 'dashboard' ? 'bg-teal-500/20 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-bold leading-tight">Dashboard</div>
                    <div class="text-[11px] opacity-75 font-normal">Ringkasan Sistem</div>
                </div>
            </button>

            <!-- 2. UNIT AC -->
            <button 
                @click="activeTab = 'unit-ac'"
                :class="activeTab === 'unit-ac' ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg shadow-teal-600/30 font-bold' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200'"
                class="w-full flex items-center space-x-3.5 px-4 py-3 rounded-2xl transition-all duration-200 text-left group">
                <div class="p-2 rounded-xl" :class="activeTab === 'unit-ac' ? 'bg-teal-500/20 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-bold leading-tight">Unit AC</div>
                    <div class="text-[11px] opacity-75 font-normal">Status & Kontrol</div>
                </div>
            </button>

            <!-- 3. GRAFIK ARUS -->
            <button 
                @click="activeTab = 'grafik-arus'"
                :class="activeTab === 'grafik-arus' ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg shadow-teal-600/30 font-bold' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200'"
                class="w-full flex items-center space-x-3.5 px-4 py-3 rounded-2xl transition-all duration-200 text-left group">
                <div class="p-2 rounded-xl" :class="activeTab === 'grafik-arus' ? 'bg-teal-500/20 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-bold leading-tight">Grafik Arus</div>
                    <div class="text-[11px] opacity-75 font-normal">Monitoring Arus</div>
                </div>
            </button>

            <!-- 4. PENJADWALAN -->
            <button 
                @click="activeTab = 'penjadwalan'"
                :class="activeTab === 'penjadwalan' ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg shadow-teal-600/30 font-bold' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200'"
                class="w-full flex items-center space-x-3.5 px-4 py-3 rounded-2xl transition-all duration-200 text-left group">
                <div class="p-2 rounded-xl" :class="activeTab === 'penjadwalan' ? 'bg-teal-500/20 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-bold leading-tight">Penjadwalan</div>
                    <div class="text-[11px] opacity-75 font-normal">Jadwal ON/OFF</div>
                </div>
            </button>

            <!-- 5. RIWAYAT -->
            <button 
                @click="activeTab = 'riwayat'"
                :class="activeTab === 'riwayat' ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg shadow-teal-600/30 font-bold' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200'"
                class="w-full flex items-center space-x-3.5 px-4 py-3 rounded-2xl transition-all duration-200 text-left group">
                <div class="p-2 rounded-xl" :class="activeTab === 'riwayat' ? 'bg-teal-500/20 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0zM12 4v.01M12 20v.01M4 12v.01M20 12v.01M6.343 6.343l.01.01M17.657 17.657l.01.01M6.343 17.657l.01.01M17.657 6.343l.01.01" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-bold leading-tight">Riwayat</div>
                    <div class="text-[11px] opacity-75 font-normal">Log & Aktivitas</div>
                </div>
            </button>

            <!-- 6. PENGATURAN -->
            <button 
                @click="activeTab = 'pengaturan'"
                :class="activeTab === 'pengaturan' ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg shadow-teal-600/30 font-bold' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200'"
                class="w-full flex items-center space-x-3.5 px-4 py-3 rounded-2xl transition-all duration-200 text-left group">
                <div class="p-2 rounded-xl" :class="activeTab === 'pengaturan' ? 'bg-teal-500/20 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-bold leading-tight">Pengaturan</div>
                    <div class="text-[11px] opacity-75 font-normal">Konfigurasi Sistem</div>
                </div>
            </button>
        </nav>
    </div>

    <!-- BOTTOM SUPPORT WIDGET -->
    <div class="bg-slate-800/80 border border-slate-700/60 rounded-2xl p-4 mt-6 space-y-3 shadow-inner">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-200">Butuh Bantuan?</h4>
                <p class="text-[10px] text-slate-400">Tim support siap membantu</p>
            </div>
        </div>
        <button onclick="alert('Layanan Dukungan Teknis IoT PT PINDAD (Persero)\nEmail: support@pindad.com\nTelepon: (022) 737001')" class="w-full bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold py-2 px-3 rounded-xl transition duration-200 shadow-md">
            Hubungi Kami
        </button>
    </div>

</aside>
