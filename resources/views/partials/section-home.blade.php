<script>
function homeFleetComponent() {
    return {
        viewMode: localStorage.getItem('pindad_home_view') || 'fleet',
        modalNewDevice: false,
        modalEditDevice: false,
        modalSchedule: false,
        modalEditSchedule: false,
        modalRpiSetup: false,
        rpiSetupData: { name: '', device_id: '', script_name: '', download_url: '', command: '' },
        copySuccess: false,
        editDeviceData: { id: '', name: '', template_id: '', location: '', device_id: '', ip_address: '', num_ac: 2 },
        editScheduleData: { id: '', label: '', target_ac: 'all', start_time: '06:00', end_time: '18:00', is_active: true },
        openRpiSetup(dev) {
            const cleanId = (dev.device_id || '').toLowerCase().replace(/[^a-z0-9_]/g, '_');
            const scriptName = 'pindad_node_' + cleanId + '.py';
            const command = `(crontab -l 2>/dev/null | grep -v 'pindad_node'; echo "@reboot sleep 10 && cd /home/alex && python3 /home/alex/${scriptName} > /home/alex/node.log 2>&1 &") | crontab - && nohup python3 /home/alex/${scriptName} > /home/alex/node.log 2>&1 &`;
            
            this.rpiSetupData = {
                name: dev.name || '',
                device_id: dev.device_id || '',
                ip_address: dev.ip_address || '192.168.197.64',
                script_name: scriptName,
                download_url: '/scripts/download/device?device_id=' + (dev.device_id || ''),
                command: command
            };
            this.copySuccess = false;
            this.modalRpiSetup = true;
        },
        copyCommand() {
            navigator.clipboard.writeText(this.rpiSetupData.command);
            this.copySuccess = true;
            setTimeout(() => { this.copySuccess = false; }, 3000);
        },
        openEditDevice(dev) {
            this.editDeviceData = {
                id: dev.id,
                name: dev.name,
                template_id: dev.template_id || '',
                location: dev.location,
                device_id: dev.device_id,
                ip_address: dev.ip_address || '',
                num_ac: dev.num_ac || 2
            };
            this.modalEditDevice = true;
        },
        openEditSchedule(sch) {
            this.editScheduleData = {
                id: sch.id,
                label: sch.label,
                target_ac: sch.target_ac || 'all',
                start_time: sch.start_time ? sch.start_time.substring(0, 5) : '06:00',
                end_time: sch.end_time ? sch.end_time.substring(0, 5) : '18:00',
                is_active: sch.is_active ? true : false
            };
            this.modalEditSchedule = true;
        },
        setView(mode) {
            this.viewMode = mode;
            localStorage.setItem('pindad_home_view', mode);
            if (mode === 'fleet' && window.history.pushState) {
                window.history.pushState({}, '', '/');
            }
        }
    };
}
</script>

<!-- ================= MODUL 1: HOME (UNIVERSAL IOT FLEET OVERVIEW & DEVICE DRILLDOWN) ================= -->
<div class="space-y-8 pb-20" x-data="homeFleetComponent()" @reset-home-view.window="setView('fleet')">

    <!-- ========================================================================= -->
    <!-- VIEW MODE 1: FLEET CENTRAL OVERVIEW (DAFTAR SEMUA DEVICE & TOTAL DAYA) -->
    <!-- ========================================================================= -->
    <div x-show="viewMode === 'fleet'" 
         x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-98"
         x-transition:enter-start="opacity-0 scale-98"
         x-transition:enter-end="opacity-100 scale-100"
         class="space-y-6">

        <!-- 1. HEADER & ACTION BUTTONS -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[#8E1616]/20 pb-4">
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#8E1616] flex items-center gap-1.5">
                    <span>⚡</span>
                    <span>CENTRAL IOT OVERVIEW • PT PINDAD</span>
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-[#1D1616] tracking-tight mt-0.5">
                    Armada Perangkat Pintar IoT
                </h2>
                <p class="text-xs font-semibold text-slate-500 mt-1">
                    Pantau akumulasi konsumsi daya seluruh pabrik dan kelola setiap perangkat dari satu dasbor terpusat.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <button @click="modalNewDevice = true" 
                        type="button"
                        class="bg-[#D84040] hover:bg-[#8E1616] text-white rounded-[24px] text-xs font-black uppercase tracking-wider py-3.5 px-6 shadow-lg shadow-[#D84040]/30 transition flex items-center space-x-2 shrink-0 cursor-pointer active:scale-95">
                    <span class="text-base leading-none font-black">+</span>
                    <span>Tambah Perangkat Baru</span>
                </button>
            </div>
        </div>

        <!-- 2. CENTRAL KPI HERO: TOTAL BEBAN SELURUH ALAT IOT -->
        <div class="bg-[#1D1616] rounded-[40px] p-6 sm:p-8 text-white shadow-[0_25px_60px_-15px_rgba(29,22,22,0.35)] border border-[#8E1616]/30 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-[#8E1616]/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-[#D84040]/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="space-y-2 relative z-10">
                <div class="flex items-center space-x-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#D84040] bg-[#D84040]/15 px-3 py-0.5 rounded-full border border-[#D84040]/30">
                        Total Beban Seluruh Armada
                    </span>
                    <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-500/20 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>{{ $onlineCount ?? count($devices) }} Node Aktif</span>
                    </span>
                </div>

                <div class="flex flex-wrap items-baseline gap-2 sm:gap-3">
                    <span class="text-3xl sm:text-4xl lg:text-5xl font-black font-mono text-white tracking-tight">
                        {{ number_format($totalFleetCurrent, 2) }} <span class="text-xl sm:text-2xl font-sans font-bold text-[#EEEEEE]/80">Ampere</span>
                    </span>
                    <span class="text-base sm:text-lg lg:text-xl font-extrabold text-amber-400 font-mono">
                        ≈ {{ number_format($totalFleetWatt) }} Watt
                    </span>
                </div>

                <p class="text-xs text-[#EEEEEE]/70 font-medium">
                    Total konsumsi listrik terukur dari {{ count($devices) }} alat IoT (AC Server, Lampu Koridor, dan Data Center).
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 shrink-0 relative z-10">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3.5 border border-white/10">
                    <span class="text-[10px] font-bold text-[#EEEEEE]/60 uppercase block">Total Perangkat</span>
                    <span class="text-xl font-black text-white mt-0.5 block">{{ count($devices) }} Node</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3.5 border border-white/10">
                    <span class="text-[10px] font-bold text-[#EEEEEE]/60 uppercase block">Total Unit Pendingin</span>
                    <span class="text-xl font-black text-[#D84040] mt-0.5 block">{{ $devices->sum('num_ac') }} AC</span>
                </div>
            </div>
        </div>

        <!-- 3. GRID KARTU PERANGKAT PINTAR IOT -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-black text-[#1D1616] tracking-tight flex items-center gap-2">
                    <span>Daftar Perangkat Terpasang</span>
                    <span class="text-xs font-bold text-[#8E1616] bg-[#8E1616]/10 px-2.5 py-0.5 rounded-full">{{ count($devices) }} Unit</span>
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($devices as $dev)
                @php
                    $devStat = $fleetStats[$dev->device_id] ?? ['is_online' => false, 'total_watt' => 0, 'total_current' => 0, 'last_seen' => 'Standby'];
                    $isAcType = ($dev->type === 'ac_monitoring' || $dev->device_id === 'RPI3B_PINDAD_ROOM_1');
                @endphp
                <div class="bg-white rounded-[32px] p-6 shadow-[0_20px_45px_-12px_rgba(29,22,22,0.08)] border-2 transition-all duration-300 hover:shadow-xl relative flex flex-col justify-between {{ $selectedDeviceId === $dev->device_id ? 'border-[#D84040] ring-4 ring-[#D84040]/10' : 'border-slate-100 hover:border-slate-300' }}">
                    
                    <div class="space-y-4">
                        <!-- Card Header -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shrink-0 {{ $devStat['is_online'] ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                                    {{ $dev->icon ?? ($isAcType ? '❄️' : '💡') }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-black text-sm sm:text-base text-[#1D1616] leading-snug break-words">{{ $dev->name }}</h4>
                                    <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5" title="{{ $dev->location }}">
                                        <svg class="w-3.5 h-3.5 text-[#8E1616] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        <span class="truncate">{{ $dev->location }}</span>
                                    </p>
                                </div>
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shrink-0 {{ $devStat['is_online'] ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                <span class="w-2 h-2 rounded-full {{ $devStat['is_online'] ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                <span>{{ $devStat['is_online'] ? 'Online' : 'Standby' }}</span>
                            </span>
                        </div>

                        <!-- 2x2 Clean Telemetry Info -->
                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Beban Daya</span>
                                <span class="text-xs font-black text-emerald-600 mt-0.5 block">
                                    {{ $devStat['total_watt'] }} W <span class="text-[10px] text-slate-400 font-normal">({{ $devStat['total_current'] }} A)</span>
                                </span>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Kapasitas</span>
                                <span class="text-xs font-black text-[#D84040] mt-0.5 block truncate">
                                    {{ $isAcType ? ($dev->num_ac . ' Unit AC') : ($dev->hardware_type) }}
                                </span>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Alamat IP</span>
                                <span class="text-[11px] font-bold text-slate-700 mt-0.5 block font-mono">
                                    {{ $dev->ip_address ?? '192.168.197.64' }}
                                </span>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">ID MQTT</span>
                                <span class="text-[10px] font-mono font-bold text-[#1D1616] mt-0.5 block break-all" title="{{ $dev->device_id }}">
                                    {{ $dev->device_id }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center gap-2">
                        <a href="{{ route('dashboard', ['device_id' => $dev->device_id]) }}" 
                           @click="setView('detail')"
                           class="flex-1 h-10 px-3 rounded-2xl text-center font-black text-[11px] sm:text-xs uppercase tracking-wide bg-[#1D1616] hover:bg-[#8E1616] text-white shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95">
                            <span>Kontrol & Jadwal</span>
                            <span class="shrink-0 text-xs">➔</span>
                        </a>

                        <!-- Setup Node & 1-Click Auto-Start Command Guide -->
                        <button @click="openRpiSetup({
                            name: '{{ addslashes($dev->name) }}',
                            device_id: '{{ $dev->device_id }}',
                            ip_address: '{{ $dev->ip_address }}'
                        })" 
                        type="button" 
                        class="h-10 px-3.5 rounded-2xl bg-slate-900 hover:bg-[#8E1616] text-white transition cursor-pointer active:scale-95 flex items-center justify-center gap-1.5 shrink-0 shadow-xs font-black text-xs" 
                        title="Panduan Setup & Salin Perintah Auto-Start Raspberry Pi untuk {{ $dev->name }}">
                            <span>⚡</span>
                            <span>Setup Node</span>
                        </button>

                        <!-- Edit Device Button -->
                        <button @click="openEditDevice({
                            id: '{{ $dev->id }}',
                            name: '{{ addslashes($dev->name) }}',
                            template_id: '{{ $dev->template_id }}',
                            location: '{{ addslashes($dev->location) }}',
                            device_id: '{{ $dev->device_id }}',
                            ip_address: '{{ $dev->ip_address }}',
                            num_ac: {{ $dev->num_ac ?? 2 }}
                        })" 
                        type="button" 
                        class="w-10 h-10 rounded-2xl bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 transition cursor-pointer active:scale-95 flex items-center justify-center shrink-0 shadow-xs" 
                        title="Edit Informasi Perangkat">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>

                        <form action="{{ route('devices.destroy', $dev->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perangkat {{ $dev->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-10 h-10 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition cursor-pointer active:scale-95 flex items-center justify-center shrink-0 shadow-xs" title="Hapus Perangkat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- VIEW MODE 2: DEVICE DETAIL VIEW (KONTROL SAKLAR, ARUS PER AC & JADWAL RUANGAN) -->
    <!-- ========================================================================= -->
    <div x-show="viewMode === 'detail'" 
         x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-98"
         x-transition:enter-start="opacity-0 scale-98"
         x-transition:enter-end="opacity-100 scale-100"
         class="space-y-8">

        <!-- 1. DETAIL HEADER DENGAN TOMBOL KEMBALI KE SEMUA DEVICE -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[#8E1616]/20 pb-4">
            <div class="flex items-center space-x-3.5">
                <button @click="setView('fleet')" 
                        type="button"
                        class="w-12 h-12 rounded-[20px] bg-white border border-[#8E1616]/30 text-[#8E1616] hover:bg-[#8E1616] hover:text-white transition flex items-center justify-center font-black text-lg shadow-sm cursor-pointer"
                        title="Kembali ke Daftar Semua Perangkat">
                    ⬅
                </button>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">
                            KONTROL & MONITORING SPESIFIK RUANGAN
                        </span>
                        <span class="text-[9px] font-mono font-bold bg-slate-200 text-slate-700 px-2 py-0.5 rounded-md">
                            {{ $currentDevice->device_id ?? 'RPI3B_PINDAD_ROOM_1' }}
                        </span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-[#1D1616] tracking-tight">
                        {{ $currentDevice->name ?? 'Ruang Server Utama (Lt. 1)' }}
                    </h2>
                    <p class="text-xs font-semibold text-slate-500">
                        📍 {{ $currentDevice->location ?? 'Gedung Divisi Mutu & TI' }} • IP: {{ $currentDevice->ip_address ?? '192.168.197.64' }}
                    </p>
                </div>
            </div>

            <!-- Download Standalone Python Script for This Room -->
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('scripts.download', ['type' => 'device', 'device_id' => $currentDevice->device_id ?? 'RPI3B_PINDAD_ROOM_1']) }}" 
                   class="px-4 py-2.5 rounded-2xl bg-[#1D1616] hover:bg-[#8E1616] text-white font-black text-xs uppercase tracking-wider transition cursor-pointer flex items-center gap-2 shadow-sm active:scale-95"
                   title="Unduh 1 file Python siap pakai untuk perangkat ini tanpa perlu file json">
                    <span>📥</span>
                    <span>Unduh Skrip (.py)</span>
                </a>
            </div>
        </div>

        <!-- 2. SUMMARY HERO STATUS KHUSUS RUANGAN INI -->
        <div class="bg-[#1D1616] rounded-[40px] p-6 sm:p-7 text-white shadow-[0_20px_50px_-12px_rgba(29,22,22,0.35)] border border-[#8E1616]/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#D84040] block">Total Konsumsi Ruangan Ini</span>
                <div class="flex items-baseline space-x-3">
                    <span id="stat-total-current" class="text-3xl sm:text-4xl font-black font-mono text-white tracking-tight">
                        {{ number_format(($latestAc1 ? (float)$latestAc1->current_ampere : 0) + ($latestAc2 ? (float)$latestAc2->current_ampere : 0), 4) }} A
                    </span>
                    <span id="stat-total-watt" class="text-base sm:text-lg font-extrabold text-[#EEEEEE]/80">
                        ≈ {{ round((($latestAc1 ? (float)$latestAc1->current_ampere : 0) + ($latestAc2 ? (float)$latestAc2->current_ampere : 0)) * 220) }} Watt
                    </span>
                </div>
                <span class="text-xs font-bold text-[#D84040] block">⚡ Sumber: Sensor Arus ACS712 & Relai Industri</span>
            </div>

            @php
                $isSelectedDevOnline = $fleetStats[$selectedDeviceId]['is_online'] ?? false;
            @endphp
            <div class="bg-white/10 backdrop-blur-md rounded-[28px] px-6 py-3.5 border border-white/10 flex items-center space-x-4 shrink-0">
                <span class="text-2xl {{ $isSelectedDevOnline ? 'animate-pulse' : '' }}">
                    {{ $isSelectedDevOnline ? '🟢' : '⚪' }}
                </span>
                <div>
                    <span class="text-sm font-black text-white block">
                        {{ $isSelectedDevOnline ? 'Node Online (Live Telemetri)' : 'Node Standby (Menunggu Sinyal)' }}
                    </span>
                    <span class="text-xs text-[#EEEEEE]/70 font-semibold">
                        {{ $isSelectedDevOnline ? 'Sinkronisasi 5 Detik' : 'Perangkat Belum Terhubung' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 3. KONTROL SAKLAR & ARUS PER UNIT AC (FLEKSIBEL 1, 2, 4, ATAU N UNIT) -->
        <div class="grid grid-cols-1 md:grid-cols-2 {{ count($unitData ?? []) > 2 ? 'xl:grid-cols-2 2xl:grid-cols-4' : '' }} gap-6"
             x-data="{
                 @foreach($unitData ?? [] as $uNum => $u)
                     ac{{ $uNum }}On: {{ $u['is_on'] ? 'true' : 'false' }},
                 @endforeach
                 loadingNum: null,
                 async toggleSwitch(acNumber) {
                     let key = 'ac' + acNumber + 'On';
                     let currentState = this[key];
                     let nextState = !currentState;
                     
                     // Instant visual state transition (0ms UI lag)
                     this[key] = nextState;
                     this.loadingNum = acNumber;

                     try {
                         let res = await fetch('{{ route('ac.control') }}', {
                             method: 'POST',
                             headers: {
                                 'Content-Type': 'application/json',
                                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                 'Accept': 'application/json',
                                 'X-Requested-With': 'XMLHttpRequest'
                             },
                             body: JSON.stringify({
                                 ac_number: acNumber,
                                 state: nextState ? 'ON' : 'OFF',
                                 device_id: '{{ $selectedDeviceId }}'
                             })
                         });
                         let json = await res.json();
                         if (!json.success) {
                             this[key] = currentState;
                         }
                     } catch (e) {
                         console.error('Toggle error:', e);
                         this[key] = currentState;
                     } finally {
                         this.loadingNum = null;
                     }
                 }
             }">
            
            @foreach($unitData ?? [] as $acNum => $unit)
            <!-- AC UNIT {{ $acNum }} CARD -->
            <div class="bg-white rounded-[40px] p-6 sm:p-7 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 space-y-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-[20px] bg-[#EEEEEE] text-[#8E1616] font-black text-xl flex items-center justify-center">
                                ❄️
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">UNIT {{ $acNum }} • PIN GPIO {{ $unit['gpio'] }}</span>
                                <h3 class="text-xl font-black text-[#1D1616]">{{ $unit['name'] }}</h3>
                            </div>
                        </div>
                        <span id="badge-status-ac{{ $acNum }}" 
                              class="px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider transition-colors"
                              :class="ac{{ $acNum }}On ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500'"
                              x-text="ac{{ $acNum }}On ? 'ONLINE' : 'STANDBY'">
                            {{ $unit['is_on'] ? 'ONLINE' : 'STANDBY' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-5">
                        <div class="bg-[#EEEEEE]/60 rounded-[28px] p-4 border border-[#8E1616]/10">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 block">Beban Arus Real-Time</span>
                            <span id="val-current-ac{{ $acNum }}" class="text-2xl font-black font-mono text-[#1D1616] mt-1 block">
                                {{ number_format((float)$unit['ampere'], 4) }} A
                            </span>
                            <span id="val-watt-ac{{ $acNum }}" class="text-xs font-bold text-slate-400 block mt-0.5">
                                ≈ {{ $unit['watt'] }} Watt
                            </span>
                        </div>

                        <div class="bg-[#EEEEEE]/60 rounded-[28px] p-4 border border-[#8E1616]/10">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 block">Jadwal Shift DS3231</span>
                            <span id="shift-text-ac{{ $acNum }}" class="text-xs font-extrabold text-[#8E1616] mt-1.5 block leading-snug">
                                {{ $unit['shift'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Toggle Switch AC {{ $acNum }} (Zero Reload AJAX) -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-black text-[#1D1616] block">Saklar Manual AC {{ $acNum }}</span>
                        <span class="text-[10px] text-slate-400 font-semibold">Klik saklar untuk ON/OFF langsung</span>
                    </div>

                    <button @click="toggleSwitch({{ $acNum }})" 
                            type="button" 
                            :title="ac{{ $acNum }}On ? 'Klik untuk mematikan AC {{ $acNum }}' : 'Klik untuk menyalakan AC {{ $acNum }}'"
                            class="group relative inline-flex items-center h-10 w-24 rounded-full transition-all duration-300 p-1 cursor-pointer select-none shadow-md active:scale-95"
                            :class="ac{{ $acNum }}On ? 'bg-gradient-to-r from-emerald-500 to-teal-600 ring-2 ring-emerald-400/50 shadow-emerald-200' : 'bg-gradient-to-r from-slate-300 to-slate-400 shadow-slate-200'">
                        
                        <!-- Label Text Inside Switch -->
                        <span class="w-full text-center text-[10px] font-black uppercase tracking-wider transition-all font-mono"
                              :class="ac{{ $acNum }}On ? 'text-white pr-7' : 'text-slate-600 pl-7'"
                              x-text="ac{{ $acNum }}On ? 'ON' : 'OFF'">
                        </span>

                        <!-- Sliding Circular Knob -->
                        <span class="absolute top-1 left-1 bg-white w-8 h-8 rounded-full shadow-lg transform transition-transform duration-300 flex items-center justify-center"
                              :class="ac{{ $acNum }}On ? 'translate-x-14' : 'translate-x-0'">
                            <span class="w-2.5 h-2.5 rounded-full transition-colors"
                                  :class="ac{{ $acNum }}On ? 'bg-emerald-500 ring-2 ring-emerald-200' : 'bg-slate-400'"></span>
                        </span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- 5. PUSAT PENJADWALAN SHIFT 12 JAM KHUSUS RUANGAN INI -->
        <div class="bg-white rounded-[40px] p-6 sm:p-8 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">OTOMASI HARDWARE RTC DS3231</span>
                    <h3 class="text-xl font-black text-[#1D1616] tracking-tight mt-0.5">Jadwal Shift & Rotasi AC Ruangan Ini</h3>
                    <p class="text-xs font-medium text-slate-500 mt-0.5">Aturan jadwal pergantian shift 12 jam otomatis tersinkronisasi ke modul RTC Raspberry Pi.</p>
                </div>

                <button @click="modalSchedule = true" 
                        class="bg-[#D84040] hover:bg-[#8E1616] text-white rounded-2xl text-xs font-black uppercase tracking-wider py-2.5 px-5 shadow-md transition flex items-center space-x-2 cursor-pointer shrink-0">
                    <span>+ Tambah Aturan Jadwal</span>
                </button>
            </div>

            <!-- Schedule Rules List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($schedules as $sch)
                <div class="rounded-[28px] p-5 border flex items-center justify-between gap-4 transition-all {{ $sch->is_active ? 'bg-slate-50 border-slate-200 shadow-xs' : 'bg-slate-100/70 border-slate-300/80 opacity-65' }}">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-black text-sm text-[#1D1616]">{{ $sch->label }}</h4>
                            <span class="text-[9px] font-black uppercase px-2.5 py-0.5 rounded-full flex items-center gap-1 {{ $sch->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sch->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                <span>{{ $sch->is_active ? 'Aktif' : 'Non-Aktif' }}</span>
                            </span>
                        </div>
                        <p class="text-xs font-mono font-bold text-[#8E1616]">
                            ⏰ {{ substr($sch->start_time, 0, 5) }} - {{ substr($sch->end_time, 0, 5) }} WIB
                        </p>
                        <p class="text-[11px] text-slate-500">
                            Target: 
                            @if($sch->target_ac === 'all')
                                Seluruh Unit AC Sekaligus ({{ count($unitData ?? []) }} Unit)
                            @elseif(isset($unitData[(int)$sch->target_ac]))
                                {{ $unitData[(int)$sch->target_ac]['name'] }}
                            @else
                                Unit AC {{ $sch->target_ac }}
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Edit Schedule Button (Pencil Icon) -->
                        <button @click="openEditSchedule({
                            id: '{{ $sch->id }}',
                            label: '{{ addslashes($sch->label) }}',
                            target_ac: '{{ $sch->target_ac }}',
                            start_time: '{{ $sch->start_time }}',
                            end_time: '{{ $sch->end_time }}',
                            is_active: {{ $sch->is_active ? 'true' : 'false' }}
                        })" 
                        type="button" 
                        class="p-2.5 rounded-2xl bg-amber-100 hover:bg-amber-200 text-amber-800 transition cursor-pointer text-xs font-bold flex items-center justify-center shrink-0 active:scale-95 shadow-xs" 
                        title="Edit Jam & Pengaturan Jadwal">
                            ✏️
                        </button>

                        <!-- Delete Schedule Button -->
                        <form action="{{ route('schedules.destroy', $sch->id) }}" method="POST" onsubmit="return confirm('Hapus aturan jadwal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 rounded-2xl bg-rose-100 hover:bg-rose-200 text-rose-700 transition cursor-pointer text-xs font-bold flex items-center justify-center shrink-0 active:scale-95 shadow-xs" title="Hapus Jadwal">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-2 text-center py-6 text-slate-400 text-xs">Belum ada aturan jadwal khusus untuk ruangan ini.</div>
                @endforelse
            </div>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- MODAL 1: TAMBAH PERANGKAT IOT BARU (PILIH TEMPLATE & DAFTARKAN) -->
    <!-- ========================================================================= -->
    <div x-show="modalNewDevice" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="modalNewDevice = false" 
             class="bg-white rounded-[40px] p-7 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-[20px] bg-[#D84040]/10 text-[#D84040] flex items-center justify-center font-black text-xl">
                        +
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-[#1D1616]">Daftarkan Perangkat IoT Baru</h4>
                        <p class="text-xs text-slate-500">Pilih blueprint template & hubungkan kontroler</p>
                    </div>
                </div>
                <button @click="modalNewDevice = false" class="text-slate-400 hover:text-[#D84040] text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('devices.store') }}" method="POST" class="space-y-4" 
                  x-data="{ 
                      devName: '', 
                      devId: '', 
                      selectedTmpl: '{{ $templates->first()->id ?? '' }}',
                      templatesMap: {
                          @foreach($templates as $t)
                          '{{ $t->id }}': {
                              name: '{{ addslashes($t->name) }}',
                              hardware: '{{ addslashes($t->hardware_type) }}',
                              conn: '{{ addslashes($t->connection_type) }}',
                              icon: '{{ addslashes($t->icon ?? '⚡') }}',
                              numAc: {{ count(collect($t->datastreams ?? [])->filter(fn($ds) => str_starts_with($ds['pin'], 'V') && (int)substr($ds['pin'], 1) < 8)) ?: 2 }}
                          },
                          @endforeach
                      },
                      updateSlug() {
                          this.devId = 'RPI3B_' + this.devName.toUpperCase().replace(/[^A-Z0-9]/g, '_').replace(/_+/g, '_');
                      }
                  }">
                @csrf
                
                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Blueprint Template Perangkat *</label>
                    <select name="template_id" 
                            x-model="selectedTmpl"
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#D84040] outline-none cursor-pointer">
                        @foreach($templates as $t)
                        <option value="{{ $t->id }}">{{ $t->icon ?? '⚡' }} {{ $t->name }} ({{ $t->hardware_type }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Nama Perangkat / Ruangan *</label>
                        <input type="text" name="name" x-model="devName" @input="updateSlug()" required placeholder="Contoh: AC Ruang Server 2" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Lokasi Gedung / Ruangan *</label>
                        <input type="text" name="location" required placeholder="Contoh: Gedung TIK Lantai 2" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider">ID Perangkat (Device ID MQTT) *</label>
                        <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded-md">
                            <span>🔒</span>
                            <span>Auto-Generated (Read-Only)</span>
                        </span>
                    </div>
                    <input type="text" name="device_id" x-model="devId" readonly required placeholder="RPI3B_..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-mono font-bold uppercase bg-slate-100 text-slate-600 cursor-not-allowed outline-none select-none shadow-2xs">
                    <p class="text-[11px] text-slate-400 mt-1">ID MQTT digenerate otomatis dari nama perangkat untuk meminimalisir kesalahan penulisan topic broker.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">IP Address Perangkat</label>
                        <input type="text" name="ip_address" value="192.168.196.50" placeholder="192.168.196.x" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Jumlah Unit AC (Kapasitas Relai)</label>
                        <input type="number" name="num_ac" min="1" max="8" value="2" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button @click="modalNewDevice = false" type="button" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#D84040] to-[#8E1616] text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Daftarkan Node</button>
                </div>
            </form>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- MODAL 2: TAMBAH ATURAN JADWAL SHIFT -->
    <!-- ========================================================================= -->
    <div x-show="modalSchedule" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="modalSchedule = false" 
             class="bg-white rounded-[40px] p-7 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-[20px] bg-[#8E1616]/10 text-[#8E1616] flex items-center justify-center font-black text-xl">
                        ⏰
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-[#1D1616]">Tambah Jadwal Shift AC</h4>
                        <p class="text-xs text-slate-500">Konfigurasi waktu rotasi hardware RTC DS3231</p>
                    </div>
                </div>
                <button @click="modalSchedule = false" class="text-slate-400 hover:text-[#8E1616] text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="device_id" value="{{ $selectedDeviceId }}">

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Label / Nama Shift *</label>
                    <input type="text" name="label" required placeholder="Contoh: Shift Siang (AC 1)" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Target Unit AC *</label>
                    <select name="target_ac" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#8E1616] outline-none cursor-pointer">
                        @foreach($unitData ?? [] as $acNum => $unit)
                            <option value="{{ $acNum }}">{{ $unit['name'] }} (Pin GPIO {{ $unit['gpio'] }})</option>
                        @endforeach
                        <option value="all">Seluruh Unit AC Sekaligus (Semua {{ count($unitData ?? []) }} Unit)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Jam Mulai (WIB) *</label>
                        <input type="time" name="start_time" required value="06:00" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Jam Berakhir (WIB) *</label>
                        <input type="time" name="end_time" required value="18:00" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button @click="modalSchedule = false" type="button" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#8E1616] to-[#1D1616] text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- MODAL 3: EDIT ATURAN JADWAL SHIFT & ROTASI -->
    <!-- ========================================================================= -->
    <div x-show="modalEditSchedule" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="modalEditSchedule = false" 
             class="bg-white rounded-[40px] p-7 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-[20px] bg-amber-100 text-amber-800 flex items-center justify-center font-black text-xl">
                        ✏️
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-[#1D1616]">Edit Jadwal Shift AC</h4>
                        <p class="text-xs text-slate-500">Ubah jam mulai, jam berakhir, atau unit target</p>
                    </div>
                </div>
                <button @click="modalEditSchedule = false" class="text-slate-400 hover:text-amber-800 text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/schedules/' + editScheduleData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Label / Nama Shift *</label>
                    <input type="text" 
                           name="label" 
                           x-model="editScheduleData.label"
                           required 
                           placeholder="Contoh: Shift Siang (AC 1)" 
                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Target Unit AC *</label>
                    <select name="target_ac" 
                            x-model="editScheduleData.target_ac"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#8E1616] outline-none cursor-pointer">
                        @foreach($unitData ?? [] as $acNum => $unit)
                            <option value="{{ $acNum }}">{{ $unit['name'] }} (Pin GPIO {{ $unit['gpio'] }})</option>
                        @endforeach
                        <option value="all">Seluruh Unit AC Sekaligus (Semua {{ count($unitData ?? []) }} Unit)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Jam Mulai (WIB) *</label>
                        <input type="time" 
                               name="start_time" 
                               x-model="editScheduleData.start_time"
                               required 
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Jam Berakhir (WIB) *</label>
                        <input type="time" 
                               name="end_time" 
                               x-model="editScheduleData.end_time"
                               required 
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                </div>

                <div class="pt-1">
                    <label class="flex items-center gap-3 cursor-pointer p-3.5 rounded-2xl border transition"
                           :class="editScheduleData.is_active ? 'bg-emerald-50/70 border-emerald-300' : 'bg-slate-50 border-slate-200'">
                        <input type="checkbox" name="is_active" value="1" x-model="editScheduleData.is_active" class="w-5 h-5 accent-emerald-600 rounded-lg cursor-pointer">
                        <div>
                            <span class="text-xs font-black text-[#1D1616] block">Status Aturan Jadwal</span>
                            <span class="text-[10.5px] font-medium" :class="editScheduleData.is_active ? 'text-emerald-700 font-bold' : 'text-slate-500'" x-text="editScheduleData.is_active ? '🟢 Jadwal AKTIF dan dieksekusi otomatis oleh sistem' : '⚪ Jadwal NON-AKTIF (Dijeda / Tidak dieksekusi)'"></span>
                        </div>
                    </label>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button @click="modalEditSchedule = false" type="button" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-amber-600 to-amber-700 text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Perbarui Jadwal</button>
                </div>
            </form>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- MODAL 4: EDIT INFORMASI PERANGKAT IOT -->
    <!-- ========================================================================= -->
    <div x-show="modalEditDevice" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="modalEditDevice = false" 
             class="bg-white rounded-[40px] p-7 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-[20px] bg-amber-100 text-amber-800 flex items-center justify-center font-black text-xl">
                        ✏️
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-[#1D1616]">Edit Informasi Perangkat IoT</h4>
                        <p class="text-xs text-slate-500">Perbarui konfigurasi ruangan & blueprint template</p>
                    </div>
                </div>
                <button @click="modalEditDevice = false" class="text-slate-400 hover:text-amber-800 text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/devices/' + editDeviceData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Pilih Template Blueprint *</label>
                    <select name="template_id" x-model="editDeviceData.template_id" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#D84040] outline-none">
                        @foreach($templates as $tmpl)
                        <option value="{{ $tmpl->id }}">{{ $tmpl->icon }} {{ $tmpl->name }} ({{ $tmpl->hardware_type }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Nama Perangkat / Ruangan *</label>
                    <input type="text" 
                           name="name" 
                           x-model="editDeviceData.name"
                           required 
                           placeholder="Contoh: Monitoring AC Ruang Server 2" 
                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Lokasi / Gedung *</label>
                        <input type="text" 
                               name="location" 
                               x-model="editDeviceData.location"
                               required 
                               placeholder="Gedung TIK" 
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-black uppercase text-slate-700 tracking-wider">ID Perangkat (MQTT)</label>
                            <span class="text-[9px] font-bold text-slate-600 bg-slate-100 border border-slate-300 px-2 py-0.5 rounded-full flex items-center gap-1">
                                <span>Terkunci</span>
                            </span>
                        </div>
                        <input type="text" 
                               name="device_id" 
                               x-model="editDeviceData.device_id"
                               readonly
                               class="w-full px-4 py-3 rounded-2xl border-2 border-dashed border-slate-300 text-xs sm:text-sm font-mono uppercase bg-slate-100/90 text-slate-700 font-bold cursor-not-allowed select-all outline-none">
                        <p class="text-[10px] text-slate-400 mt-1">ID MQTT bersifat permanen untuk menjaga rute sensor.</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Alamat IP Perangkat</label>
                        <input type="text" 
                               name="ip_address" 
                               x-model="editDeviceData.ip_address"
                               placeholder="192.168.196.45" 
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Kapasitas AC (Unit)</label>
                        <input type="number" 
                               name="num_ac" 
                               x-model="editDeviceData.num_ac"
                               min="0" 
                               max="8" 
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                </div>

                <!-- Direct Standalone Script Download -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between gap-3">
                    <div class="space-y-0.5">
                        <span class="text-xs font-black text-[#1D1616] block">Skrip Standalone Raspberry Pi</span>
                        <span class="text-[10px] text-slate-500">Unduh 1 file Python siap pakai tanpa perlu file node_config.json</span>
                    </div>
                    <a :href="'/scripts/download/device?device_id=' + editDeviceData.device_id" 
                       class="px-3.5 py-2 rounded-xl bg-[#1D1616] hover:bg-[#8E1616] text-white font-bold text-[11px] uppercase tracking-wide transition flex items-center gap-1.5 shrink-0 shadow-xs cursor-pointer">
                        <span>📥</span>
                        <span>Unduh .py</span>
                    </a>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button @click="modalEditDevice = false" type="button" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-amber-600 to-amber-700 text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Perbarui Perangkat</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 5: PANDUAN CEPAT SETUP & SALIN PERINTAH AUTO-START RASPBERRY PI -->
    <!-- ========================================================================= -->
    <div x-show="modalRpiSetup" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="modalRpiSetup = false" 
             class="bg-white rounded-[40px] p-7 sm:p-8 max-w-xl w-full shadow-2xl border border-slate-200 space-y-6 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-[22px] bg-slate-900 text-amber-400 flex items-center justify-center font-black text-2xl shadow-md">
                        ⚡
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-[#1D1616]" x-text="'Setup Node: ' + rpiSetupData.name"></h4>
                        <p class="text-xs text-slate-500 font-semibold flex items-center gap-1.5">
                            <span>ID MQTT:</span>
                            <code class="text-[#D84040] font-mono font-bold bg-rose-50 px-1.5 py-0.5 rounded" x-text="rpiSetupData.device_id"></code>
                        </p>
                    </div>
                </div>
                <button @click="modalRpiSetup = false" class="text-slate-400 hover:text-[#D84040] text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <!-- STEP 1: UNDUH FILE SKRIP -->
            <div class="bg-slate-50 rounded-2xl p-4 sm:p-5 border border-slate-200 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#1D1616] text-white flex items-center justify-center text-[10px]">1</span>
                        <span>Unduh File Skrip Python (.py)</span>
                    </span>
                    <a :href="rpiSetupData.download_url" 
                       class="px-4 py-2 rounded-xl bg-[#D84040] hover:bg-[#8E1616] text-white text-xs font-black uppercase tracking-wider shadow-md hover:shadow-lg transition flex items-center gap-1.5 cursor-pointer active:scale-95">
                        <span>📥</span>
                        <span>Unduh Skrip</span>
                    </a>
                </div>
                <p class="text-[11px] text-slate-500">
                    Unduh skrip <code class="font-bold text-[#1D1616] bg-white px-1.5 py-0.5 rounded border border-slate-200" x-text="rpiSetupData.script_name"></code> lalu letakkan pada folder <code class="font-mono text-slate-700 font-bold">/home/alex/</code> di Raspberry Pi.
                </p>
            </div>

            <!-- STEP 2: SALIN PERINTAH 1-BARIS AUTO-START & JALANKAN -->
            <div class="bg-slate-900 rounded-3xl p-5 border border-slate-800 space-y-3 text-white shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-amber-400 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-amber-400 text-slate-900 flex items-center justify-center text-[10px] font-black">2</span>
                        <span>Perintah 1-Klik Auto-Start on Boot</span>
                    </span>
                    <button @click="copyCommand()" 
                            type="button" 
                            class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider transition flex items-center gap-1.5 cursor-pointer active:scale-95"
                            :class="copySuccess ? 'bg-emerald-500 text-white' : 'bg-white/15 hover:bg-white/25 text-amber-300 border border-amber-400/30'">
                        <span x-text="copySuccess ? '✓' : '📋'"></span>
                        <span x-text="copySuccess ? 'Tersalin!' : 'Salin Perintah'"></span>
                    </button>
                </div>
                
                <div class="bg-black/60 rounded-2xl p-3.5 border border-white/10 font-mono text-[11px] text-emerald-400 break-all select-all leading-relaxed" x-text="rpiSetupData.command"></div>

                <p class="text-[11px] text-slate-400 leading-relaxed">
                    💡 <strong>Cara Pakai:</strong> Buka SSH terminal Raspberry Pi, <em>paste</em> perintah di atas lalu tekan <strong>Enter</strong>. Script akan langsung hidup di background dan otomatis menyala setiap kali Raspberry Pi dicolok listrik. Terminal SSH bisa langsung ditutup.
                </p>
            </div>

            <!-- STEP 3: CEK LOG REAL-TIME -->
            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 space-y-2">
                <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-slate-400 text-white flex items-center justify-center text-[10px]">3</span>
                    <span>Periksa Log Berjalan (Opsional)</span>
                </span>
                <div class="flex items-center justify-between bg-white p-2.5 rounded-xl border border-slate-200">
                    <code class="text-xs font-mono font-bold text-slate-700">tail -f /home/alex/node.log</code>
                    <span class="text-[10px] font-semibold text-slate-400">Tekan Ctrl+C untuk keluar</span>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end">
                <button @click="modalRpiSetup = false" type="button" class="px-6 py-3 rounded-2xl bg-[#1D1616] hover:bg-[#8E1616] text-white font-black text-xs uppercase tracking-wider shadow-md transition cursor-pointer active:scale-95">
                    Tutup Panduan
                </button>
            </div>
        </div>
    </div>
</div>
