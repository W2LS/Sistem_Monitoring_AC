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

    <!-- 2. FILTER DEVICE BAR -->
    <div class="bg-white rounded-[28px] sm:rounded-[32px] p-4 sm:p-5 shadow-sm border border-[#8E1616]/20 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 w-full md:w-auto">
            <span class="text-xs font-black uppercase tracking-wider text-slate-400 shrink-0">Pilih Perangkat:</span>
            <select x-model="selectedLogDevice" 
                    @change="window.location.href='/?filter_device=' + selectedLogDevice"
                    class="bg-slate-50 border-2 border-slate-200 text-xs font-bold text-[#1D1616] rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-[#D84040] cursor-pointer w-full sm:w-auto">
                <option value="all">🌐 Seluruh Armada (All Devices)</option>
                @foreach($devices as $d)
                <option value="{{ $d->device_id }}" {{ ($filterDevice ?? '') === $d->device_id ? 'selected' : '' }}>
                    {{ $d->icon ?? '⚡' }} {{ $d->name }} ({{ $d->device_id }})
                </option>
                @endforeach
            </select>
        </div>

        <!-- AC Unit Sub-filter -->
        <div class="grid grid-cols-3 sm:flex items-center gap-1.5 bg-slate-100 p-1.5 rounded-2xl w-full md:w-auto">
            <button @click="logTab = 'all'" 
                    :class="logTab === 'all' ? 'bg-[#1D1616] text-white font-black shadow-xs' : 'text-slate-600 font-bold hover:text-[#D84040]'"
                    class="px-3 py-2 rounded-xl text-[11px] sm:text-xs transition uppercase tracking-wider cursor-pointer text-center truncate">
                Semua ({{ count($recentLogsAll) }})
            </button>
            <button @click="logTab = 'ac1'" 
                    :class="logTab === 'ac1' ? 'bg-[#D84040] text-white font-black shadow-xs' : 'text-slate-600 font-bold hover:text-[#D84040]'"
                    class="px-3 py-2 rounded-xl text-[11px] sm:text-xs transition uppercase tracking-wider cursor-pointer text-center truncate">
                AC 1 ({{ count($recentLogsAc1) }})
            </button>
            <button @click="logTab = 'ac2'" 
                    :class="logTab === 'ac2' ? 'bg-[#8E1616] text-white font-black shadow-xs' : 'text-slate-600 font-bold hover:text-[#8E1616]'"
                    class="px-3 py-2 rounded-xl text-[11px] sm:text-xs transition uppercase tracking-wider cursor-pointer text-center truncate">
                AC 2 ({{ count($recentLogsAc2) }})
            </button>
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
                <div class="py-3 px-3 hover:bg-slate-50 rounded-2xl transition border border-slate-100 bg-white flex flex-col md:flex-row md:items-center justify-between gap-2.5 shadow-2xs">
                    <!-- Mobile Top Row / Desktop Left Side -->
                    <div class="flex items-center justify-between md:justify-start gap-2 sm:gap-3 flex-wrap">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-500 font-bold text-[11px] bg-slate-100 px-2 py-0.5 rounded-md font-mono">
                                {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                            </span>
                            @if(str_contains($log->active_ac, 'AC_1'))
                                <span class="bg-rose-50 text-[#D84040] border border-rose-200 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider">
                                    AC 1 • {{ str_contains($log->active_ac, 'ON') ? 'ON' : 'OFF' }}
                                </span>
                            @else
                                <span class="bg-[#8E1616]/10 text-[#8E1616] border border-[#8E1616]/20 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider">
                                    AC 2 • {{ str_contains($log->active_ac, 'ON') ? 'ON' : 'OFF' }}
                                </span>
                            @endif
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

        <!-- 2. TAB: KHUSUS AC 1 -->
        <div x-show="logTab === 'ac1'" class="space-y-2 font-mono text-xs max-h-[520px] overflow-y-auto pr-1">
            @forelse($recentLogsAc1 as $log)
                <div class="py-3 px-3 hover:bg-slate-50 rounded-2xl transition border border-slate-100 bg-white flex flex-col md:flex-row md:items-center justify-between gap-2.5 shadow-2xs">
                    <div class="flex items-center justify-between md:justify-start gap-2 sm:gap-3 flex-wrap">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-500 font-bold text-[11px] bg-slate-100 px-2 py-0.5 rounded-md font-mono">
                                {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                            </span>
                            <span class="bg-rose-50 text-[#D84040] border border-rose-200 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider">
                                AC 1 • {{ str_contains($log->active_ac, 'ON') ? 'ON' : 'OFF' }}
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
                <div class="p-8 text-center text-slate-400 italic">Belum ada riwayat khusus AC 1.</div>
            @endforelse
        </div>

        <!-- 3. TAB: KHUSUS AC 2 -->
        <div x-show="logTab === 'ac2'" class="space-y-2 font-mono text-xs max-h-[520px] overflow-y-auto pr-1">
            @forelse($recentLogsAc2 as $log)
                <div class="py-3 px-3 hover:bg-slate-50 rounded-2xl transition border border-slate-100 bg-white flex flex-col md:flex-row md:items-center justify-between gap-2.5 shadow-2xs">
                    <div class="flex items-center justify-between md:justify-start gap-2 sm:gap-3 flex-wrap">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-500 font-bold text-[11px] bg-slate-100 px-2 py-0.5 rounded-md font-mono">
                                {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                            </span>
                            <span class="bg-[#8E1616]/10 text-[#8E1616] border border-[#8E1616]/20 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider">
                                AC 2 • {{ str_contains($log->active_ac, 'ON') ? 'ON' : 'OFF' }}
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
                <div class="p-8 text-center text-slate-400 italic">Belum ada riwayat khusus AC 2.</div>
            @endforelse
        </div>
    </div>
</div>
