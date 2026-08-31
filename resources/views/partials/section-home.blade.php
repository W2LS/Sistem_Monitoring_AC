<!-- ================= MODUL 1: HOME (UNIVERSAL IOT FLEET OVERVIEW & DEVICE DRILLDOWN) ================= -->
<div class="space-y-8 pb-20" x-data="{ 
    viewMode: localStorage.getItem('pindad_home_view') || 'fleet', // 'fleet' or 'detail'
    modalNewDevice: false,
    modalSchedule: false,
    editScheduleData: { id: '', label: '', target_ac: 'all', start_time: '', end_time: '' },
    setView(mode) {
        this.viewMode = mode;
        localStorage.setItem('pindad_home_view', mode);
    }
}">

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
                    <span>CENTRAL IOT OVERVIEW • PT PINDAD (PERSERO)</span>
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-[#1D1616] tracking-tight mt-0.5">
                    Armada Perangkat Pintar IoT
                </h2>
                <p class="text-xs font-semibold text-slate-500 mt-1">
                    Pantau akumulasi konsumsi daya seluruh pabrik dan kelola setiap perangkat dari satu dasbor terpusat.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <button @click="modalNewDevice = true" 
                        type="button"
                        class="bg-[#D84040] hover:bg-[#8E1616] text-white rounded-[24px] text-xs font-black uppercase tracking-wider py-3.5 px-6 shadow-lg shadow-[#D84040]/30 transition flex items-center space-x-2 shrink-0 cursor-pointer active:scale-95">
                    <span class="text-base leading-none font-black">+</span>
                    <span>Tambah Perangkat Baru</span>
                </button>

                <!-- Master Controls -->
                <div class="flex items-center gap-1.5 bg-white border border-[#8E1616]/20 p-1.5 rounded-full shadow-xs">
                    <form action="{{ route('devices.masterControl') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="command" value="ON">
                        <button type="submit" 
                                onclick="return confirm('Nyalakan SELURUH unit perangkat di semua ruangan?')"
                                class="px-3.5 py-1.5 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[10px] uppercase tracking-wider transition flex items-center gap-1 cursor-pointer"
                                title="Nyalakan Seluruh Perangkat">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Master ON</span>
                        </button>
                    </form>

                    <form action="{{ route('devices.masterControl') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="command" value="OFF">
                        <button type="submit" 
                                onclick="return confirm('Matikan SELURUH unit perangkat di semua ruangan?')"
                                class="px-3.5 py-1.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[10px] uppercase tracking-wider transition flex items-center gap-1 cursor-pointer"
                                title="Matikan Seluruh Perangkat">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span>Master OFF</span>
                        </button>
                    </form>
                </div>
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

                <div class="flex items-baseline space-x-3">
                    <span class="text-4xl sm:text-5xl font-black font-mono text-white tracking-tight">
                        {{ number_format($totalFleetCurrent, 2) }} <span class="text-2xl font-sans font-bold text-[#EEEEEE]/80">Ampere</span>
                    </span>
                    <span class="text-lg sm:text-xl font-extrabold text-amber-400 font-mono">
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
                <span class="text-xs text-slate-500">Klik <b>"Buka Detail & Jadwal"</b> untuk mengontrol perangkat</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($devices as $dev)
                @php
                    $devStat = $fleetStats[$dev->device_id] ?? ['is_online' => false, 'total_watt' => 0, 'total_current' => 0, 'last_seen' => 'Standby'];
                    $isAcType = ($dev->type === 'ac_monitoring' || $dev->device_id === 'RPI3B_PINDAD_ROOM_1');
                @endphp
                <div class="bg-white rounded-[32px] p-6 shadow-[0_20px_45px_-12px_rgba(29,22,22,0.08)] border-2 transition-all duration-300 hover:shadow-xl relative flex flex-col justify-between {{ $selectedDeviceId === $dev->device_id ? 'border-[#D84040] ring-4 ring-[#D84040]/10' : 'border-slate-100 hover:border-slate-300' }}">
                    
                    <div class="space-y-4">
                        <!-- Card Header -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shrink-0 {{ $devStat['is_online'] ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                                    {{ $dev->icon ?? ($isAcType ? '❄️' : '💡') }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-black text-base text-[#1D1616] leading-tight truncate" title="{{ $dev->name }}">{{ $dev->name }}</h4>
                                    <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5 truncate" title="{{ $dev->location }}">
                                        <svg class="w-3.5 h-3.5 text-[#8E1616] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        <span class="truncate">{{ $dev->location }}</span>
                                    </p>
                                </div>
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shrink-0 {{ $devStat['is_online'] ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                <span class="w-2 h-2 rounded-full {{ $devStat['is_online'] ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                {{ $devStat['is_online'] ? 'Online' : 'Standby' }}
                            </span>
                        </div>

                        <!-- 2x2 Clean Telemetry Info -->
                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Beban Terukur</span>
                                <span class="text-xs font-black text-emerald-600 mt-0.5 block truncate">
                                    {{ $devStat['total_watt'] }} Watt <span class="text-[10px] text-slate-400 font-normal">({{ $devStat['total_current'] }} A)</span>
                                </span>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Kapasitas / Tipe</span>
                                <span class="text-xs font-black text-[#D84040] mt-0.5 block truncate">
                                    {{ $isAcType ? ($dev->num_ac . ' Unit AC') : ($dev->hardware_type) }}
                                </span>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Alamat IP</span>
                                <span class="text-[11px] font-bold text-slate-700 mt-0.5 truncate block font-mono">
                                    {{ $dev->ip_address ?? '192.168.x.x' }}
                                </span>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">ID Perangkat</span>
                                <span class="text-[10px] font-mono font-bold text-[#1D1616] mt-0.5 truncate block" title="{{ $dev->device_id }}">
                                    {{ $dev->device_id }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center gap-2">
                        <a href="{{ route('dashboard', ['device_id' => $dev->device_id]) }}" 
                           @click="setView('detail')"
                           class="flex-1 py-3 px-4 rounded-2xl text-center font-black text-xs uppercase tracking-wider bg-[#1D1616] hover:bg-[#8E1616] text-white shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <span>Buka Kontrol & Jadwal</span>
                            <span>➔</span>
                        </a>

                        @if($dev->device_id !== 'RPI3B_PINDAD_ROOM_1')
                        <form action="{{ route('devices.destroy', $dev->id) }}" method="POST" onsubmit="return confirm('Hapus perangkat {{ $dev->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition cursor-pointer" title="Hapus Perangkat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        @endif
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

            <!-- Device Quick Selector Dropdown -->
            <div class="flex items-center gap-2">
                <select onchange="window.location.href='/?device_id=' + this.value" 
                        class="bg-white border-2 border-[#8E1616]/30 text-xs font-bold text-[#1D1616] rounded-2xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-[#D84040] shadow-sm cursor-pointer">
                    @foreach($devices as $d)
                    <option value="{{ $d->device_id }}" {{ $selectedDeviceId === $d->device_id ? 'selected' : '' }}>
                        {{ $d->name }}
                    </option>
                    @endforeach
                </select>

                <button @click="setView('fleet')" 
                        class="px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-xs uppercase tracking-wider transition cursor-pointer">
                    Semua Device ➔
                </button>
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

            <div class="bg-white/10 backdrop-blur-md rounded-[28px] px-6 py-3.5 border border-white/10 flex items-center space-x-4 shrink-0">
                <span class="text-2xl animate-pulse">🟢</span>
                <div>
                    <span class="text-sm font-black text-white block">Node Online (Live Telemetri)</span>
                    <span class="text-xs text-[#EEEEEE]/70 font-semibold">Sinkronisasi 5 Detik</span>
                </div>
            </div>
        </div>

        <!-- 3. DUAL AC KONTROL SAKLAR & ARUS (PANASONIC 1 VS PANASONIC 2) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- AC UNIT 1 CARD -->
            <div class="bg-white rounded-[40px] p-6 sm:p-7 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 space-y-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-[20px] bg-[#EEEEEE] text-[#8E1616] font-black text-xl flex items-center justify-center">
                                ❄️
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">UNIT 1 • PIN GPIO 17</span>
                                <h3 class="text-xl font-black text-[#1D1616]">Panasonic 1 (Lampu Bawah)</h3>
                            </div>
                        </div>
                        <span id="badge-status-ac1" class="px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider {{ ($latestAc1 && str_contains($latestAc1->active_ac, 'ON')) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ ($latestAc1 && str_contains($latestAc1->active_ac, 'ON')) ? 'ONLINE' : 'STANDBY' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-5">
                        <div class="bg-[#EEEEEE]/60 rounded-[28px] p-4 border border-[#8E1616]/10">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 block">Beban Arus Real-Time</span>
                            <span id="val-current-ac1" class="text-2xl font-black font-mono text-[#1D1616] mt-1 block">
                                {{ $latestAc1 ? number_format((float)$latestAc1->current_ampere, 4) : '0.0000' }} A
                            </span>
                            <span id="val-watt-ac1" class="text-xs font-bold text-slate-400 block mt-0.5">
                                ≈ {{ $latestAc1 ? round((float)$latestAc1->current_ampere * 220) : '0' }} Watt
                            </span>
                        </div>

                        <div class="bg-[#EEEEEE]/60 rounded-[28px] p-4 border border-[#8E1616]/10">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 block">Jadwal Shift DS3231</span>
                            <span id="shift-text-ac1" class="text-xs font-extrabold text-[#8E1616] mt-1.5 block leading-snug">
                                {{ $shiftAc1 }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Toggle Switch AC 1 -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-black text-[#1D1616] block">Kontrol Manual Langsung</span>
                        <span class="text-[10px] text-slate-400 font-semibold">Bypass jadwal otomatis RTC</span>
                    </div>

                    <form action="{{ route('ac.control') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ac_number" value="1">
                        <input type="hidden" name="device_id" value="{{ $selectedDeviceId }}">
                        <input type="hidden" name="state" value="{{ ($latestAc1 && str_contains($latestAc1->active_ac, 'ON')) ? 'OFF' : 'ON' }}">
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-wider transition-all shadow-md flex items-center space-x-2 cursor-pointer {{ ($latestAc1 && str_contains($latestAc1->active_ac, 'ON')) ? 'bg-[#D84040] text-white hover:bg-[#8E1616]' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}">
                            <span>{{ ($latestAc1 && str_contains($latestAc1->active_ac, 'ON')) ? 'MATIKAN (OFF)' : 'NYALAKAN (ON)' }}</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- AC UNIT 2 CARD -->
            <div class="bg-white rounded-[40px] p-6 sm:p-7 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 space-y-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-[20px] bg-[#EEEEEE] text-[#8E1616] font-black text-xl flex items-center justify-center">
                                ❄️
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">UNIT 2 • PIN GPIO 27</span>
                                <h3 class="text-xl font-black text-[#1D1616]">Panasonic 2 (Lampu Atas)</h3>
                            </div>
                        </div>
                        <span id="badge-status-ac2" class="px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider {{ ($latestAc2 && str_contains($latestAc2->active_ac, 'ON')) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ ($latestAc2 && str_contains($latestAc2->active_ac, 'ON')) ? 'ONLINE' : 'STANDBY' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-5">
                        <div class="bg-[#EEEEEE]/60 rounded-[28px] p-4 border border-[#8E1616]/10">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 block">Beban Arus Real-Time</span>
                            <span id="val-current-ac2" class="text-2xl font-black font-mono text-[#1D1616] mt-1 block">
                                {{ $latestAc2 ? number_format((float)$latestAc2->current_ampere, 4) : '0.0000' }} A
                            </span>
                            <span id="val-watt-ac2" class="text-xs font-bold text-slate-400 block mt-0.5">
                                ≈ {{ $latestAc2 ? round((float)$latestAc2->current_ampere * 220) : '0' }} Watt
                            </span>
                        </div>

                        <div class="bg-[#EEEEEE]/60 rounded-[28px] p-4 border border-[#8E1616]/10">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 block">Jadwal Shift DS3231</span>
                            <span id="shift-text-ac2" class="text-xs font-extrabold text-[#8E1616] mt-1.5 block leading-snug">
                                {{ $shiftAc2 }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Toggle Switch AC 2 -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-black text-[#1D1616] block">Kontrol Manual Langsung</span>
                        <span class="text-[10px] text-slate-400 font-semibold">Bypass jadwal otomatis RTC</span>
                    </div>

                    <form action="{{ route('ac.control') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ac_number" value="2">
                        <input type="hidden" name="device_id" value="{{ $selectedDeviceId }}">
                        <input type="hidden" name="state" value="{{ ($latestAc2 && str_contains($latestAc2->active_ac, 'ON')) ? 'OFF' : 'ON' }}">
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-wider transition-all shadow-md flex items-center space-x-2 cursor-pointer {{ ($latestAc2 && str_contains($latestAc2->active_ac, 'ON')) ? 'bg-[#D84040] text-white hover:bg-[#8E1616]' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}">
                            <span>{{ ($latestAc2 && str_contains($latestAc2->active_ac, 'ON')) ? 'MATIKAN (OFF)' : 'NYALAKAN (ON)' }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 4. GRAFIK TELEMETRI ARUS LISTRIK (ACS712) -->
        <div class="bg-white rounded-[40px] p-6 sm:p-8 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-[#1D1616] tracking-tight">Grafik Pemantauan Arus Real-Time (Ampere)</h3>
                    <p class="text-xs font-medium text-slate-500">Data telemetri pembacaan sensor arus ACS712 secara langsung.</p>
                </div>
                <div class="flex items-center space-x-4 text-xs font-bold">
                    <span class="flex items-center space-x-1 text-[#D84040]"><span class="w-3 h-3 rounded-full bg-[#D84040]"></span><span>AC 1</span></span>
                    <span class="flex items-center space-x-1 text-[#8E1616]"><span class="w-3 h-3 rounded-full bg-[#8E1616]"></span><span>AC 2</span></span>
                </div>
            </div>
            <div class="h-64 sm:h-72 w-full pt-4">
                <canvas id="currentChart"></canvas>
            </div>
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
                <div class="bg-slate-50 rounded-[28px] p-5 border border-slate-200 flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-black text-sm text-[#1D1616]">{{ $sch->label }}</h4>
                            <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full {{ $sch->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                {{ $sch->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </div>
                        <p class="text-xs font-mono font-bold text-[#8E1616]">
                            ⏰ {{ substr($sch->start_time, 0, 5) }} - {{ substr($sch->end_time, 0, 5) }} WIB
                        </p>
                        <p class="text-[11px] text-slate-500">
                            Target: {{ $sch->target_ac === '1' ? 'Panasonic 1 (Bawah)' : ($sch->target_ac === '2' ? 'Panasonic 2 (Atas)' : 'Semua Unit AC') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <form action="{{ route('schedules.toggle', $sch->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1.5 rounded-xl text-[11px] font-bold uppercase transition cursor-pointer {{ $sch->is_active ? 'bg-amber-100 hover:bg-amber-200 text-amber-800' : 'bg-emerald-100 hover:bg-emerald-200 text-emerald-800' }}">
                                {{ $sch->is_active ? 'Matikan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <form action="{{ route('schedules.destroy', $sch->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-xl bg-rose-100 hover:bg-rose-200 text-rose-700 transition cursor-pointer" title="Hapus Jadwal">
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

            <form action="{{ route('devices.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Pilih Template Blueprint *</label>
                    <select name="template_id" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#D84040] outline-none">
                        @foreach($templates as $tmpl)
                        <option value="{{ $tmpl->id }}">{{ $tmpl->icon }} {{ $tmpl->name }} ({{ $tmpl->hardware_type }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Nama Perangkat / Ruangan *</label>
                    <input type="text" name="name" required placeholder="Contoh: Monitoring AC Ruang Server Lt. 2" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Lokasi / Gedung *</label>
                        <input type="text" name="location" required placeholder="Gedung Divisi Rekayasa" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">ID Perangkat (MQTT) *</label>
                        <input type="text" name="device_id" required placeholder="RPI3B_PINDAD_ROOM_2" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-mono uppercase focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Alamat IP Perangkat</label>
                        <input type="text" name="ip_address" placeholder="192.168.196.45" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Kapasitas AC (Unit)</label>
                        <input type="number" name="num_ac" min="0" max="8" value="2" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button @click="modalNewDevice = false" type="button" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#D84040] to-[#8E1616] text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Simpan Perangkat</button>
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
                    <input type="text" name="label" required placeholder="Contoh: Shift Siang (Panasonic 1)" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Target Unit AC *</label>
                    <select name="target_ac" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#8E1616] outline-none">
                        <option value="1">Panasonic 1 (Lampu Bawah • Pin GPIO 17)</option>
                        <option value="2">Panasonic 2 (Lampu Atas • Pin GPIO 27)</option>
                        <option value="all">Kedua Unit AC Sekaligus</option>
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
</div>
