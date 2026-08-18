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
    
    <!-- Alpine.js CDN for interactive tabs & states -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #E2E8F0; /* Soft pastel grayish-blue background */
        }
    </style>
</head>
<body x-data="{ activeTab: 'home' }" class="text-slate-800 bg-slate-200 min-h-screen flex flex-col antialiased">

    <!-- Header & Banner Navigation -->
    @include('partials.menu-atas')

    <!-- Main Container Container -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 py-6 space-y-6">
        
        <!-- Alerts Feedback Notification -->
        @if(session('success'))
            <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm" role="alert">
                <span class="text-sm font-semibold">{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-teal-500 hover:text-teal-700 font-bold text-lg">&times;</button>
            </div>
        @endif

        <!-- TAB 1: HOME (Sesuai Wireframe Gambar 1) -->
        <div x-show="activeTab === 'home'" x-cloak class="space-y-6">
            <!-- Grid Kartu Ruangan & Middle Status Pills -->
            @include('partials.kartu-ruangan')

            <!-- Panel Kontrol Alat Kiri & Detail Alat Kanan -->
            @include('partials.panel-detail-ac')
        </div>

        <!-- TAB 2: GRAFIK ARUS (Sesuai Wireframe Gambar 2) -->
        <div x-show="activeTab === 'grafik'" x-cloak class="space-y-6">
            @include('partials.grafik-arus')
            
            <!-- Tampilkan Juga Panel Kontrol di bawah grafik sesuai wireframe -->
            @include('partials.panel-detail-ac')
        </div>

        <!-- TAB 3: LOG REPORT (Sesuai Wireframe Gambar 3) -->
        <div x-show="activeTab === 'log'" x-cloak class="space-y-6">
            @include('partials.tabel-log-report')
            
            <!-- Tampilkan Juga Panel Kontrol di bawah log sesuai wireframe -->
            @include('partials.panel-detail-ac')
        </div>

        <!-- TAB 4: PENJADWALAN AC -->
        <div x-show="activeTab === 'penjadwalan'" x-cloak class="space-y-6">
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @include('partials.form-tambah-jadwal')
                @include('partials.daftar-jadwal')
            </section>
        </div>

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
                    ? "text-xs font-black uppercase tracking-wider text-teal-400"
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
                        label: 'AC 1 (Panel Bawah)',
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
                        label: 'AC 2 (Panel Atas)',
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
                    let totalAmpere = 0;

                    // 1. Update AC 1 Data
                    if (data.latest_ac1) {
                        const currentVal = parseFloat(data.latest_ac1.current_ampere).toFixed(4);
                        totalAmpere += parseFloat(data.latest_ac1.current_ampere);

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
                                statusLabel.innerText = isActive ? 'ONLINE (ACTIVE)' : 'OFFLINE (STANDBY)';
                                statusLabel.className = isActive 
                                    ? 'font-extrabold text-teal-700 uppercase tracking-wider' 
                                    : 'font-extrabold text-slate-500 uppercase tracking-wider';
                            }
                            if (switchText) {
                                switchText.innerText = isActive ? 'ON' : 'OFF';
                                switchText.className = isActive 
                                    ? "text-xs font-black uppercase tracking-wider text-teal-400"
                                    : "text-xs font-black uppercase tracking-wider text-slate-400";
                            }
                        }
                        
                        const timeEl = document.getElementById('ac1-time');
                        if (timeEl && data.latest_ac1.recorded_at) {
                            timeEl.innerText = data.latest_ac1.recorded_at.split(' ')[1];
                        }
                    }

                    // 2. Update AC 2 Data
                    if (data.latest_ac2) {
                        const currentVal = parseFloat(data.latest_ac2.current_ampere).toFixed(4);
                        totalAmpere += parseFloat(data.latest_ac2.current_ampere);

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
                                statusLabel.innerText = isActive ? 'ONLINE (ACTIVE)' : 'OFFLINE (STANDBY)';
                                statusLabel.className = isActive 
                                    ? 'font-extrabold text-cyan-700 uppercase tracking-wider' 
                                    : 'font-extrabold text-slate-500 uppercase tracking-wider';
                            }
                            if (switchText) {
                                switchText.innerText = isActive ? 'ON' : 'OFF';
                                switchText.className = isActive 
                                    ? "text-xs font-black uppercase tracking-wider text-teal-400"
                                    : "text-xs font-black uppercase tracking-wider text-slate-400";
                            }
                        }
                        
                        const timeEl = document.getElementById('ac2-time');
                        if (timeEl && data.latest_ac2.recorded_at) {
                            timeEl.innerText = data.latest_ac2.recorded_at.split(' ')[1];
                        }
                    }

                    // Update Middle Status Pill
                    const totalPill = document.getElementById('total-current-pill');
                    if (totalPill) {
                        totalPill.innerText = `⚡ ${totalAmpere.toFixed(3)} A`;
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
