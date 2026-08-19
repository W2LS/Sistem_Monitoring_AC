<!DOCTYPE html>
<html lang="id" :class="currentTheme === 'cli' ? 'dark' : ''" x-data="{ 
    activeTab: 'dashboard', 
    modalJadwalOpen: false, 
    currentTheme: localStorage.getItem('pindad_theme') || 'gui',
    toggleTheme() {
        this.currentTheme = this.currentTheme === 'gui' ? 'cli' : 'gui';
        localStorage.setItem('pindad_theme', this.currentTheme);
        if (window.onThemeChange) {
            window.onThemeChange(this.currentTheme);
        }
    }
}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PT PINDAD - Industrial IoT AC Dashboard</title>
    
    <!-- Google Fonts: Inter, Outfit, JetBrains Mono, VT323 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;600;700&family=JetBrains+Mono:wght@300;400;500;700;800&family=VT323&family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                        mono: ['"JetBrains Mono"', '"Fira Code"', 'monospace'],
                        vt: ['VT323', 'monospace'],
                    },
                    colors: {
                        term: {
                            bg: '#0a0a0a',
                            pane: '#050505',
                            card: '#0f140f',
                            green: '#33ff00',
                            greendim: '#1f521f',
                            amber: '#ffb000',
                            red: '#ff3333',
                            cyan: '#00e5ff',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN for reactive tab navigation & modals -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Scanlines Overlay for Terminal Mode */
        .scanlines-overlay {
            background: linear-gradient(
                rgba(18, 16, 16, 0) 50%, 
                rgba(0, 0, 0, 0.45) 50%
            ), linear-gradient(
                90deg,
                rgba(51, 255, 0, 0.02),
                rgba(0, 255, 0, 0.005),
                rgba(0, 229, 255, 0.02)
            );
            background-size: 100% 3px, 6px 100%;
            pointer-events: none;
        }

        /* Phosphor CRT Glow */
        .cli-glow {
            text-shadow: 0 0 7px rgba(51, 255, 0, 0.7);
        }
        .cli-amber-glow {
            text-shadow: 0 0 7px rgba(255, 176, 0, 0.7);
        }
        .cli-red-glow {
            text-shadow: 0 0 7px rgba(255, 51, 51, 0.7);
        }
        .cli-cyan-glow {
            text-shadow: 0 0 7px rgba(0, 229, 255, 0.7);
        }

        /* Terminal Cursor Blink */
        .cli-blink {
            animation: cursor-blink 1s step-end infinite;
        }
        @keyframes cursor-blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        /* Inverted Video Button on Hover */
        .cli-btn-invert:hover {
            background-color: #33ff00 !important;
            color: #0a0a0a !important;
            box-shadow: 0 0 10px rgba(51, 255, 0, 0.8);
        }
        .cli-btn-invert-amber:hover {
            background-color: #ffb000 !important;
            color: #0a0a0a !important;
            box-shadow: 0 0 10px rgba(255, 176, 0, 0.8);
        }

        /* Terminal Scrollbar */
        .cli-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .cli-scroll::-webkit-scrollbar-track {
            background: #0a0a0a;
            border-left: 1px solid #1f521f;
        }
        .cli-scroll::-webkit-scrollbar-thumb {
            background: #1f521f;
        }
        .cli-scroll::-webkit-scrollbar-thumb:hover {
            background: #33ff00;
        }
    </style>
</head>
<body 
    :class="currentTheme === 'cli' ? 'bg-[#0a0a0a] text-[#33ff00] font-mono' : 'bg-slate-50 text-slate-800 font-sans'"
    class="h-screen w-screen overflow-hidden flex antialiased transition-colors duration-300 relative">

    <!-- CRT SCANLINES (ACTIVE ONLY IN CLI MODE) -->
    <div x-show="currentTheme === 'cli'" class="scanlines-overlay fixed inset-0 z-50 pointer-events-none" x-cloak></div>

    <!-- 1. LEFT VERTICAL SIDEBAR -->
    @include('partials.sidebar')

    <!-- 2. RIGHT MAIN CONTENT AREA (FIXED WRAPPER) -->
    <div class="flex-grow flex flex-col min-w-0 h-screen overflow-hidden"
         :class="currentTheme === 'cli' ? 'bg-[#0a0a0a]' : 'bg-slate-50'">
        
        <!-- TOP HEADER BAR (FIXED AT TOP) -->
        @include('partials.menu-atas')

        <!-- MAIN DYNAMIC CONTENT CONTAINER (ONLY THIS SCROLLS!) -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-8"
              :class="currentTheme === 'cli' ? 'cli-scroll' : ''">
            
            <!-- ALERTS FEEDBACK NOTIFICATION -->
            @if(session('success'))
                <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#33ff00] text-[#33ff00] rounded-none cli-glow' : 'bg-teal-50 border border-teal-200 text-teal-700 rounded-2xl shadow-sm'" 
                     class="px-4 py-3.5 flex items-center justify-between" role="alert">
                    <span class="text-sm font-bold flex items-center space-x-2">
                        <span x-text="currentTheme === 'cli' ? '[OK]' : '✅'"></span>
                        <span>{{ session('success') }}</span>
                    </span>
                    <button onclick="this.parentElement.remove()" 
                            :class="currentTheme === 'cli' ? 'text-[#33ff00] hover:bg-[#33ff00] hover:text-[#0a0a0a] px-2' : 'text-teal-500 hover:text-teal-700'"
                            class="font-bold text-lg">&times;</button>
                </div>
            @endif

            <!-- TAB 1: DASHBOARD (RINGKASAN SISTEM) -->
            <div x-show="activeTab === 'dashboard'" x-cloak class="space-y-8">
                
                <!-- PAGE TITLE & SUMMARY WIDGET -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <!-- Title GUI vs CLI -->
                        <template x-if="currentTheme === 'gui'">
                            <div>
                                <h1 class="font-outfit font-black text-2xl lg:text-3xl text-slate-800 tracking-tight">Dashboard</h1>
                                <p class="text-xs font-semibold text-slate-500 mt-1">Ringkasan sistem monitoring dan kontrol AC</p>
                            </div>
                        </template>
                        <template x-if="currentTheme === 'cli'">
                            <div>
                                <h1 class="font-mono font-black text-xl lg:text-2xl text-[#33ff00] cli-glow tracking-wider uppercase">
                                    &gt; SYS_DASHBOARD://MAINFRAME_V1.0 <span class="cli-blink">█</span>
                                </h1>
                                <p class="text-xs font-mono text-[#1f521f] text-emerald-600 mt-1 font-bold">
                                    // PINDAD_AC_CORE_SUBSYSTEM :: DUAL_REDUNDANT_CHANNEL_ACTIVE
                                </p>
                            </div>
                        </template>
                    </div>

                    <!-- Status Widget Badge (Live Telemetry & ESP32 State) -->
                    <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] text-[#33ff00] rounded-none' : 'bg-white border border-slate-200 rounded-2xl shadow-sm text-slate-800'"
                         class="px-4 py-2.5 flex items-center space-x-3">
                        <div :class="currentTheme === 'cli' ? 'border border-[#33ff00] text-[#33ff00] bg-transparent rounded-none' : 'w-8 h-8 rounded-xl bg-teal-500/10 text-teal-600'"
                             class="w-8 h-8 flex items-center justify-center font-black">
                            <span x-text="currentTheme === 'cli' ? 'SYS' : '🎛️'"></span>
                        </div>
                        <div>
                            <div :class="currentTheme === 'cli' ? 'text-xs font-mono font-black uppercase text-[#33ff00]' : 'text-xs font-black text-slate-800'">
                                <span id="kpi-total-current">0.00 A</span> • <span id="kpi-total-watt">0 W</span>
                            </div>
                            <div :class="currentTheme === 'cli' ? 'text-[10px] font-mono text-[#33ff00] font-bold cli-glow' : 'text-[10px] font-bold text-emerald-600'"
                                 class="flex items-center space-x-1">
                                <span id="esp32-status-dot" :class="currentTheme === 'cli' ? 'w-1.5 h-1.5 bg-[#33ff00] animate-ping' : 'w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse'"></span>
                                <span id="esp32-status-text">ESP32 Online & Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION A: KARTU KONTROL & MONITORING AC 1 DAN AC 2 -->
                <section class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="kontrol-ac">
                        @include('partials.kartu-ac', ['id' => 1, 'pin' => 18, 'name' => 'PANASONIC 1', 'color' => 'teal', 'schedules' => $schedules])
                        @include('partials.kartu-ac', ['id' => 2, 'pin' => 19, 'name' => 'PANASONIC 2', 'color' => 'cyan', 'schedules' => $schedules])
                    </div>
                </section>

                <!-- SECTION B: GRAFIK TREN ARUS LISTRIK (AMPERE REAL-TIME) -->
                <section id="grafik-telemetri">
                    @include('partials.grafik-arus')
                </section>

                <!-- SECTION C: PENJADWALAN ON/OFF AC TABLE -->
                <section id="ringkasan-penjadwalan">
                    @include('partials.section-penjadwalan')
                </section>

            </div>

            <!-- TAB 2: UNIT AC (STATUS & KONTROL MANUAL) -->
            <div x-show="activeTab === 'unit-ac'" x-cloak>
                @include('partials.section-unit-ac')
            </div>

            <!-- TAB 3: GRAFIK ARUS (MONITORING ARUS) -->
            <div x-show="activeTab === 'grafik-arus'" x-cloak>
                @include('partials.section-grafik-arus')
            </div>

            <!-- TAB 4: PENJADWALAN (JADWAL ON/OFF) -->
            <div x-show="activeTab === 'penjadwalan'" x-cloak>
                @include('partials.section-penjadwalan')
            </div>

            <!-- TAB 5: RIWAYAT (LOG & AKTIVITAS) -->
            <div x-show="activeTab === 'riwayat'" x-cloak>
                @include('partials.section-riwayat')
            </div>

            <!-- TAB 6: PENGATURAN (KONFIGURASI SISTEM) -->
            <div x-show="activeTab === 'pengaturan'" x-cloak>
                @include('partials.section-pengaturan')
            </div>

        </main>

        <!-- FOOTER BAWAH -->
        @include('partials.footer-bawah')

    </div>

    <!-- SCRIPTS SECTION -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // State locks to prevent race conditions during polling
        const controlLocks = {
            1: { targetState: null, lastToggled: 0 },
            2: { targetState: null, lastToggled: 0 }
        };
        const LOCK_TIMEOUT_MS = 25000;

        // Function triggered when iOS-style / CLI toggles are clicked
        function sendAcControlViaSwitch(relayNum, checkboxEl) {
            const command = checkboxEl.checked ? 'ON' : 'OFF';
            console.log(`Sending command: Relay ${relayNum} -> ${command}`);
            
            controlLocks[relayNum].targetState = checkboxEl.checked;
            controlLocks[relayNum].lastToggled = Date.now();
            
            const switchText = document.getElementById(`ac${relayNum}-switch-text`);
            if (switchText) {
                switchText.innerText = (localStorage.getItem('pindad_theme') === 'cli')
                    ? `[ RELAY: ${command} ]`
                    : command;
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

        // Global Chart Instance References for Dynamic Theme Recoloring
        let mainChartInstance = null;
        let detailChartInstance = null;

        function getChartPalette(theme) {
            if (theme === 'cli') {
                return {
                    ac1Border: '#33ff00',
                    ac1Bg: 'rgba(51, 255, 0, 0.1)',
                    ac2Border: '#ffb000',
                    ac2Bg: 'rgba(255, 176, 0, 0.1)',
                    gridColor: 'rgba(31, 82, 31, 0.4)',
                    textColor: '#33ff00',
                    fontFamily: '"JetBrains Mono", monospace'
                };
            }
            return {
                ac1Border: '#0D9488',
                ac1Bg: 'rgba(13, 148, 136, 0.05)',
                ac2Border: '#0EA5E9',
                ac2Bg: 'rgba(14, 165, 233, 0.05)',
                gridColor: 'rgba(226, 232, 240, 0.6)',
                textColor: '#64748B',
                fontFamily: 'Inter, sans-serif'
            };
        }

        function applyChartTheme(chart, theme) {
            if (!chart) return;
            const p = getChartPalette(theme);
            
            if (chart.data.datasets[0]) {
                chart.data.datasets[0].borderColor = p.ac1Border;
                chart.data.datasets[0].backgroundColor = p.ac1Bg;
            }
            if (chart.data.datasets[1]) {
                chart.data.datasets[1].borderColor = p.ac2Border;
                chart.data.datasets[1].backgroundColor = p.ac2Bg;
            }
            if (chart.options.scales.x) {
                chart.options.scales.x.grid.color = p.gridColor;
                chart.options.scales.x.ticks.color = p.textColor;
                chart.options.scales.x.ticks.font = { family: p.fontFamily, size: 10 };
            }
            if (chart.options.scales.y) {
                chart.options.scales.y.grid.color = p.gridColor;
                chart.options.scales.y.ticks.color = p.textColor;
                chart.options.scales.y.ticks.font = { family: p.fontFamily, size: 10 };
            }
            if (chart.options.plugins.legend) {
                chart.options.plugins.legend.labels.color = p.textColor;
                chart.options.plugins.legend.labels.font = { family: p.fontFamily, weight: 600, size: 12 };
            }
            chart.update();
        }

        window.onThemeChange = function(theme) {
            applyChartTheme(mainChartInstance, theme);
            applyChartTheme(detailChartInstance, theme);
        };

        // --- Real-time AJAX Polling Engine (Every 3 Seconds) ---
        function pollTelemetryData() {
            fetch("{{ route('api.logs') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'success') return;

                    const now = Date.now();
                    const isCli = (localStorage.getItem('pindad_theme') === 'cli');

                    // 1. Update KPI Widgets
                    const kpiCurrent = document.getElementById('kpi-total-current');
                    const kpiWatt = document.getElementById('kpi-total-watt');
                    if (kpiCurrent) kpiCurrent.innerText = `${data.total_current.toFixed(2)} A`;
                    if (kpiWatt) kpiWatt.innerText = `${data.estimated_watt} W`;

                    // 2. Update ESP32 Status Badge
                    const esp32Dot = document.getElementById('esp32-status-dot');
                    const esp32Text = document.getElementById('esp32-status-text');
                    if (esp32Dot && esp32Text) {
                        if (data.device_online) {
                            esp32Dot.className = isCli ? "w-1.5 h-1.5 bg-[#33ff00] animate-ping" : "w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse";
                            esp32Text.innerText = isCli ? "[ ESP32 : ONLINE ]" : "ESP32 Online & Aktif";
                        } else {
                            esp32Dot.className = isCli ? "w-1.5 h-1.5 bg-[#ffb000]" : "w-1.5 h-1.5 rounded-full bg-amber-400";
                            esp32Text.innerText = isCli ? "[ ESP32 : STANDBY ]" : "ESP32 Standby";
                        }
                    }

                    // 3. Update AC 1 Card
                    if (data.latest_ac1) {
                        const ac1Cur = document.getElementById('ac1-current');
                        const ac1Time = document.getElementById('ac1-time');
                        const ac1Badge = document.getElementById('ac1-badge-label');
                        const ac1Switch = document.getElementById('ac1-switch');
                        const ac1SwitchText = document.getElementById('ac1-switch-text');
                        const tab2Ac1Cur = document.getElementById('tab2-ac1-current');
                        const tab2Ac1Stat = document.getElementById('tab2-ac1-status');

                        if (ac1Cur) ac1Cur.innerText = data.latest_ac1.current_ampere.toFixed(4);
                        if (ac1Time) ac1Time.innerText = data.latest_ac1.recorded_at;
                        if (tab2Ac1Cur) tab2Ac1Cur.innerText = `${data.latest_ac1.current_ampere.toFixed(4)} Ampere`;

                        if (ac1Badge) {
                            ac1Badge.innerText = data.latest_ac1.is_on ? "Aktif ON" : "Standby OFF";
                        }
                        if (tab2Ac1Stat) {
                            tab2Ac1Stat.innerText = data.latest_ac1.is_on ? "ACTIVE (ON)" : "STANDBY (OFF)";
                        }

                        // Sync switch state if not actively locked
                        const isLocked1 = controlLocks[1].targetState !== null && (now - controlLocks[1].lastToggled < LOCK_TIMEOUT_MS);
                        if (ac1Switch && !isLocked1) {
                            ac1Switch.checked = data.latest_ac1.is_on;
                            if (ac1SwitchText) {
                                ac1SwitchText.innerText = isCli 
                                    ? `[ RELAY: ${data.latest_ac1.is_on ? 'ON' : 'OFF'} ]` 
                                    : (data.latest_ac1.is_on ? 'ON' : 'OFF');
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
                        const tab2Ac2Cur = document.getElementById('tab2-ac2-current');
                        const tab2Ac2Stat = document.getElementById('tab2-ac2-status');

                        if (ac2Cur) ac2Cur.innerText = data.latest_ac2.current_ampere.toFixed(4);
                        if (ac2Time) ac2Time.innerText = data.latest_ac2.recorded_at;
                        if (tab2Ac2Cur) tab2Ac2Cur.innerText = `${data.latest_ac2.current_ampere.toFixed(4)} Ampere`;

                        if (ac2Badge) {
                            ac2Badge.innerText = data.latest_ac2.is_on ? "Aktif ON" : "Standby OFF";
                        }
                        if (tab2Ac2Stat) {
                            tab2Ac2Stat.innerText = data.latest_ac2.is_on ? "ACTIVE (ON)" : "STANDBY (OFF)";
                        }

                        // Sync switch state if not actively locked
                        const isLocked2 = controlLocks[2].targetState !== null && (now - controlLocks[2].lastToggled < LOCK_TIMEOUT_MS);
                        if (ac2Switch && !isLocked2) {
                            ac2Switch.checked = data.latest_ac2.is_on;
                            if (ac2SwitchText) {
                                ac2SwitchText.innerText = isCli 
                                    ? `[ RELAY: ${data.latest_ac2.is_on ? 'ON' : 'OFF'} ]` 
                                    : (data.latest_ac2.is_on ? 'ON' : 'OFF');
                            }
                        } else if (controlLocks[2].targetState !== null && data.latest_ac2.is_on === controlLocks[2].targetState) {
                            controlLocks[2].targetState = null;
                        }
                    }

                    // 5. Update Chart Telemetry
                    if (mainChartInstance && data.chart && data.chart.labels && data.chart.labels.length > 0) {
                        mainChartInstance.data.labels = data.chart.labels;
                        mainChartInstance.data.datasets[0].data = data.chart.ac1;
                        mainChartInstance.data.datasets[1].data = data.chart.ac2;
                        mainChartInstance.update('none'); // Update without flickering
                    }
                })
                .catch(err => console.error("Telemetry polling error:", err));
        }

        // --- Real-time Chart.js Setup ---
        document.addEventListener("DOMContentLoaded", function() {
            const currentTheme = localStorage.getItem('pindad_theme') || 'gui';
            const p = getChartPalette(currentTheme);

            const ctx1 = document.getElementById('currentChart');
            if (ctx1) {
                mainChartInstance = new Chart(ctx1.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['--:--:--', '--:--:--', '--:--:--', '--:--:--', '--:--:--'],
                        datasets: [
                            {
                                label: 'PANASONIC 1 (AC 1)',
                                borderColor: p.ac1Border,
                                backgroundColor: p.ac1Bg,
                                borderWidth: 3,
                                pointRadius: 3,
                                tension: 0.4,
                                fill: true,
                                data: [0, 0, 0, 0, 0]
                            },
                            {
                                label: 'PANASONIC 2 (AC 2)',
                                borderColor: p.ac2Border,
                                backgroundColor: p.ac2Bg,
                                borderWidth: 3,
                                pointRadius: 3,
                                tension: 0.4,
                                fill: true,
                                data: [0, 0, 0, 0, 0]
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: { color: p.textColor, font: { family: p.fontFamily, weight: 600, size: 12 } }
                            }
                        },
                        scales: {
                            x: { grid: { color: p.gridColor }, ticks: { color: p.textColor, font: { family: p.fontFamily, size: 10 } } },
                            y: { grid: { color: p.gridColor }, ticks: { color: p.textColor }, title: { display: true, text: 'Arus (Ampere)', color: p.textColor, font: { weight: 'bold', size: 12 } } }
                        }
                    }
                });
            }

            const ctx2 = document.getElementById('chart-analisis-detail');
            if (ctx2) {
                detailChartInstance = new Chart(ctx2.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00'],
                        datasets: [
                            {
                                label: 'Panasonic 1 (Ampere)',
                                borderColor: p.ac1Border,
                                backgroundColor: p.ac1Bg,
                                borderWidth: 3,
                                tension: 0.3,
                                fill: true,
                                data: [0.12, 0.10, 0.45, 0.52, 0.55, 0.48, 0.15, 0.12]
                            },
                            {
                                label: 'Panasonic 2 (Ampere)',
                                borderColor: p.ac2Border,
                                backgroundColor: p.ac2Bg,
                                borderWidth: 3,
                                tension: 0.3,
                                fill: true,
                                data: [0.55, 0.60, 0.15, 0.12, 0.18, 0.58, 0.62, 0.50]
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { labels: { color: p.textColor, font: { family: p.fontFamily, weight: 600, size: 12 } } }
                        },
                        scales: {
                            x: { grid: { color: p.gridColor }, ticks: { color: p.textColor, font: { family: p.fontFamily, size: 10 } } },
                            y: { grid: { color: p.gridColor }, ticks: { color: p.textColor } }
                        }
                    }
                });
            }

            // Start first immediate poll and then recurring every 30 seconds (Synchronized with ESP32 interval)
            pollTelemetryData();
            setInterval(pollTelemetryData, 30000);
        });

        // Real-time server clock update
        function updateServerClock() {
            const clockEl = document.getElementById('server-clock');
            if (clockEl) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                clockEl.innerText = `${hours}:${minutes}:${seconds} WIB`;
            }
        }
        setInterval(updateServerClock, 1000);
        updateServerClock();
    </script>
</body>
</html>
