<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PT PINDAD - IoT AC Dashboard</title>
    
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

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC; /* Clean soft light slate background */
        }
    </style>
</head>
<body class="text-slate-800 bg-slate-50 min-h-screen flex flex-col antialiased">

    <!-- Clean Header Bar -->
    @include('partials.menu-atas')

    <!-- Main Container -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 sm:px-6 py-8 space-y-8">
        
        <!-- Alerts Feedback Notification -->
        @if(session('success'))
            <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3.5 rounded-2xl flex items-center justify-between shadow-sm" role="alert">
                <span class="text-sm font-bold flex items-center space-x-2">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </span>
                <button onclick="this.parentElement.remove()" class="text-teal-500 hover:text-teal-700 font-bold text-lg">&times;</button>
            </div>
        @endif

        <!-- SECTION 1: KARTU KONTROL & MONITORING AC 1 DAN AC 2 -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-outfit font-black text-xl text-slate-800 uppercase tracking-wide flex items-center space-x-2">
                    <span class="text-teal-600 font-bold">⚡</span>
                    <span>Status & Kontrol Unit AC</span>
                </h2>
                <span class="text-xs font-bold text-slate-400">2 Unit Terhubung</span>
            </div>

            <!-- GRID 2 KARTU AC -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @include('partials.kartu-ac', ['id' => 1, 'pin' => 18, 'name' => 'Lampu Panel Bawah (AC 1)', 'color' => 'teal', 'schedules' => $schedules])
                @include('partials.kartu-ac', ['id' => 2, 'pin' => 19, 'name' => 'Lampu Panel Atas (AC 2)', 'color' => 'cyan', 'schedules' => $schedules])
            </div>
        </section>

        <!-- SECTION 2: GRAFIK TREN ARUS LISTRIK (AMPERE REAL-TIME) -->
        <section>
            @include('partials.grafik-arus')
        </section>

        <!-- SECTION 3: JAM ATUR AC (MANAJEMEN PENJADWALAN OTOMATIS) -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-outfit font-black text-xl text-slate-800 uppercase tracking-wide flex items-center space-x-2">
                    <span class="text-teal-600 font-bold">⏱️</span>
                    <span>Pengaturan Jam Atur AC</span>
                </h2>
                <span class="text-xs font-bold text-slate-400">Penjadwalan Otomatis</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @include('partials.form-tambah-jadwal')
                @include('partials.daftar-jadwal')
            </div>
        </section>

    </main>

    <!-- Footer -->
    @include('partials.footer-bawah')


    <!-- Scripts Section -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // State locks to prevent race conditions during polling
        const controlLocks = {
            1: { targetState: null, lastToggled: 0 },
            2: { targetState: null, lastToggled: 0 }
        };
        const LOCK_TIMEOUT_MS = 35000; // 35 seconds

        // Function triggered when iOS-style toggles are clicked
        function sendAcControlViaSwitch(relayNum, checkboxEl) {
            const command = checkboxEl.checked ? 'ON' : 'OFF';
            console.log(`Sending command: Relay ${relayNum} -> ${command}`);
            
            // Set the control lock
            controlLocks[relayNum].targetState = checkboxEl.checked;
            controlLocks[relayNum].lastToggled = Date.now();
            
            // Instantly update text status next to switch
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
        const ctx = document.getElementById('currentChart').getContext('2d');
        const currentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Lampu Panel Bawah (AC 1)',
                        borderColor: '#0D9488',
                        backgroundColor: 'rgba(13, 148, 136, 0.05)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#0D9488',
                        tension: 0.3,
                        fill: true,
                        data: []
                    },
                    {
                        label: 'Lampu Panel Atas (AC 2)',
                        borderColor: '#0EA5E9',
                        backgroundColor: 'rgba(14, 165, 233, 0.05)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#0EA5E9',
                        tension: 0.3,
                        fill: true,
                        data: []
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#334155',
                            font: { family: 'Inter', weight: 600, size: 12 }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: { color: '#64748B', font: { family: 'mono', size: 10 } }
                    },
                    y: {
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: { color: '#64748B' },
                        title: {
                            display: true,
                            text: 'Arus (Ampere)',
                            color: '#475569',
                            font: { weight: 'bold', size: 12 }
                        }
                    }
                }
            }
        });

        // --- AJAX Polling for Real-time Telemetry Data ---
        function fetchRealtimeData() {
            fetch("{{ route('api.logs') }}")
                .then(response => response.json())
                .then(data => {
                    // 1. Update AC 1 Data
                    if (data.latest_ac1) {
                        const currentVal = parseFloat(data.latest_ac1.current_ampere).toFixed(4);
                        const ac1CurrEl = document.getElementById('ac1-current');
                        if (ac1CurrEl) ac1CurrEl.innerText = currentVal;
                        
                        const isActive = data.latest_ac1.active_ac.includes('_ON');
                        const switchEl = document.getElementById('ac1-switch');
                        const statusLabel = document.getElementById('ac1-badge-label');
                        const switchText = document.getElementById('ac1-switch-text');
                        
                        const lock = controlLocks[1];
                        const now = Date.now();
                        
                        if (lock.targetState !== null) {
                            if (isActive === lock.targetState || (now - lock.lastToggled > LOCK_TIMEOUT_MS)) {
                                lock.targetState = null;
                            }
                        }
                        
                        if (lock.targetState === null) {
                            if (switchEl) switchEl.checked = isActive;
                            if (statusLabel) {
                                statusLabel.innerText = isActive ? 'Online' : 'Offline';
                                statusLabel.className = isActive 
                                    ? 'px-2.5 py-0.5 rounded-md text-[11px] font-extrabold uppercase tracking-wider bg-teal-100 text-teal-800 border border-teal-300' 
                                    : 'px-2.5 py-0.5 rounded-md text-[11px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200';
                            }
                            if (switchText) {
                                switchText.innerText = isActive ? 'ON' : 'OFF';
                                switchText.className = isActive 
                                    ? "text-xs font-black uppercase tracking-wider text-teal-600"
                                    : "text-xs font-black uppercase tracking-wider text-slate-400";
                            }
                        }
                        
                        const timeEl = document.getElementById('ac1-time');
                        if (timeEl && data.latest_ac1.recorded_at) {
                            const raw = String(data.latest_ac1.recorded_at);
                            timeEl.innerText = raw.includes(' ') ? raw.split(' ')[1] : (raw.includes('T') ? raw.split('T')[1].substring(0, 8) : raw);
                        }
                    }

                    // 2. Update AC 2 Data
                    if (data.latest_ac2) {
                        const currentVal = parseFloat(data.latest_ac2.current_ampere).toFixed(4);
                        const ac2CurrEl = document.getElementById('ac2-current');
                        if (ac2CurrEl) ac2CurrEl.innerText = currentVal;
                        
                        const isActive = data.latest_ac2.active_ac.includes('_ON');
                        const switchEl = document.getElementById('ac2-switch');
                        const statusLabel = document.getElementById('ac2-badge-label');
                        const switchText = document.getElementById('ac2-switch-text');
                        
                        const lock = controlLocks[2];
                        const now = Date.now();
                        
                        if (lock.targetState !== null) {
                            if (isActive === lock.targetState || (now - lock.lastToggled > LOCK_TIMEOUT_MS)) {
                                lock.targetState = null;
                            }
                        }
                        
                        if (lock.targetState === null) {
                            if (switchEl) switchEl.checked = isActive;
                            if (statusLabel) {
                                statusLabel.innerText = isActive ? 'Online' : 'Offline';
                                statusLabel.className = isActive 
                                    ? 'px-2.5 py-0.5 rounded-md text-[11px] font-extrabold uppercase tracking-wider bg-cyan-100 text-cyan-800 border border-cyan-300' 
                                    : 'px-2.5 py-0.5 rounded-md text-[11px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200';
                            }
                            if (switchText) {
                                switchText.innerText = isActive ? 'ON' : 'OFF';
                                switchText.className = isActive 
                                    ? "text-xs font-black uppercase tracking-wider text-cyan-600"
                                    : "text-xs font-black uppercase tracking-wider text-slate-400";
                            }
                        }
                        
                        const timeEl = document.getElementById('ac2-time');
                        if (timeEl && data.latest_ac2.recorded_at) {
                            const raw = String(data.latest_ac2.recorded_at);
                            timeEl.innerText = raw.includes(' ') ? raw.split(' ')[1] : (raw.includes('T') ? raw.split('T')[1].substring(0, 8) : raw);
                        }
                    }

                    // 3. Update Chart.js
                    if (data.chart_logs && data.chart_logs.length > 0) {
                        const labels = [];
                        const ac1Data = [];
                        const ac2Data = [];

                        data.chart_logs.forEach(log => {
                            const timeStr = log.recorded_at ? log.recorded_at.split(' ')[1] : '';
                            if (!labels.includes(timeStr)) {
                                labels.push(timeStr);
                            }
                            if (log.active_ac.includes('AC_1')) {
                                ac1Data.push(log.current_ampere);
                            } else if (log.active_ac.includes('AC_2')) {
                                ac2Data.push(log.current_ampere);
                            }
                        });

                        currentChart.data.labels = labels;
                        currentChart.data.datasets[0].data = ac1Data;
                        currentChart.data.datasets[1].data = ac2Data;
                        currentChart.update();
                    }
                })
                .catch(err => console.error("Error fetching telemetry data:", err));
        }

        // Run fetchRealtimeData immediately and poll every 3 seconds
        fetchRealtimeData();
        setInterval(fetchRealtimeData, 3000);
    </script>
</body>
</html>
