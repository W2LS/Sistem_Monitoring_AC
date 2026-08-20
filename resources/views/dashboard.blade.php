<!DOCTYPE html>
<html lang="id" x-data="{ 
    activeTab: 'dashboard', 
    modalJadwalOpen: false 
}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PT PINDAD - Industrial IoT AC Dashboard</title>
    
    <!-- Google Fonts: Inter, Outfit, JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        pindad: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            900: '#134e4a',
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
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans h-screen w-screen overflow-hidden flex antialiased">

    <!-- 1. LEFT VERTICAL SIDEBAR -->
    @include('partials.sidebar')

    <!-- 2. RIGHT MAIN CONTENT AREA (FIXED WRAPPER) -->
    <div class="flex-grow flex flex-col min-w-0 h-screen overflow-hidden bg-slate-50">
        
        <!-- TOP HEADER BAR (FIXED AT TOP) -->
        @include('partials.menu-atas')

        <!-- MAIN DYNAMIC CONTENT CONTAINER (ONLY THIS SCROLLS!) -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-8">
            
            <!-- ALERTS FEEDBACK NOTIFICATION -->
            @if(session('success'))
                <div class="bg-teal-50 border border-teal-200 text-teal-700 rounded-2xl shadow-sm px-4 py-3.5 flex items-center justify-between" role="alert">
                    <span class="text-sm font-bold flex items-center space-x-2">
                        <span>✅</span>
                        <span>{{ session('success') }}</span>
                    </span>
                    <button onclick="this.parentElement.remove()" class="text-teal-500 hover:text-teal-700 font-bold text-lg cursor-pointer">&times;</button>
                </div>
            @endif

            <!-- TAB 1: DASHBOARD (RINGKASAN SISTEM) -->
            <div x-show="activeTab === 'dashboard'" x-cloak class="space-y-8">
                
                <!-- PAGE TITLE & SUMMARY WIDGET -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="font-outfit font-black text-2xl lg:text-3xl text-slate-800 tracking-tight">Dashboard Monitoring</h1>
                        <p class="text-xs font-semibold text-slate-500 mt-1">Ringkasan operasional dan kontrol AC Ruang Server 1</p>
                    </div>

                    <!-- Status Widget Badge (Live Telemetry & ESP32 State) -->
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm text-slate-800 px-4 py-2.5 flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-teal-500/10 text-teal-600 flex items-center justify-center font-black">
                            🎛️
                        </div>
                        <div>
                            <div class="text-xs font-black text-slate-800">
                                <span id="kpi-total-current">0.00 A</span> • <span id="kpi-total-watt">0 W</span>
                            </div>
                            <div class="text-[10px] font-bold text-emerald-600 flex items-center space-x-1">
                                <span id="esp32-status-dot" class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
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

        // Function triggered when iOS-style toggles are clicked
        function sendAcControlViaSwitch(relayNum, checkboxEl) {
            const command = checkboxEl.checked ? 'ON' : 'OFF';
            console.log(`Sending command: Relay ${relayNum} -> ${command}`);
            
            controlLocks[relayNum].targetState = checkboxEl.checked;
            controlLocks[relayNum].lastToggled = Date.now();
            
            const switchText = document.getElementById(`ac${relayNum}-switch-text`);
            if (switchText) {
                switchText.innerText = command;
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

        // Global Chart Instance References
        let mainChartInstance = null;
        let detailChartInstance = null;

        // --- Real-time AJAX Polling Engine (Every 30 Seconds / Instant Trigger) ---
        function pollTelemetryData() {
            fetch("{{ route('api.logs') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'success') return;

                    const now = Date.now();

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
                            esp32Dot.className = "w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse";
                            esp32Text.innerText = "ESP32 Online & Aktif";
                        } else {
                            esp32Dot.className = "w-1.5 h-1.5 rounded-full bg-amber-400";
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
                                ac1SwitchText.innerText = data.latest_ac1.is_on ? 'ON' : 'OFF';
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
                                ac2SwitchText.innerText = data.latest_ac2.is_on ? 'ON' : 'OFF';
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
            const ctx1 = document.getElementById('currentChart');
            if (ctx1) {
                mainChartInstance = new Chart(ctx1.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['--:--:--', '--:--:--', '--:--:--', '--:--:--', '--:--:--'],
                        datasets: [
                            {
                                label: 'PANASONIC 1 (AC 1)',
                                borderColor: '#0D9488',
                                backgroundColor: 'rgba(13, 148, 136, 0.08)',
                                borderWidth: 3,
                                pointRadius: 3,
                                tension: 0.4,
                                fill: true,
                                data: [0, 0, 0, 0, 0]
                            },
                            {
                                label: 'PANASONIC 2 (AC 2)',
                                borderColor: '#0EA5E9',
                                backgroundColor: 'rgba(14, 165, 233, 0.08)',
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
                                labels: { color: '#475569', font: { family: 'Inter, sans-serif', weight: 600, size: 12 } }
                            }
                        },
                        scales: {
                            x: { 
                                grid: { color: 'rgba(226, 232, 240, 0.7)' }, 
                                ticks: { color: '#64748B', font: { family: '"JetBrains Mono", monospace', size: 10 } } 
                            },
                            y: { 
                                grid: { color: 'rgba(226, 232, 240, 0.7)' }, 
                                ticks: { color: '#64748B' }, 
                                title: { display: true, text: 'Arus (Ampere)', color: '#475569', font: { weight: 'bold', size: 12 } } 
                            }
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
                                borderColor: '#0D9488',
                                backgroundColor: 'rgba(13, 148, 136, 0.08)',
                                borderWidth: 3,
                                tension: 0.3,
                                fill: true,
                                data: [0.12, 0.10, 0.45, 0.52, 0.55, 0.48, 0.15, 0.12]
                            },
                            {
                                label: 'Panasonic 2 (Ampere)',
                                borderColor: '#0EA5E9',
                                backgroundColor: 'rgba(14, 165, 233, 0.08)',
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
                            legend: { labels: { color: '#475569', font: { family: 'Inter, sans-serif', weight: 600, size: 12 } } }
                        },
                        scales: {
                            x: { grid: { color: 'rgba(226, 232, 240, 0.7)' }, ticks: { color: '#64748B', font: { family: '"JetBrains Mono", monospace' } } },
                            y: { grid: { color: 'rgba(226, 232, 240, 0.7)' }, ticks: { color: '#64748B' } }
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
