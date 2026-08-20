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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
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

    <!-- Alpine.js CDN for reactive tab navigation & modals -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

    <!-- Header / Navbar -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-slate-200/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-teal-500 to-cyan-500 flex items-center justify-center font-outfit font-extrabold text-xl text-white shadow-md shadow-teal-500/20">
                    P
                </div>
                <div>
                    <h1 class="font-outfit font-extrabold text-lg leading-tight tracking-wider text-slate-800">
                        PT PINDAD (PERSERO)
                    </h1>
                    <p class="text-[10px] text-slate-400 font-semibold tracking-widest uppercase">IoT AC Monitoring System</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 bg-teal-50 px-3.5 py-1.5 rounded-full border border-teal-100">
                    <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-teal-600 uppercase tracking-wider">MQTT Live</span>
                </div>
                <div class="text-xs text-slate-400 font-medium hidden sm:block">
                    Server Time: <span id="server-time" class="font-mono text-slate-600 font-bold">{{ now()->format('H:i:s') }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 sm:px-6 py-6 space-y-6">
        
        <!-- Alerts for feedback -->
        @if(session('success'))
            <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm" role="alert">
                <span class="text-sm font-semibold">{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-teal-500 hover:text-teal-700 font-bold text-lg">&times;</button>
            </div>
        @endif

            <!-- TAB 1: DASHBOARD (RINGKASAN SISTEM) -->
            <div x-show="activeTab === 'dashboard'" x-cloak class="space-y-8">
                
                <!-- PAGE TITLE & SUMMARY WIDGET -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold text-teal-100 uppercase tracking-widest">Location Status</p>
                        <h3 class="font-outfit font-extrabold text-xl mt-1">Pindad Room 1</h3>
                    </div>
                    <span class="text-xs bg-white/20 px-2.5 py-1 rounded-full font-bold uppercase backdrop-blur-sm">AC Active</span>
                </div>
                <div class="flex items-baseline space-x-1 mt-6">
                    <span class="font-outfit font-extrabold text-4xl">24</span>
                    <span class="text-xl font-bold">°C</span>
                    <span class="text-xs text-teal-100 ml-2 font-medium">| Normal Temp</span>
                </div>
            </div>
        </section>

        <!-- AC UNIT STATUS CARDS -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- AC 1 CARD (Lampu Panel Bawah) -->
            <div id="ac1-card" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600">
                            <!-- AC / Fan Icon -->
                            <svg class="w-6 h-6 animate-[spin_10s_linear_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-teal-600 uppercase tracking-wider block">AC Unit 1 (Pin 18)</span>
                            <h4 class="font-outfit font-bold text-slate-800 text-lg">Lampu Panel Bawah</h4>
                        </div>
                    </div>
                    
                    <!-- iOS Switch Toggle with Label -->
                    <div class="flex items-center space-x-2">
                        <span id="ac1-switch-text" class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">OFF</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="ac1-switch" onchange="sendAcControlViaSwitch(1, this)" class="sr-only peer">
                            <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-teal-100 transition-all peer-checked:bg-teal-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-6"></div>
                        </label>
                    </div>
                </div>

                <!-- Current Reading Section -->
                <div class="bg-slate-50/80 rounded-2xl p-4 flex items-center justify-between border border-slate-100">
                    <div>
                        <span class="text-xs text-slate-400 font-semibold block">Arus Listrik (Current)</span>
                        <div class="flex items-baseline space-x-1 mt-1">
                            <span id="ac1-current" class="font-outfit font-extrabold text-3xl text-slate-800 font-mono">0.0000</span>
                            <span class="text-xs font-bold text-slate-400">A</span>
                        </div>
                    </div>
                    <div id="ac1-status-text" class="text-right">
                        <span class="text-xs text-slate-400 font-semibold block">Status</span>
                        <span id="ac1-badge-label" class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 block">Offline / Loading</span>
                    </div>
                </div>

                <!-- Timestamp -->
                <div class="mt-4 flex justify-between items-center text-[10px] text-slate-400 font-medium">
                    <span>ESP32 Telemetry</span>
                    <span>Updated: <span id="ac1-time" class="font-mono">Never</span></span>
                </div>
            </div>

            <!-- AC 2 CARD (Lampu Panel Atas) -->
            <div id="ac2-card" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-50 flex items-center justify-center text-cyan-600">
                            <!-- AC / Fan Icon -->
                            <svg class="w-6 h-6 animate-[spin_12s_linear_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-cyan-600 uppercase tracking-wider block">AC Unit 2 (Pin 19)</span>
                            <h4 class="font-outfit font-bold text-slate-800 text-lg">Lampu Panel Atas</h4>
                        </div>
                    </div>
                    
                    <!-- iOS Switch Toggle with Label -->
                    <div class="flex items-center space-x-2">
                        <span id="ac2-switch-text" class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">OFF</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="ac2-switch" onchange="sendAcControlViaSwitch(2, this)" class="sr-only peer">
                            <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-cyan-100 transition-all peer-checked:bg-cyan-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-6"></div>
                        </label>
                    </div>
                </div>

                <!-- Current Reading Section -->
                <div class="bg-slate-50/80 rounded-2xl p-4 flex items-center justify-between border border-slate-100">
                    <div>
                        <span class="text-xs text-slate-400 font-semibold block">Arus Listrik (Current)</span>
                        <div class="flex items-baseline space-x-1 mt-1">
                            <span id="ac2-current" class="font-outfit font-extrabold text-3xl text-slate-800 font-mono">0.0000</span>
                            <span class="text-xs font-bold text-slate-400">A</span>
                        </div>
                    </div>
                    <div id="ac2-status-text" class="text-right">
                        <span class="text-xs text-slate-400 font-semibold block">Status</span>
                        <span id="ac2-badge-label" class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 block">Offline / Loading</span>
                    </div>
                </div>

                <!-- Timestamp -->
                <div class="mt-4 flex justify-between items-center text-[10px] text-slate-400 font-medium">
                    <span>ESP32 Telemetry</span>
                    <span>Updated: <span id="ac2-time" class="font-mono">Never</span></span>
                </div>
            </div>

        </section>

        <!-- CHART CONTAINER -->
        <section class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-outfit font-extrabold text-lg text-slate-850 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Grafik Tren Arus Listrik (Ampere)</span>
                </h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Updates every 3s</span>
            </div>
            <div class="w-full relative h-[280px]">
                <canvas id="currentChart"></canvas>
            </div>
        </section>

        <!-- SCHEDULER SECTION -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Schedule Form Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm h-fit">
                <h3 class="font-outfit font-extrabold text-lg text-slate-800 mb-6 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-teal-650" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Tambah Jadwal AC</span>
                </h3>
                <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="label" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Label Jadwal</label>
                        <input type="text" id="label" name="label" required placeholder="Contoh: Shift Pagi, Lembur" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Jam Mulai</label>
                            <input type="time" id="start_time" name="start_time" required 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all">
                        </div>
                        <div>
                            <label for="end_time" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Jam Selesai</label>
                            <input type="time" id="end_time" name="end_time" required 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 active:scale-[0.98] text-white font-bold text-sm uppercase tracking-wider rounded-2xl transition-all duration-200 shadow-md shadow-teal-500/10">
                        Simpan Jadwal
                    </button>
                </form>
            </div>

            <!-- Schedule List Card -->
            <div class="lg:col-span-2 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h3 class="font-outfit font-extrabold text-lg text-slate-800 mb-6 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-teal-650" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Daftar Jadwal Penjadwalan</span>
                </h3>
                
                <div class="space-y-3">
                    @forelse($schedules as $schedule)
                        <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-4 flex items-center justify-between transition-all hover:bg-slate-50">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm leading-snug">{{ $schedule->label }}</h4>
                                    <p class="text-xs text-slate-400 font-semibold font-mono mt-0.5">
                                        {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <!-- Status Toggle -->
                                <form action="{{ route('schedules.toggle', $schedule->id) }}" method="POST" id="toggle-form-{{ $schedule->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" onchange="document.getElementById('toggle-form-{{ $schedule->id }}').submit()" class="sr-only peer" {{ $schedule->is_active ? 'checked' : '' }}>
                                        <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:bg-teal-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                                    </label>
                                </form>

                                <!-- Delete button -->
                                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-2 bg-white hover:bg-red-50 border border-slate-200 hover:border-red-200 rounded-xl">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 font-semibold text-sm">Belum ada jadwal yang diatur.</div>
                    @endforelse
                </div>
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
        const LOCK_TIMEOUT_MS = 35000; // 35 seconds (slightly longer than ESP32 telemetry interval)

        // Function triggered when iOS-style / CLI toggles are clicked
        function sendAcControlViaSwitch(relayNum, checkboxEl) {
            const command = checkboxEl.checked ? 'ON' : 'OFF';
            console.log(`Sending command: Relay ${relayNum} -> ${command}`);
            
            controlLocks[relayNum].targetState = checkboxEl.checked;
            controlLocks[relayNum].lastToggled = Date.now();
            
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
