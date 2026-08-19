<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PT PINDAD - Industrial IoT AC Dashboard</title>
    
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
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
            background-color: #F8FAFC;
        }
    </style>
</head>
<body x-data="{ activeTab: 'dashboard', modalJadwalOpen: false }" class="text-slate-800 bg-slate-50 min-h-screen flex antialiased">

    <!-- 1. LEFT VERTICAL DARK SIDEBAR -->
    @include('partials.sidebar')

    <!-- 2. RIGHT MAIN CONTENT AREA -->
    <div class="flex-grow flex flex-col min-w-0 min-h-screen">
        
        <!-- TOP HEADER BAR -->
        @include('partials.menu-atas')

        <!-- MAIN DYNAMIC CONTENT CONTAINER -->
        <main class="flex-grow p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-8">
            
            <!-- ALERTS FEEDBACK NOTIFICATION -->
            @if(session('success'))
                <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3.5 rounded-2xl flex items-center justify-between shadow-sm" role="alert">
                    <span class="text-sm font-bold flex items-center space-x-2">
                        <span>✅</span>
                        <span>{{ session('success') }}</span>
                    </span>
                    <button onclick="this.parentElement.remove()" class="text-teal-500 hover:text-teal-700 font-bold text-lg">&times;</button>
                </div>
            @endif

            <!-- TAB 1: DASHBOARD (RINGKASAN SISTEM) -->
            <div x-show="activeTab === 'dashboard'" x-cloak class="space-y-8">
                
                <!-- PAGE TITLE & SUMMARY WIDGET -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="font-outfit font-black text-2xl lg:text-3xl text-slate-800 tracking-tight">Dashboard</h1>
                        <p class="text-xs font-semibold text-slate-500 mt-1">Ringkasan sistem monitoring dan kontrol AC</p>
                    </div>

                    <div class="bg-white border border-slate-200 px-4 py-2.5 rounded-2xl shadow-sm flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-teal-500/10 text-teal-600 flex items-center justify-center font-black">
                            🎛️
                        </div>
                        <div>
                            <div class="text-xs font-black text-slate-800">2 Unit Terhubung</div>
                            <div class="text-[10px] font-bold text-emerald-600 flex items-center space-x-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Semua sistem normal</span>
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
        const LOCK_TIMEOUT_MS = 35000;

        // Function triggered when iOS-style toggles are clicked
        function sendAcControlViaSwitch(relayNum, checkboxEl) {
            const command = checkboxEl.checked ? 'ON' : 'OFF';
            console.log(`Sending command: Relay ${relayNum} -> ${command}`);
            
            controlLocks[relayNum].targetState = checkboxEl.checked;
            controlLocks[relayNum].lastToggled = Date.now();
            
            const switchText = document.getElementById(`ac${relayNum}-switch-text`);
            if (switchText) {
                switchText.innerText = command;
                switchText.className = command === 'ON'
                    ? "text-xs font-black uppercase tracking-wider text-teal-600"
                    : "text-xs font-black uppercase tracking-wider text-slate-400";
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

        // --- Real-time Chart.js Setup ---
        document.addEventListener("DOMContentLoaded", function() {
            const ctx1 = document.getElementById('currentChart');
            if (ctx1) {
                new Chart(ctx1.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['12:00:00', '12:00:15', '12:00:30', '12:00:45', '12:01:00', '12:01:15', '12:01:30', '12:01:45', '12:02:00'],
                        datasets: [
                            {
                                label: 'PANASONIC 1 (AC 1)',
                                borderColor: '#0D9488',
                                backgroundColor: 'rgba(13, 148, 136, 0.05)',
                                borderWidth: 3,
                                pointRadius: 3,
                                tension: 0.4,
                                fill: true,
                                data: [0.35, 0.48, 0.52, 0.61, 0.45, 0.58, 0.39, 0.42, 0.50]
                            },
                            {
                                label: 'PANASONIC 2 (AC 2)',
                                borderColor: '#0EA5E9',
                                backgroundColor: 'rgba(14, 165, 233, 0.05)',
                                borderWidth: 3,
                                pointRadius: 3,
                                tension: 0.4,
                                fill: true,
                                data: [0.42, 0.38, 0.55, 0.68, 0.72, 0.61, 0.58, 0.62, 0.48]
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: { color: '#334155', font: { family: 'Inter', weight: 600, size: 12 } }
                            }
                        },
                        scales: {
                            x: { grid: { color: 'rgba(226, 232, 240, 0.6)' }, ticks: { color: '#64748B', font: { family: 'mono', size: 10 } } },
                            y: { grid: { color: 'rgba(226, 232, 240, 0.6)' }, ticks: { color: '#64748B' }, title: { display: true, text: 'Arus (Ampere)', color: '#475569', font: { weight: 'bold', size: 12 } } }
                        }
                    }
                });
            }

            const ctx2 = document.getElementById('chart-analisis-detail');
            if (ctx2) {
                new Chart(ctx2.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00'],
                        datasets: [
                            {
                                label: 'Panasonic 1 (Ampere)',
                                borderColor: '#0D9488',
                                backgroundColor: 'rgba(13, 148, 136, 0.1)',
                                borderWidth: 3,
                                tension: 0.3,
                                fill: true,
                                data: [0.12, 0.10, 0.45, 0.52, 0.55, 0.48, 0.15, 0.12]
                            },
                            {
                                label: 'Panasonic 2 (Ampere)',
                                borderColor: '#0EA5E9',
                                backgroundColor: 'rgba(14, 165, 233, 0.1)',
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
                            legend: { labels: { color: '#334155', font: { family: 'Inter', weight: 600, size: 12 } } }
                        },
                        scales: {
                            x: { grid: { color: 'rgba(226, 232, 240, 0.6)' }, ticks: { color: '#64748B', font: { family: 'mono', size: 10 } } },
                            y: { grid: { color: 'rgba(226, 232, 240, 0.6)' }, ticks: { color: '#64748B' } }
                        }
                    }
                });
            }
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
