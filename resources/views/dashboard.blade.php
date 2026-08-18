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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        panelBg: '#F3F4F6', // Light gray background
                        accentTeal: '#0D9488', // Teal accent
                        accentCyan: '#0EA5E9', // Cyan accent
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
            background-color: #E2E8F0; /* Soft pastel grayish-blue background */
        }
        /* Custom smooth transition classes */
        .ios-toggle:checked + .ios-toggle-bg {
            background-color: #0D9488;
        }
        .ios-toggle:checked + .ios-toggle-bg::after {
            transform: translateX(100%);
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen flex flex-col antialiased">

    @include('partials.navbar')

    <!-- Main Container -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 sm:px-6 py-6 space-y-6">
        
        <!-- Alerts for feedback -->
        @if(session('success'))
            <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm" role="alert">
                <span class="text-sm font-semibold">{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-teal-500 hover:text-teal-700 font-bold text-lg">&times;</button>
            </div>
        @endif

        @include('partials.welcome-cards')

        <!-- AC UNIT STATUS CARDS -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @include('partials.ac-card', ['id' => 1, 'pin' => 18, 'name' => 'Lampu Panel Bawah', 'color' => 'teal'])
            @include('partials.ac-card', ['id' => 2, 'pin' => 19, 'name' => 'Lampu Panel Atas', 'color' => 'cyan'])
        </section>

        @include('partials.chart')

        <!-- SCHEDULER SECTION -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @include('partials.schedule-form')
            @include('partials.schedule-list')
        </section>

    </main>

    @include('partials.footer')


    <!-- Scripts Section -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // State locks to prevent race conditions during polling
        const controlLocks = {
            1: { targetState: null, lastToggled: 0 },
            2: { targetState: null, lastToggled: 0 }
        };
        const LOCK_TIMEOUT_MS = 35000; // 35 seconds (slightly longer than ESP32 telemetry interval)

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
                if (command === 'ON') {
                    switchText.className = relayNum === 1
                        ? "text-xs font-extrabold text-teal-600 uppercase tracking-wider"
                        : "text-xs font-extrabold text-cyan-600 uppercase tracking-wider";
                } else {
                    switchText.className = "text-xs font-extrabold text-slate-400 uppercase tracking-wider";
                }
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
                    // Reset lock on failure
                    controlLocks[relayNum].targetState = null;
                    // Revert UI state on failure
                    checkboxEl.checked = !checkboxEl.checked;
                    if (switchText) {
                        const revertedCommand = checkboxEl.checked ? 'ON' : 'OFF';
                        switchText.innerText = revertedCommand;
                        switchText.className = revertedCommand === 'ON'
                            ? (relayNum === 1 ? "text-xs font-extrabold text-teal-600 uppercase tracking-wider" : "text-xs font-extrabold text-cyan-600 uppercase tracking-wider")
                            : "text-xs font-extrabold text-slate-400 uppercase tracking-wider";
                    }
                }
            })
            .catch(error => {
                console.error("Error sending control:", error);
                alert("Kesalahan koneksi mengirim perintah.");
                // Reset lock on failure
                controlLocks[relayNum].targetState = null;
                // Revert UI state on failure
                checkboxEl.checked = !checkboxEl.checked;
                if (switchText) {
                    const revertedCommand = checkboxEl.checked ? 'ON' : 'OFF';
                    switchText.innerText = revertedCommand;
                    switchText.className = revertedCommand === 'ON'
                        ? (relayNum === 1 ? "text-xs font-extrabold text-teal-600 uppercase tracking-wider" : "text-xs font-extrabold text-cyan-600 uppercase tracking-wider")
                        : "text-xs font-extrabold text-slate-400 uppercase tracking-wider";
                }
            });
        }

        // --- Real-time Chart.js Setup ---
        const ctx = document.getElementById('currentChart').getContext('2d');
        const currentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [], // Timestamps
                datasets: [
                    {
                        label: 'AC 1 (Panel Bawah)',
                        borderColor: '#0D9488', // Soft Teal
                        backgroundColor: 'rgba(13, 148, 136, 0.03)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#0D9488',
                        tension: 0.3,
                        fill: true,
                        data: []
                    },
                    {
                        label: 'AC 2 (Panel Atas)',
                        borderColor: '#0EA5E9', // Soft Cyan
                        backgroundColor: 'rgba(14, 165, 233, 0.03)',
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
                            color: '#64748B',
                            font: { family: 'Inter', weight: 500, size: 11 }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(226, 232, 240, 0.5)' },
                        ticks: { color: '#64748B', font: { family: 'mono', size: 10 } }
                    },
                    y: {
                        grid: { color: 'rgba(226, 232, 240, 0.5)' },
                        ticks: { color: '#64748B' },
                        title: {
                            display: true,
                            text: 'Arus (Ampere)',
                            color: '#64748B',
                            font: { weight: 'bold', size: 11 }
                        }
                    }
                }
            }
        });

        // --- AJAX Polling for Real-time Logs ---
        function fetchRealtimeData() {
            fetch("{{ route('api.logs') }}")
                .then(response => response.json())
                .then(data => {
                    // 1. Update AC 1 Card
                    if (data.latest_ac1) {
                        const currentVal = parseFloat(data.latest_ac1.current_ampere).toFixed(4);
                        document.getElementById('ac1-current').innerText = currentVal;
                        
                        const isActive = data.latest_ac1.active_ac.includes('_ON');
                        const switchEl = document.getElementById('ac1-switch');
                        const statusLabel = document.getElementById('ac1-badge-label');
                        const switchText = document.getElementById('ac1-switch-text');
                        
                        // Check if we have an active control lock for AC 1
                        const lock = controlLocks[1];
                        const now = Date.now();
                        
                        if (lock.targetState !== null) {
                            // If the latest telemetry matches our target state, the device has acknowledged it
                            if (isActive === lock.targetState) {
                                lock.targetState = null; // Clear the lock
                            } else if (now - lock.lastToggled > LOCK_TIMEOUT_MS) {
                                lock.targetState = null; // Timeout, revert to database state
                            }
                        }
                        
                        // Only update switch visual state if there is no active manual lock
                        if (lock.targetState === null) {
                            switchEl.checked = isActive;
                            if (switchText) {
                                switchText.innerText = isActive ? 'ON' : 'OFF';
                                switchText.className = isActive 
                                    ? "text-xs font-extrabold text-teal-600 uppercase tracking-wider"
                                    : "text-xs font-extrabold text-slate-400 uppercase tracking-wider";
                            }
                        }
                        
                        if (isActive) {
                            statusLabel.className = "text-xs font-bold text-teal-600 uppercase tracking-wider mt-1 block";
                            statusLabel.innerText = "ACTIVE ON";
                        } else {
                            statusLabel.className = "text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 block";
                            statusLabel.innerText = "ACTIVE OFF";
                        }
                        
                        const date = new Date(data.latest_ac1.recorded_at);
                        document.getElementById('ac1-time').innerText = date.toLocaleTimeString('id-ID');
                    }

                    // 2. Update AC 2 Card
                    if (data.latest_ac2) {
                        const currentVal = parseFloat(data.latest_ac2.current_ampere).toFixed(4);
                        document.getElementById('ac2-current').innerText = currentVal;
                        
                        const isActive = data.latest_ac2.active_ac.includes('_ON');
                        const switchEl = document.getElementById('ac2-switch');
                        const statusLabel = document.getElementById('ac2-badge-label');
                        const switchText = document.getElementById('ac2-switch-text');
                        
                        // Check if we have an active control lock for AC 2
                        const lock = controlLocks[2];
                        const now = Date.now();
                        
                        if (lock.targetState !== null) {
                            // If the latest telemetry matches our target state, the device has acknowledged it
                            if (isActive === lock.targetState) {
                                lock.targetState = null; // Clear the lock
                            } else if (now - lock.lastToggled > LOCK_TIMEOUT_MS) {
                                lock.targetState = null; // Timeout, revert to database state
                            }
                        }
                        
                        // Only update switch visual state if there is no active manual lock
                        if (lock.targetState === null) {
                            switchEl.checked = isActive;
                            if (switchText) {
                                switchText.innerText = isActive ? 'ON' : 'OFF';
                                switchText.className = isActive 
                                    ? "text-xs font-extrabold text-cyan-600 uppercase tracking-wider"
                                    : "text-xs font-extrabold text-slate-400 uppercase tracking-wider";
                            }
                        }
                        
                        if (isActive) {
                            statusLabel.className = "text-xs font-bold text-cyan-600 uppercase tracking-wider mt-1 block";
                            statusLabel.innerText = "ACTIVE ON";
                        } else {
                            statusLabel.className = "text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 block";
                            statusLabel.innerText = "ACTIVE OFF";
                        }
                        
                        const date = new Date(data.latest_ac2.recorded_at);
                        document.getElementById('ac2-time').innerText = date.toLocaleTimeString('id-ID');
                    }

                    // 3. Update Chart.js data
                    if (data.chart_logs && data.chart_logs.length > 0) {
                        const timeMap = {};
                        
                        data.chart_logs.forEach(log => {
                            const dateObj = new Date(log.recorded_at);
                            const timeStr = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                            
                            if (!timeMap[timeStr]) {
                                timeMap[timeStr] = { ac1: null, ac2: null };
                            }
                            
                            if (log.active_ac.includes('AC_1')) {
                                timeMap[timeStr].ac1 = parseFloat(log.current_ampere);
                            } else if (log.active_ac.includes('AC_2')) {
                                timeMap[timeStr].ac2 = parseFloat(log.current_ampere);
                            }
                        });

                        const sortedLabels = Object.keys(timeMap);
                        const ac1Dataset = [];
                        const ac2Dataset = [];

                        sortedLabels.forEach(timeLabel => {
                            ac1Dataset.push(timeMap[timeLabel].ac1);
                            ac2Dataset.push(timeMap[timeLabel].ac2);
                        });

                        currentChart.data.labels = sortedLabels;
                        currentChart.data.datasets[0].data = ac1Dataset;
                        currentChart.data.datasets[1].data = ac2Dataset;
                        currentChart.update('none'); // Update smoothly without animation lag
                    }
                })
                .catch(error => console.error("Error polling logs:", error));
        }

        // Live clock update in header
        setInterval(() => {
            const timeSpan = document.getElementById('server-time');
            if (timeSpan) {
                const now = new Date();
                timeSpan.innerText = now.toLocaleTimeString('id-ID', { hour12: false });
            }
        }, 1000);

        // Run polling every 3 seconds for instant updates
        setInterval(fetchRealtimeData, 3000);
        
        // Run immediately on page load
        fetchRealtimeData();
    </script>
</body>
</html>
