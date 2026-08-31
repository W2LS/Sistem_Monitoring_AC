<!DOCTYPE html>
<html lang="id" x-data="{ 
    activeTab: localStorage.getItem('pindad_active_tab') || 'home', 
    modalFabOpen: false 
}" x-init="$watch('activeTab', val => localStorage.setItem('pindad_active_tab', val))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PT PINDAD - Industrial IoT AC Monitoring & Control</title>
    
    <!-- Google Fonts: Nunito (400-900) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=JetBrains+Mono:wght@500;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        ebony: '#1D1616',
                        maroon: '#8E1616',
                        coralRed: '#D84040',
                        lightGrey: '#EEEEEE',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #EEEEEE;
            color: #1D1616;
        }
        /* Custom smooth scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #EEEEEE;
        }
        ::-webkit-scrollbar-thumb {
            background: #8E1616;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #1D1616;
        }
        /* Animasi Kedap-Kedip Memudar Santai (Smooth Opacity Fade, Warna Asli & Tanpa Berubah Ukuran) */
        @keyframes smoothFadePulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.35;
            }
        }
        .live-current-active {
            animation: smoothFadePulse 2.2s ease-in-out infinite;
            display: inline-block;
            color: #1D1616 !important;
            will-change: opacity;
        }
    </style>
</head>
<body class="bg-[#EEEEEE] text-[#1D1616] min-h-screen antialiased flex flex-col justify-between selection:bg-[#D84040] selection:text-white">

    <!-- MAIN RESPONSIVE WRAPPER CONTAINER (Proporsional & Nyaman di Layar Desktop) -->
    <div class="w-full max-w-5xl lg:max-w-6xl mx-auto px-6 sm:px-10 lg:px-12 pt-6 sm:pt-8 pb-48 space-y-7">
        
        <!-- ================= TOP HEADER (Matching Reference Header Layout) ================= -->
        <header class="flex items-center justify-between pt-2">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#8E1616] flex items-center space-x-1.5">
                    <span>☀️</span>
                    <span>SISTEM KONTROL & MONITORING AC IOT</span>
                </span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#1D1616] tracking-tight mt-0.5">
                    PT PINDAD <span class="text-sm font-extrabold text-[#D84040] bg-rose-50 px-3 py-1 rounded-full border border-rose-200 align-middle ml-1">(PERSERO)</span>
                </h1>
                <p class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">
                    Ruang Server 1 • Divisi Sistem Informasi & Fasilitas Gedung
                </p>
            </div>

            <!-- Right Profile Avatar with Red Notification Badge -->
            <div @click="activeTab = 'akun'" class="relative cursor-pointer group shrink-0" title="Buka Informasi Sistem">
                <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-[#1D1616] via-[#8E1616] to-[#D84040] text-white flex items-center justify-center font-black text-xl border-2 border-white shadow-md transition-transform group-hover:scale-105">
                    ⚙️
                </div>
                <!-- 16px Coral Red Notification Badge -->
                <span class="absolute -bottom-0.5 -right-0.5 w-4.5 h-4.5 bg-[#D84040] border-2 border-[#EEEEEE] rounded-full flex items-center justify-center">
                    <span class="w-2 h-2 bg-white rounded-full animate-ping"></span>
                </span>
            </div>
        </header>

        <!-- ================= ALERTS NOTIFICATION ================= -->
        @if(session('success'))
            <div class="bg-white rounded-[24px] border border-[#8E1616]/30 text-[#1D1616] shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] px-6 py-4 flex items-center justify-between" role="alert">
                <span class="text-xs sm:text-sm font-black flex items-center space-x-2.5">
                    <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">✓</span>
                    <span>{{ session('success') }}</span>
                </span>
                <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-[#D84040] font-bold text-2xl cursor-pointer">&times;</button>
            </div>
        @endif

        <!-- ================= HORIZONTAL SNAP SCROLL SELECTOR ================= -->
        <div class="flex items-center space-x-3.5 overflow-x-auto pb-2 pt-1 no-scrollbar">
            
            <!-- Category 1: Home (Dashboard) -->
            <button 
                @click="activeTab = 'home'"
                type="button"
                :class="activeTab === 'home' 
                    ? 'w-48 bg-[#8E1616] text-white shadow-lg shadow-[#8E1616]/25' 
                    : 'w-14 bg-white text-[#1D1616]/60 border border-[#8E1616]/20 hover:border-[#8E1616]'"
                class="h-14 rounded-[22px] p-2 flex items-center space-x-3 shrink-0 transition-all duration-300 cursor-pointer overflow-hidden">
                <div class="w-10 h-10 rounded-full bg-[#D84040] text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div class="text-left truncate pr-2" x-show="activeTab === 'home'">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-[#EEEEEE]/80 block">MODUL 1</span>
                    <span class="text-xs font-black text-white leading-none block">Dashboard</span>
                </div>
            </button>

            <!-- Category 2: Search (Penjadwalan) -->
            <button 
                @click="activeTab = 'search'"
                type="button"
                :class="activeTab === 'search' 
                    ? 'w-48 bg-[#8E1616] text-white shadow-lg shadow-[#8E1616]/25' 
                    : 'w-14 bg-white text-[#1D1616]/60 border border-[#8E1616]/20 hover:border-[#8E1616]'"
                class="h-14 rounded-[22px] p-2 flex items-center space-x-3 shrink-0 transition-all duration-300 cursor-pointer overflow-hidden">
                <div class="w-10 h-10 rounded-full bg-[#D84040] text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-left truncate pr-2" x-show="activeTab === 'search'">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-[#EEEEEE]/80 block">MODUL 2</span>
                    <span class="text-xs font-black text-white leading-none block">Penjadwalan</span>
                </div>
            </button>

            <!-- Category 3: Book (Log AC1 vs AC2) -->
            <button 
                @click="activeTab = 'book'"
                type="button"
                :class="activeTab === 'book' 
                    ? 'w-48 bg-[#8E1616] text-white shadow-lg shadow-[#8E1616]/25' 
                    : 'w-14 bg-white text-[#1D1616]/60 border border-[#8E1616]/20 hover:border-[#8E1616]'"
                class="h-14 rounded-[22px] p-2 flex items-center space-x-3 shrink-0 transition-all duration-300 cursor-pointer overflow-hidden">
                <div class="w-10 h-10 rounded-full bg-[#D84040] text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="text-left truncate pr-2" x-show="activeTab === 'book'">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-[#EEEEEE]/80 block">MODUL 3</span>
                    <span class="text-xs font-black text-[#EEEEEE] leading-none block">Log AC</span>
                </div>
            </button>

            <!-- Category 4: Info Sistem -->
            <button 
                @click="activeTab = 'akun'"
                type="button"
                :class="activeTab === 'akun' 
                    ? 'w-48 bg-[#8E1616] text-white shadow-lg shadow-[#8E1616]/25' 
                    : 'w-14 bg-white text-[#1D1616]/60 border border-[#8E1616]/20 hover:border-[#8E1616]'"
                class="h-14 rounded-[22px] p-2 flex items-center space-x-3 shrink-0 transition-all duration-300 cursor-pointer overflow-hidden">
                <div class="w-10 h-10 rounded-full bg-[#D84040] text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="text-left truncate pr-2" x-show="activeTab === 'akun'">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-[#EEEEEE]/80 block">MODUL 4</span>
                    <span class="text-xs font-black text-white leading-none block">Info Sistem</span>
                </div>
            </button>

            <!-- Category 5: Fleet & Perangkat (Blynk-Style Console) -->
            <button 
                @click="activeTab = 'perangkat'"
                type="button"
                :class="activeTab === 'perangkat' 
                    ? 'w-48 bg-[#8E1616] text-white shadow-lg shadow-[#8E1616]/25' 
                    : 'w-14 bg-white text-[#1D1616]/60 border border-[#8E1616]/20 hover:border-[#8E1616]'"
                class="h-14 rounded-[22px] p-2 flex items-center space-x-3 shrink-0 transition-all duration-300 cursor-pointer overflow-hidden">
                <div class="w-10 h-10 rounded-full bg-[#D84040] text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="text-left truncate pr-2" x-show="activeTab === 'perangkat'">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-[#EEEEEE]/80 block">MODUL 5</span>
                    <span class="text-xs font-black text-white leading-none block">Perangkat IoT</span>
                </div>
            </button>

        </div>

        <!-- ================= MAIN DYNAMIC CONTENT TABS ================= -->
        <main class="space-y-8">
            
            <!-- TAB 1: HOME (DASHBOARD HERO CARDS AC1 & AC2 MELEBAR DI LAYAR DESKTOP) -->
            <div x-show="activeTab === 'home'" x-cloak class="space-y-8">
                
                <!-- SECTION HEADER (Like "Let's try this!" in the reference image) -->
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-[#1D1616] tracking-tight">
                            Kontrol & Telemetri Real-time
                        </h2>
                        <p class="text-xs font-semibold text-slate-500 mt-0.5">
                            Status aktif, pemantauan arus listrik, dan saklar manual individual.
                        </p>
                    </div>

                    <!-- Status Widget Badge -->
                    <div class="hidden sm:flex items-center space-x-3 bg-white border border-[#8E1616]/20 px-4 py-2 rounded-full shadow-2xs">
                        <span id="esp32-status-dot" class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span id="esp32-status-text" class="text-xs font-black text-[#1D1616]">{{ $currentDevice->hardware_type ?? 'Raspberry Pi 3B+' }} Online</span>
                    </div>
                </div>

                <!-- KPI SUMMARY HERO WIDGET (Expands majestically on desktop) -->
                <div class="bg-[#1D1616] rounded-[40px] p-7 sm:p-8 text-white shadow-[0_20px_50px_-12px_rgba(29,22,22,0.35)] border border-[#8E1616]/30 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="space-y-1">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#D84040] block">Status Total Beban Daya: {{ $currentDevice->name ?? 'Ruang Server' }}</span>
                        <div class="flex items-baseline space-x-3">
                            <span id="kpi-total-current" class="text-3xl sm:text-5xl font-black font-mono text-white tracking-tight">0.00 A</span>
                            <span class="text-sm font-extrabold text-[#EEEEEE]/80">Total Konsumsi Arus</span>
                        </div>
                        <span id="kpi-total-watt" class="text-xs sm:text-sm font-bold text-[#D84040] block">0 Watt Estimasi Beban Terpakai</span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-[28px] px-6 py-4 border border-white/10 flex items-center space-x-4 shrink-0">
                        <span class="text-2xl">⚡</span>
                        <div>
                            <span class="text-sm font-black text-white block">Sinkronisasi 30 Detik</span>
                            <span class="text-xs text-[#EEEEEE]/70 font-semibold">Sensor ACS712 + RTC DS3231</span>
                        </div>
                    </div>
                </div>

                <!-- DUAL HERO AC CARDS (1 COLUMN ON MOBILE & IPAD, 2 COLUMNS ON DESKTOP) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                    @include('partials.kartu-ac', [
                        'id' => 1, 
                        'pin' => 17, 
                        'name' => 'PANASONIC 1', 
                        'location' => 'Lampu Panel Bawah', 
                        'shift' => $shiftAc1, 
                        'color' => 'red', 
                        'schedules' => $schedules
                    ])
                    @include('partials.kartu-ac', [
                        'id' => 2, 
                        'pin' => 27, 
                        'name' => 'PANASONIC 2', 
                        'location' => 'Lampu Panel Atas', 
                        'shift' => $shiftAc2, 
                        'color' => 'maroon', 
                        'schedules' => $schedules
                    ])
                </div>

            </div>

            <!-- TAB 2: SEARCH (PUSAT PENJADWALAN AC1 & AC2) -->
            <div x-show="activeTab === 'search'" x-cloak>
                @include('partials.section-penjadwalan')
            </div>

            <!-- TAB 3: BOOK (LOG TELEMETRI TERPISAH AC1 VS AC2 + DOWNLOAD CSV) -->
            <div x-show="activeTab === 'book'" x-cloak>
                @include('partials.section-riwayat')
            </div>

            <!-- TAB 4: AKUN (INFORMASI PROFIL PENGGUNA & SESI) -->
            <div x-show="activeTab === 'akun'" x-cloak>
                @include('partials.section-akun')
            </div>

            <!-- TAB 5: PERANGKAT (MANAJEMEN ARMADA IOT FLEET BLYNK-STYLE) -->
            <div x-show="activeTab === 'perangkat'" x-cloak>
                @include('partials.section-perangkat')
            </div>

        </main>

    </div>

    <!-- ================= FLOATING CENTER ACTION BUTTON MODAL ================= -->
    <div x-show="modalFabOpen" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="modalFabOpen = false">
        
        <div class="bg-white rounded-[40px] text-[#1D1616] border border-[#8E1616]/30 max-w-md w-full p-8 shadow-2xl space-y-6 transform transition-all"
             @click.away="modalFabOpen = false">
            
            <div class="flex justify-between items-center border-b border-[#8E1616]/20 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full bg-[#D84040] text-white flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">Kontrol Terintegrasi</span>
                        <h3 class="text-xl font-black text-[#1D1616]">Aksi Pintar Sistem</h3>
                    </div>
                </div>
                <button @click="modalFabOpen = false" class="text-slate-400 hover:text-[#D84040] font-bold text-2xl cursor-pointer">&times;</button>
            </div>

            <div class="space-y-3">
                <button type="button" 
                        @click="activeTab = 'perangkat'; modalFabOpen = false" 
                        class="w-full bg-slate-100 hover:bg-slate-200 text-[#1D1616] p-4 rounded-[20px] text-left flex items-center justify-between font-black text-xs uppercase tracking-wider transition cursor-pointer active:scale-98 border border-slate-200">
                    <span class="flex items-center gap-2"><span>📡</span><span>Kelola Armada Perangkat IoT (Fleet)</span></span>
                    <span class="text-[#8E1616] font-bold text-base">➔</span>
                </button>

                <button type="button" 
                        @click="smartActionTurnAll(true); modalFabOpen = false" 
                        class="w-full bg-[#1D1616] hover:bg-black text-white p-4 rounded-[20px] text-left flex items-center justify-between font-black text-xs uppercase tracking-wider transition cursor-pointer active:scale-98 shadow-md">
                    <span>❄️ Nyalakan Semua AC (Full Cooling ON)</span>
                    <span class="text-[#D84040] font-bold text-base">➔</span>
                </button>

                <button type="button" 
                        @click="smartActionTurnAll(false); modalFabOpen = false" 
                        class="w-full bg-[#D84040] hover:bg-[#8E1616] text-white p-4 rounded-[20px] text-left flex items-center justify-between font-black text-xs uppercase tracking-wider shadow-lg shadow-[#D84040]/30 transition cursor-pointer active:scale-98">
                    <span>🛑 Matikan Semua AC (Shutdown All)</span>
                    <span class="text-white font-bold text-base">➔</span>
                </button>

                <button type="button" 
                        @click="activeTab = 'search'; modalFabOpen = false" 
                        class="w-full bg-[#EEEEEE] hover:bg-slate-200 text-[#1D1616] p-4 rounded-[20px] text-left flex items-center justify-between font-black text-xs uppercase tracking-wider transition cursor-pointer active:scale-98 border border-[#8E1616]/10">
                    <span>⏱️ Buka Manajemen Penjadwalan 12 Jam</span>
                    <span class="text-[#8E1616] font-bold text-base">➔</span>
                </button>
            </div>

            <button @click="modalFabOpen = false" class="w-full py-3 text-xs font-black uppercase tracking-wider text-slate-400 hover:text-[#D84040] text-center cursor-pointer">
                Tutup Menu
            </button>
        </div>
    </div>

    <!-- ================= FLOATING BOTTOM NAVIGATION BAR ================= -->
    @include('partials.floating-nav')

    <!-- ================= REAL-TIME SCRIPT LOGIC ================= -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // State locks to prevent race conditions during polling
        const controlLocks = {
            1: { targetState: null, lastToggled: 0 },
            2: { targetState: null, lastToggled: 0 }
        };
        const LOCK_TIMEOUT_MS = 25000;

        // Smart Action: Batch control all AC units simultaneously
        function smartActionTurnAll(turnOn) {
            const sw1 = document.getElementById('ac1-switch');
            const sw2 = document.getElementById('ac2-switch');
            
            if (sw1) {
                sw1.checked = turnOn;
                sendAcControlViaSwitch(1, sw1);
            } else {
                sendAcControlViaSwitch(1, { checked: turnOn });
            }
            
            if (sw2) {
                sw2.checked = turnOn;
                sendAcControlViaSwitch(2, sw2);
            } else {
                sendAcControlViaSwitch(2, { checked: turnOn });
            }
        }

        // Function triggered when switch toggles are clicked
        function sendAcControlViaSwitch(relayNum, checkboxEl) {
            const command = checkboxEl.checked ? 'ON' : 'OFF';
            console.log(`Sending command: Relay ${relayNum} -> ${command}`);
            
            controlLocks[relayNum].targetState = checkboxEl.checked;
            controlLocks[relayNum].lastToggled = Date.now();
            
            const switchText = document.getElementById(`ac${relayNum}-switch-text`);
            if (switchText) {
                switchText.innerText = checkboxEl.checked ? 'ON (AKTIF)' : 'OFF (MATI)';
            }

            // INSTANT ZERO-DELAY OPTIMISTIC UPDATE
            const targetCurElem = document.getElementById(`ac${relayNum}-current`);
            const targetBadge = document.getElementById(`ac${relayNum}-badge-label`);
            if (targetBadge) {
                targetBadge.innerText = checkboxEl.checked ? 'Aktif ON' : 'Standby OFF';
            }
            if (targetCurElem) {
                if (checkboxEl.checked) {
                    targetCurElem.innerText = (relayNum === 1 ? '2.1500' : '2.0800');
                    targetCurElem.classList.add('live-current-active');
                } else {
                    targetCurElem.innerText = '0.0000';
                    targetCurElem.classList.remove('live-current-active');
                }
            }
            
            // Recalculate KPI total instantly
            const cur1 = parseFloat(document.getElementById('ac1-current')?.innerText || 0);
            const cur2 = parseFloat(document.getElementById('ac2-current')?.innerText || 0);
            const total = cur1 + cur2;
            const kpiCur = document.getElementById('kpi-total-current');
            const kpiWatt = document.getElementById('kpi-total-watt');
            if (kpiCur) kpiCur.innerText = `${total.toFixed(2)} A`;
            if (kpiWatt) kpiWatt.innerText = `${Math.round(total * 220)} Watt Estimasi Beban Terpakai`;
            
            fetch("{{ route('ac.control') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    relay: relayNum,
                    command: command,
                    device_id: '{{ $selectedDeviceId }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    console.log(`MQTT Command for AC ${relayNum} sent successfully!`);
                    // Immediate fast poll to sync telemetry with MongoDB
                    setTimeout(pollTelemetryData, 400);
                } else {
                    alert('Gagal mengirim perintah: ' + data.message);
                    controlLocks[relayNum].targetState = null;
                    checkboxEl.checked = !checkboxEl.checked;
                    pollTelemetryData();
                }
            })
            .catch(error => {
                console.error("Error sending control:", error);
                alert("Kesalahan koneksi mengirim perintah.");
                controlLocks[relayNum].targetState = null;
                checkboxEl.checked = !checkboxEl.checked;
                pollTelemetryData();
            });
        }

        // --- Real-time AJAX Polling Engine (Every 30 Seconds / Instant Trigger) ---
        function pollTelemetryData() {
            fetch("{{ route('api.logs') }}?device_id={{ $selectedDeviceId }}")
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'success') return;

                    const now = Date.now();

                    // 1. Update KPI Hero Widget (Tetap Putih Solid & Bersih Tanpa Kedip)
                    const kpiCurrent = document.getElementById('kpi-total-current');
                    const kpiWatt = document.getElementById('kpi-total-watt');
                    if (kpiCurrent) {
                        kpiCurrent.innerText = `${data.total_current.toFixed(2)} A`;
                        kpiCurrent.classList.remove('live-current-active');
                    }
                    if (kpiWatt) kpiWatt.innerText = `${data.estimated_watt} Watt Estimasi Beban Terpakai`;

                    // 2. Update ESP32 Status Badge
                    const esp32Dot = document.getElementById('esp32-status-dot');
                    const esp32Text = document.getElementById('esp32-status-text');
                    if (esp32Dot && esp32Text) {
                        if (data.device_online) {
                            esp32Dot.className = "w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse";
                            esp32Text.innerText = "Raspberry Pi 3B+ Online";
                        } else {
                            esp32Dot.className = "w-2.5 h-2.5 rounded-full bg-amber-400";
                            esp32Text.innerText = "Raspberry Pi 3B+ Standby";
                        }
                    }

                    // 3. Update AC 1 Card
                    if (data.latest_ac1) {
                        const ac1Cur = document.getElementById('ac1-current');
                        const ac1Time = document.getElementById('ac1-time');
                        const ac1Badge = document.getElementById('ac1-badge-label');
                        const ac1Switch = document.getElementById('ac1-switch');
                        const ac1SwitchText = document.getElementById('ac1-switch-text');

                        if (ac1Cur) {
                            ac1Cur.innerText = data.latest_ac1.current_ampere.toFixed(4);
                            if (data.latest_ac1.current_ampere > 0.01) {
                                ac1Cur.classList.add('live-current-active');
                            } else {
                                ac1Cur.classList.remove('live-current-active');
                            }
                        }
                        if (ac1Time) ac1Time.innerText = data.latest_ac1.recorded_at;
                        if (ac1Badge) {
                            ac1Badge.innerText = data.latest_ac1.is_on ? "Aktif ON" : "Standby OFF";
                        }
                        const ac1Shift = document.getElementById('ac1-shift-text');
                        if (ac1Shift && data.shift_ac1) {
                            ac1Shift.innerText = data.shift_ac1;
                        }

                        // Sync switch state if not actively locked
                        const isLocked1 = controlLocks[1].targetState !== null && (now - controlLocks[1].lastToggled < LOCK_TIMEOUT_MS);
                        if (ac1Switch && !isLocked1) {
                            ac1Switch.checked = data.latest_ac1.is_on;
                            if (ac1SwitchText) {
                                ac1SwitchText.innerText = data.latest_ac1.is_on ? 'ON (AKTIF)' : 'OFF (MATI)';
                            }
                        } else if (controlLocks[1].targetState !== null && data.latest_ac1.is_on === controlLocks[1].targetState) {
                            controlLocks[1].targetState = null;
                        }
                    }

                    // 4. Update AC 2 Card
                    if (data.latest_ac2) {
                        const ac2Cur = document.getElementById('ac2-current');
                        const ac2Time = document.getElementById('ac2-time');
                        const ac2Badge = document.getElementById('ac2-badge-label');
                        const ac2Switch = document.getElementById('ac2-switch');
                        const ac2SwitchText = document.getElementById('ac2-switch-text');

                        if (ac2Cur) {
                            ac2Cur.innerText = data.latest_ac2.current_ampere.toFixed(4);
                            if (data.latest_ac2.current_ampere > 0.01) {
                                ac2Cur.classList.add('live-current-active');
                            } else {
                                ac2Cur.classList.remove('live-current-active');
                            }
                        }
                        if (ac2Time) ac2Time.innerText = data.latest_ac2.recorded_at;
                        if (ac2Badge) {
                            ac2Badge.innerText = data.latest_ac2.is_on ? "Aktif ON" : "Standby OFF";
                        }
                        const ac2Shift = document.getElementById('ac2-shift-text');
                        if (ac2Shift && data.shift_ac2) {
                            ac2Shift.innerText = data.shift_ac2;
                        }

                        // Sync switch state if not actively locked
                        const isLocked2 = controlLocks[2].targetState !== null && (now - controlLocks[2].lastToggled < LOCK_TIMEOUT_MS);
                        if (ac2Switch && !isLocked2) {
                            ac2Switch.checked = data.latest_ac2.is_on;
                            if (ac2SwitchText) {
                                ac2SwitchText.innerText = data.latest_ac2.is_on ? 'ON (AKTIF)' : 'OFF (MATI)';
                            }
                        } else if (controlLocks[2].targetState !== null && data.latest_ac2.is_on === controlLocks[2].targetState) {
                            controlLocks[2].targetState = null;
                        }
                    }
                })
                .catch(err => console.error("Telemetry polling error:", err));
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Start immediate poll and recurring every 30 seconds
            pollTelemetryData();
            setInterval(pollTelemetryData, 30000);
        });
    </script>
</body>
</html>
