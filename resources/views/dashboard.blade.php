<!DOCTYPE html>
<html lang="id" x-data="{ 
    activeTab: 'home', 
    modalFabOpen: false 
}">
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
    </style>
</head>
<body class="bg-[#EEEEEE] text-[#1D1616] min-h-screen antialiased flex flex-col justify-between selection:bg-[#D84040] selection:text-white">

    <!-- MAIN RESPONSIVE WRAPPER CONTAINER (Melebar Dinamis di Layar Web / Desktop) -->
    <div class="w-full max-w-6xl xl:max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 xl:px-16 pt-8 sm:pt-12 pb-36 space-y-8">
        
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
            <div @click="activeTab = 'akun'" class="relative cursor-pointer group shrink-0" title="Buka Profil Akun">
                <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-[#1D1616] via-[#8E1616] to-[#D84040] text-white flex items-center justify-center font-black text-xl border-2 border-white shadow-md transition-transform group-hover:scale-105">
                    D
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
                <div class="w-10 h-10 rounded-full bg-[#D84040] text-white flex items-center justify-center font-black text-sm shrink-0 shadow-xs">
                    🏠
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
                <div class="w-10 h-10 rounded-full bg-[#D84040] text-white flex items-center justify-center font-black text-sm shrink-0 shadow-xs">
                    🔍
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
                <div class="w-10 h-10 rounded-full bg-[#D84040] text-white flex items-center justify-center font-black text-sm shrink-0 shadow-xs">
                    📖
                </div>
                <div class="text-left truncate pr-2" x-show="activeTab === 'book'">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-[#EEEEEE]/80 block">MODUL 3</span>
                    <span class="text-xs font-black text-white leading-none block">Log AC</span>
                </div>
            </button>

            <!-- Category 4: User (Akun Admin) -->
            <button 
                @click="activeTab = 'akun'"
                type="button"
                :class="activeTab === 'akun' 
                    ? 'w-48 bg-[#8E1616] text-white shadow-lg shadow-[#8E1616]/25' 
                    : 'w-14 bg-white text-[#1D1616]/60 border border-[#8E1616]/20 hover:border-[#8E1616]'"
                class="h-14 rounded-[22px] p-2 flex items-center space-x-3 shrink-0 transition-all duration-300 cursor-pointer overflow-hidden">
                <div class="w-10 h-10 rounded-full bg-[#D84040] text-white flex items-center justify-center font-black text-sm shrink-0 shadow-xs">
                    👤
                </div>
                <div class="text-left truncate pr-2" x-show="activeTab === 'akun'">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-[#EEEEEE]/80 block">MODUL 4</span>
                    <span class="text-xs font-black text-white leading-none block">Akun Admin</span>
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
                        <span id="esp32-status-text" class="text-xs font-black text-[#1D1616]">ESP32 Online</span>
                    </div>
                </div>

                <!-- KPI SUMMARY HERO WIDGET (Expands majestically on desktop) -->
                <div class="bg-[#1D1616] rounded-[40px] p-7 sm:p-8 text-white shadow-[0_20px_50px_-12px_rgba(29,22,22,0.35)] border border-[#8E1616]/30 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="space-y-1">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#D84040] block">Status Total Beban Daya Server</span>
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

                <!-- DUAL HERO AC CARDS (GRID 2-KOLOM MELEBAR DI DESKTOP / STACKED DI MOBILE) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @include('partials.kartu-ac', [
                        'id' => 1, 
                        'pin' => 18, 
                        'name' => 'PANASONIC 1', 
                        'location' => 'Lampu Panel Bawah', 
                        'shift' => 'Shift Siang (06:00 - 18:00 WIB)', 
                        'color' => 'red', 
                        'schedules' => $schedules
                    ])
                    @include('partials.kartu-ac', [
                        'id' => 2, 
                        'pin' => 19, 
                        'name' => 'PANASONIC 2', 
                        'location' => 'Lampu Panel Atas', 
                        'shift' => 'Shift Malam (18:00 - 06:00 WIB)', 
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
                    <div class="w-12 h-12 rounded-full bg-[#D84040] text-white flex items-center justify-center font-black text-xl shadow-md">
                        💡
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
                        onclick="sendAcControlViaSwitch(1, { checked: true }); sendAcControlViaSwitch(2, { checked: true });" 
                        class="w-full bg-[#1D1616] hover:bg-black text-white p-4 rounded-[20px] text-left flex items-center justify-between font-black text-xs uppercase tracking-wider transition cursor-pointer">
                    <span>❄️ Nyalakan Semua AC (Full Cooling ON)</span>
                    <span class="text-[#D84040] font-bold">➔</span>
                </button>

                <button type="button" 
                        onclick="sendAcControlViaSwitch(1, { checked: false }); sendAcControlViaSwitch(2, { checked: false });" 
                        class="w-full bg-[#D84040] hover:bg-[#8E1616] text-white p-4 rounded-[20px] text-left flex items-center justify-between font-black text-xs uppercase tracking-wider shadow-lg shadow-[#D84040]/30 transition cursor-pointer">
                    <span>🛑 Matikan Semua AC (Shutdown All)</span>
                    <span class="text-white font-bold">➔</span>
                </button>

                <button type="button" 
                        @click="modalFabOpen = false; activeTab = 'search'" 
                        class="w-full bg-[#EEEEEE] hover:bg-slate-200 text-[#1D1616] p-4 rounded-[20px] text-left flex items-center justify-between font-black text-xs uppercase tracking-wider transition cursor-pointer">
                    <span>⏱️ Buka Manajemen Penjadwalan 12 Jam</span>
                    <span class="text-[#8E1616] font-bold">➔</span>
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
            
            fetch("{{ route('ac.control') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    relay: relayNum,
                    command: command
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    console.log(`MQTT Command for AC ${relayNum} sent successfully!`);
                    // Immediate poll to refresh telemetry right after command execution
                    setTimeout(pollTelemetryData, 1500);
                } else {
                    alert('Gagal mengirim perintah: ' + data.message);
                    controlLocks[relayNum].targetState = null;
                    checkboxEl.checked = !checkboxEl.checked;
                }
            })
            .catch(error => {
                console.error("Error sending control:", error);
                alert("Kesalahan koneksi mengirim perintah.");
                controlLocks[relayNum].targetState = null;
                checkboxEl.checked = !checkboxEl.checked;
            });
        }

        // --- Real-time AJAX Polling Engine (Every 30 Seconds / Instant Trigger) ---
        function pollTelemetryData() {
            fetch("{{ route('api.logs') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'success') return;

                    const now = Date.now();

                    // 1. Update KPI Hero Widget
                    const kpiCurrent = document.getElementById('kpi-total-current');
                    const kpiWatt = document.getElementById('kpi-total-watt');
                    if (kpiCurrent) kpiCurrent.innerText = `${data.total_current.toFixed(2)} A`;
                    if (kpiWatt) kpiWatt.innerText = `${data.estimated_watt} Watt Estimasi Beban Terpakai`;

                    // 2. Update ESP32 Status Badge
                    const esp32Dot = document.getElementById('esp32-status-dot');
                    const esp32Text = document.getElementById('esp32-status-text');
                    if (esp32Dot && esp32Text) {
                        if (data.device_online) {
                            esp32Dot.className = "w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse";
                            esp32Text.innerText = "ESP32 Online";
                        } else {
                            esp32Dot.className = "w-2.5 h-2.5 rounded-full bg-amber-400";
                            esp32Text.innerText = "ESP32 Standby";
                        }
                    }

                    // 3. Update AC 1 Card
                    if (data.latest_ac1) {
                        const ac1Cur = document.getElementById('ac1-current');
                        const ac1Time = document.getElementById('ac1-time');
                        const ac1Badge = document.getElementById('ac1-badge-label');
                        const ac1Switch = document.getElementById('ac1-switch');
                        const ac1SwitchText = document.getElementById('ac1-switch-text');

                        if (ac1Cur) ac1Cur.innerText = data.latest_ac1.current_ampere.toFixed(4);
                        if (ac1Time) ac1Time.innerText = data.latest_ac1.recorded_at;
                        if (ac1Badge) {
                            ac1Badge.innerText = data.latest_ac1.is_on ? "Aktif ON" : "Standby OFF";
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

                        if (ac2Cur) ac2Cur.innerText = data.latest_ac2.current_ampere.toFixed(4);
                        if (ac2Time) ac2Time.innerText = data.latest_ac2.recorded_at;
                        if (ac2Badge) {
                            ac2Badge.innerText = data.latest_ac2.is_on ? "Aktif ON" : "Standby OFF";
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
