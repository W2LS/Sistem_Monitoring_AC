<!-- Header / Navbar Utama -->
<header class="bg-white border-b border-slate-200/80 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
        
        <!-- LOGO & BRAND -->
        <div class="flex items-center space-x-3 shrink-0">
            <div class="bg-slate-800 text-white font-outfit font-black px-4 py-2 rounded-xl text-lg tracking-wider shadow-sm flex items-center space-x-2">
                <span class="text-teal-400 font-extrabold">PINDAD</span>
                <span class="text-xs text-slate-300 font-semibold px-2 py-0.5 bg-slate-700 rounded-md">IoT</span>
            </div>
        </div>

        <!-- TAB NAVIGASI UTAMA (Middle Navigation Pills) -->
        <nav class="hidden md:flex items-center bg-slate-100 p-1.5 rounded-2xl border border-slate-200/60 space-x-1">
            <button @click="activeTab = 'home'" 
                    :class="activeTab === 'home' ? 'bg-slate-800 text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'"
                    class="px-5 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Home</span>
            </button>

            <button @click="activeTab = 'grafik'" 
                    :class="activeTab === 'grafik' ? 'bg-slate-800 text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'"
                    class="px-5 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                <span>Grafik</span>
            </button>

            <button @click="activeTab = 'log'" 
                    :class="activeTab === 'log' ? 'bg-slate-800 text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'"
                    class="px-5 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Log Report</span>
            </button>

            <button @click="activeTab = 'penjadwalan'" 
                    :class="activeTab === 'penjadwalan' ? 'bg-slate-800 text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'"
                    class="px-5 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Penjadwalan</span>
            </button>
        </nav>

        <!-- AKSI KANAN (Theme, Notification, Settings, User) -->
        <div class="flex items-center space-x-2 shrink-0">
            <!-- Mode Terang / Gelap Icon Button -->
            <button title="Mode Tampilan" class="p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </button>

            <!-- Notifikasi Bell -->
            <button title="Notifikasi System" class="relative p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white"></span>
            </button>

            <!-- Settings Gear -->
            <button title="Pengaturan Sistem" class="p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </button>

            <!-- User Profile Pill -->
            <div class="flex items-center space-x-2 pl-2 border-l border-slate-200">
                <div class="w-8 h-8 rounded-xl bg-slate-800 text-white font-bold text-xs flex items-center justify-center">
                    U1
                </div>
                <span class="text-xs font-bold text-slate-700 hidden sm:inline">Akun</span>
            </div>
        </div>

    </div>
</header>

<!-- SECONDARY BANNER BAR (Halaman Title Banner sesuai Wireframe) -->
<section class="bg-slate-100/80 border-b border-slate-200/80 py-4 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        
        <!-- Tombol Back + Banner Judul Halaman -->
        <div class="flex items-center space-x-3">
            <button @click="activeTab = 'home'" class="p-2.5 rounded-xl bg-white hover:bg-slate-200 border border-slate-200 text-slate-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </button>

            <div class="bg-slate-300/80 text-slate-800 font-outfit font-extrabold px-6 py-2 rounded-2xl text-base tracking-widest uppercase shadow-inner">
                <span x-text="activeTab === 'home' ? 'HOME' : (activeTab === 'grafik' ? 'GRAFIK ARUS' : (activeTab === 'log' ? 'LOG REPORT' : 'PENJADWALAN AC'))">HOME</span>
            </div>
        </div>

        <!-- Tombol Tambah Ruangan Baru Kanan -->
        <div class="flex items-center space-x-3">
            <button class="p-2.5 rounded-xl bg-white hover:bg-slate-200 border border-slate-200 text-slate-600 transition-colors shadow-sm hidden sm:block">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            </button>

            <button onclick="alert('Fitur Tambah Ruangan Baru akan segera tersedia!')" 
                    class="bg-slate-300 hover:bg-slate-400 text-slate-800 font-extrabold px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shadow-sm flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span>+ Membuat Ruangan Baru</span>
            </button>
        </div>

    </div>
</section>
