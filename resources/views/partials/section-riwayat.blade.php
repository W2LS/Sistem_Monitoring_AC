<!-- ================= MODUL 3: LOG TELEMETRI & AUDIT SENSOR (DENGAN FILTER PERANGKAT & EXPORT CSV) ================= -->
<div class="space-y-6 pb-20" x-data="{ 
    selectedLogDevice: '{{ $filterDevice ?? 'all' }}',
    logTab: 'all' 
}">
    
    <!-- 1. PAGE HEADER & DOWNLOAD CSV ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#8E1616]/20 pb-4">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#8E1616] flex items-center gap-1.5">
                <span>📊</span>
                <span>PUSAT AUDIT DATA & TELEMETRI</span>
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-[#1D1616] tracking-tight mt-0.5">
                Riwayat & Log Telemetri
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Data beban arus sensor ACS712, status relay, dan audit operasional per perangkat.
            </p>
        </div>

        <!-- DOWNLOAD CSV BUTTON (FLEKSIBEL PER DEVICE) -->
        <a :href="'{{ route('logs.export') }}?device_id=' + selectedLogDevice" 
           class="inline-flex items-center justify-center space-x-2 bg-[#D84040] hover:bg-[#8E1616] text-white text-xs font-black uppercase tracking-wider py-3.5 px-6 rounded-[24px] shadow-lg shadow-[#D84040]/30 transition w-full sm:w-auto shrink-0 cursor-pointer active:scale-95 text-center">
            <span>📥</span>
            <span>Unduh Log Perangkat (CSV)</span>
        </a>
    </div>

    <!-- 2. FILTER BAR (DUAL CUSTOM DROPDOWNS ALA DEVZONE V-PIN) -->
    <div class="bg-white rounded-[28px] sm:rounded-[36px] p-5 sm:p-6 shadow-sm border border-[#8E1616]/20 grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
        
        <!-- DROPDOWN 1: PILIH PERANGKAT -->
        <div x-data="{
            openDevDropdown: false,
            searchDev: '',
            devices: [
                { id: 'all', name: 'Seluruh Armada (All Devices)', code: 'ALL', icon: '🌐' },
                @foreach($devices as $d)
                { 
                    id: '{{ $d->device_id }}', 
                    name: '{{ addslashes($d->name) }}', 
                    code: '{{ $d->device_id }}', 
                    icon: '{{ addslashes($d->icon ?? '⚡') }}' 
                },
                @endforeach
            ],
            get currentDev() {
                return this.devices.find(d => d.id === selectedLogDevice) || this.devices[0];
            },
            get filteredDevs() {
                if (!this.searchDev) return this.devices;
                const q = this.searchDev.toLowerCase();
                return this.devices.filter(d => d.name.toLowerCase().includes(q) || d.code.toLowerCase().includes(q));
            },
            selectDevice(devId) {
                selectedLogDevice = devId;
                this.openDevDropdown = false;
                window.location.href = '/?filter_device=' + devId;
            }
        }" class="relative">
            <label class="block text-[11px] font-black uppercase text-slate-500 tracking-wider mb-1.5 flex items-center gap-1.5">
                <span>📱</span>
                <span>PILIH PERANGKAT</span>
            </label>

            <!-- Trigger Button -->
            <div @click="openDevDropdown = !openDevDropdown" 
                 @click.away="openDevDropdown = false"
                 class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-xs sm:text-sm font-bold text-[#1D1616] bg-slate-50/70 hover:bg-white focus-within:ring-2 focus-within:ring-[#D84040] flex items-center justify-between cursor-pointer shadow-2xs hover:border-slate-300 transition">
                <div class="flex items-center gap-2.5 truncate">
                    <span class="text-base shrink-0" x-text="currentDev.icon"></span>
                    <span class="truncate font-black" x-text="currentDev.name"></span>
                    <span class="text-[10px] font-mono font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded shrink-0 hidden sm:inline" x-text="currentDev.code"></span>
                </div>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0 ml-2" :class="openDevDropdown ? 'rotate-180 text-[#D84040]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            <!-- Dropdown Panel -->
            <div x-show="openDevDropdown" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-150 transform opacity-0 scale-95"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute left-0 right-0 z-50 mt-1.5 bg-white rounded-2xl shadow-2xl border border-slate-200 p-2 space-y-1.5 max-h-60 overflow-y-auto">
                
                <!-- Search input -->
                <div class="px-1 pt-1 pb-1">
                    <input type="text" 
                           x-model="searchDev" 
                           @click.stop
                           placeholder="🔍 Cari perangkat / ID..." 
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-[#D84040] outline-none">
                </div>

                <!-- Device List -->
                <div class="divide-y divide-slate-50">
                    <template x-for="dev in filteredDevs" :key="dev.id">
                        <div @click="selectDevice(dev.id)" 
                             :class="selectedLogDevice === dev.id ? 'bg-[#D84040] text-white font-black' : 'text-slate-700 hover:bg-slate-50 font-bold'"
                             class="px-3.5 py-2.5 rounded-xl text-xs cursor-pointer flex items-center justify-between transition">
                            <div class="flex items-center gap-2 truncate">
                                <span x-text="dev.icon"></span>
                                <span class="truncate" x-text="dev.name"></span>
                                <span class="text-[10px] font-mono opacity-60" x-text="'(' + dev.code + ')'"></span>
                            </div>
                            <span x-show="selectedLogDevice === dev.id" class="text-[10px] font-black shrink-0">✓</span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- DROPDOWN 2: FILTER UNIT AC -->
        <div x-data="{
            openUnitDropdown: false,
            units: [
                { id: 'all', label: 'Semua Unit AC', count: {{ count($recentLogsAll) }}, badge: 'ALL', color: 'bg-slate-900 text-white' },
                @for($u = 1; $u <= ($logNumAc ?? 2); $u++)
                @php
                    $uCount = count($recentLogsByUnit[$u] ?? []);
                    $uLabel = $unitLogNames[$u] ?? ("AC {$u}");
                @endphp
                { 
                    id: 'ac{{ $u }}', 
                    label: '{{ addslashes($uLabel) }}', 
                    count: {{ $uCount }}, 
                    badge: 'AC {{ $u }}', 
                    color: '{{ match($u) { 1 => 'bg-rose-50 text-[#D84040] border border-rose-200', 2 => 'bg-red-50 text-[#8E1616] border border-red-200', 3 => 'bg-amber-50 text-amber-800 border border-amber-200', 4 => 'bg-emerald-50 text-emerald-800 border border-emerald-200', 5 => 'bg-cyan-50 text-cyan-800 border border-cyan-200', default => 'bg-purple-50 text-purple-800 border border-purple-200' } }}'
                },
                @endfor
            ],
            get currentUnit() {
                return this.units.find(u => u.id === logTab) || this.units[0];
            },
            selectUnit(unitId) {
                logTab = unitId;
                this.openUnitDropdown = false;
            }
        }" class="relative">
            <label class="block text-[11px] font-black uppercase text-slate-500 tracking-wider mb-1.5 flex items-center gap-1.5">
                <span>❄️</span>
                <span>FILTER UNIT AC</span>
            </label>

            <!-- Trigger Button -->
            <div @click="openUnitDropdown = !openUnitDropdown" 
                 @click.away="openUnitDropdown = false"
                 class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-xs sm:text-sm font-bold text-[#1D1616] bg-slate-50/70 hover:bg-white focus-within:ring-2 focus-within:ring-[#8E1616] flex items-center justify-between cursor-pointer shadow-2xs hover:border-slate-300 transition">
                <div class="flex items-center gap-2.5 truncate">
                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase shrink-0" :class="currentUnit.color" x-text="currentUnit.badge"></span>
                    <span class="truncate font-black" x-text="currentUnit.label"></span>
                    <span class="text-[11px] font-mono font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full shrink-0" x-text="'(' + currentUnit.count + ' log)'"></span>
                </div>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0 ml-2" :class="openUnitDropdown ? 'rotate-180 text-[#8E1616]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            <!-- Dropdown Panel -->
            <div x-show="openUnitDropdown" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-150 transform opacity-0 scale-95"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute left-0 right-0 z-50 mt-1.5 bg-white rounded-2xl shadow-2xl border border-slate-200 p-2 space-y-1.5 max-h-60 overflow-y-auto">
                
                <div class="divide-y divide-slate-50">
                    <template x-for="u in units" :key="u.id">
                        <div @click="selectUnit(u.id)" 
                             :class="logTab === u.id ? 'bg-[#8E1616] text-white font-black' : 'text-slate-700 hover:bg-slate-50 font-bold'"
                             class="px-3.5 py-2.5 rounded-xl text-xs cursor-pointer flex items-center justify-between transition">
                            <div class="flex items-center gap-2.5 truncate">
                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase" :class="logTab === u.id ? 'bg-white/20 text-white' : u.color" x-text="u.badge"></span>
                                <span class="truncate" x-text="u.label"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded-full" :class="logTab === u.id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'" x-text="u.count"></span>
                                <span x-show="logTab === u.id" class="text-[10px] font-black">✓</span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>

    <!-- 3. MAIN LOG TABLE -->
    <div class="bg-white rounded-[28px] sm:rounded-[40px] p-4 sm:p-7 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-[#8E1616]/15 gap-2">
            <span class="text-xs font-black uppercase tracking-wider text-[#1D1616] flex items-center gap-2">
                <span>Aliran Data Telemetri</span>
                <span class="text-[10px] text-slate-400 font-normal">({{ ($filterDevice && $filterDevice !== 'all') ? $filterDevice : 'Semua Perangkat' }})</span>
            </span>
            <span class="text-[10px] font-bold text-emerald-800 bg-emerald-100 border border-emerald-300 px-3 py-1 rounded-full inline-flex items-center gap-1.5 w-fit">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Auto-Recording Aktif</span>
            </span>
        </div>

        <!-- 1. TAB: SEMUA LOG -->
        <div x-show="logTab === 'all'" class="space-y-2 font-mono text-xs max-h-[520px] overflow-y-auto pr-1">
            @forelse($recentLogsAll as $log)
                @php
                    $acUnitNumber = 1;
                    $acState = 'OFF';
                    if (preg_match('/AC_(\d+)_([A-Z]+)/', $log->active_ac, $m)) {
                        $acUnitNumber = (int)$m[1];
                        $acState = $m[2];
                    } elseif (str_contains($log->active_ac, 'ON')) {
                        $acState = 'ON';
                    }
                    $badgeColor = match($acUnitNumber) {
                        1 => 'bg-rose-50 text-[#D84040] border-rose-200',
                        2 => 'bg-[#8E1616]/10 text-[#8E1616] border-[#8E1616]/20',
                        3 => 'bg-amber-50 text-amber-800 border-amber-200',
                        4 => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                        5 => 'bg-cyan-50 text-cyan-800 border-cyan-200',
                        default => 'bg-purple-50 text-purple-800 border-purple-200',
                    };
                    $customUnitLabel = $unitLogNames[$acUnitNumber] ?? ("AC {$acUnitNumber}");
                @endphp
                <div class="py-3 px-3 hover:bg-slate-50 rounded-2xl transition border border-slate-100 bg-white flex flex-col md:flex-row md:items-center justify-between gap-2.5 shadow-2xs">
                    <!-- Mobile Top Row / Desktop Left Side -->
                    <div class="flex items-center justify-between md:justify-start gap-2 sm:gap-3 flex-wrap">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-500 font-bold text-[11px] bg-slate-100 px-2 py-0.5 rounded-md font-mono">
                                {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                            </span>
                            <span class="{{ $badgeColor }} border px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider whitespace-nowrap">
                                {{ $customUnitLabel }} • {{ $acState }}
                            </span>
                        </div>
                        <span class="text-[#1D1616] font-bold text-xs font-mono bg-slate-50 px-2 py-0.5 rounded border border-slate-200">
                            {{ $log->device_id ?? 'RPI3B_PINDAD_ROOM_1' }}
                        </span>
                        <span class="hidden md:inline text-slate-300">→</span>
                        <!-- Current & Wattage Desktop View -->
                        <div class="hidden md:flex items-center gap-1.5 text-xs text-slate-600 font-mono">
                            <span>Arus:</span>
                            <strong class="text-[#1D1616] font-black">{{ number_format($log->current_ampere, 4) }} A</strong>
                            <span class="text-[10.5px] text-slate-400 font-medium">({{ round($log->current_ampere * 220) }} W @ 220V)</span>
                        </div>
                    </div>

                    <!-- Mobile Bottom Row / Desktop Right Side -->
                    <div class="flex items-center justify-between md:justify-end gap-3 pt-1.5 md:pt-0 border-t border-slate-100 md:border-0">
                        <div class="md:hidden flex items-center gap-1.5 text-xs text-slate-600 font-mono bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                            <span class="text-[11px] text-slate-500">Arus:</span>
                            <strong class="text-[#1D1616] font-black">{{ number_format($log->current_ampere, 4) }} A</strong>
                            <span class="text-[10px] text-slate-400 font-semibold">({{ round($log->current_ampere * 220) }}W)</span>
                        </div>
                        <span class="text-slate-400 text-[10px] font-sans shrink-0">
                            {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->diffForHumans() }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 italic">Belum ada riwayat telemetri tercatat untuk filter ini.</div>
            @endforelse
        </div>

        <!-- 2. TABS: KHUSUS UNIT AC (1..N) -->
        @for($u = 1; $u <= ($logNumAc ?? 2); $u++)
        @php
            $unitLogs = $recentLogsByUnit[$u] ?? [];
            $customUnitLabel = $unitLogNames[$u] ?? ("AC {$u}");
            $badgeColor = match($u) {
                1 => 'bg-rose-50 text-[#D84040] border-rose-200',
                2 => 'bg-[#8E1616]/10 text-[#8E1616] border-[#8E1616]/20',
                3 => 'bg-amber-50 text-amber-800 border-amber-200',
                4 => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                5 => 'bg-cyan-50 text-cyan-800 border-cyan-200',
                default => 'bg-purple-50 text-purple-800 border-purple-200',
            };
        @endphp
        <div x-show="logTab === 'ac{{ $u }}'" class="space-y-2 font-mono text-xs max-h-[520px] overflow-y-auto pr-1">
            @forelse($unitLogs as $log)
                @php
                    $acState = str_contains($log->active_ac, 'ON') ? 'ON' : 'OFF';
                @endphp
                <div class="py-3 px-3 hover:bg-slate-50 rounded-2xl transition border border-slate-100 bg-white flex flex-col md:flex-row md:items-center justify-between gap-2.5 shadow-2xs">
                    <div class="flex items-center justify-between md:justify-start gap-2 sm:gap-3 flex-wrap">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-500 font-bold text-[11px] bg-slate-100 px-2 py-0.5 rounded-md font-mono">
                                {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                            </span>
                            <span class="{{ $badgeColor }} border px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider whitespace-nowrap">
                                {{ $customUnitLabel }} • {{ $acState }}
                            </span>
                        </div>
                        <span class="text-[#1D1616] font-bold text-xs font-mono bg-slate-50 px-2 py-0.5 rounded border border-slate-200">{{ $log->device_id ?? 'RPI3B_PINDAD_ROOM_1' }}</span>
                        <span class="hidden md:inline text-slate-300">→</span>
                        <div class="hidden md:flex items-center gap-1.5 text-xs text-slate-600 font-mono">
                            <span>Arus:</span>
                            <strong class="text-[#1D1616] font-black">{{ number_format($log->current_ampere, 4) }} A</strong>
                            <span class="text-[10.5px] text-slate-400 font-medium">({{ round($log->current_ampere * 220) }} W @ 220V)</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between md:justify-end gap-3 pt-1.5 md:pt-0 border-t border-slate-100 md:border-0">
                        <div class="md:hidden flex items-center gap-1.5 text-xs text-slate-600 font-mono bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                            <span class="text-[11px] text-slate-500">Arus:</span>
                            <strong class="text-[#1D1616] font-black">{{ number_format($log->current_ampere, 4) }} A</strong>
                            <span class="text-[10px] text-slate-400 font-semibold">({{ round($log->current_ampere * 220) }}W)</span>
                        </div>
                        <span class="text-slate-400 text-[10px] font-sans shrink-0">
                            {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->diffForHumans() }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 italic">Belum ada riwayat khusus {{ $customUnitLabel }}.</div>
            @endforelse
        </div>
        @endfor
    </div>
</div>
