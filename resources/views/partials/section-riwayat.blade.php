<!-- ================= MODUL 3: LOG TELEMETRI & AUDIT SENSOR (DENGAN FILTER PERANGKAT & EXPORT CSV) ================= -->
<div class="space-y-6 pb-20" x-data="{ 
    selectedLogDevice: '{{ $filterDevice ?? 'all' }}',
    logTab: 'all' 
}">
    
    <!-- 1. PAGE HEADER & DOWNLOAD CSV ACTION -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[#8E1616]/20 pb-4">
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
           class="inline-flex items-center space-x-2 bg-[#D84040] hover:bg-[#8E1616] text-white text-xs font-black uppercase tracking-wider py-3.5 px-6 rounded-[24px] shadow-lg shadow-[#D84040]/30 transition shrink-0 cursor-pointer active:scale-95">
            <span>📥</span>
            <span>Unduh Log Perangkat (CSV)</span>
        </a>
    </div>

    <!-- 2. FILTER DEVICE BAR -->
    <div class="bg-white rounded-[32px] p-4 sm:p-5 shadow-sm border border-[#8E1616]/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="text-xs font-black uppercase tracking-wider text-slate-400">Pilih Perangkat:</span>
            <select x-model="selectedLogDevice" 
                    @change="window.location.href='/?filter_device=' + selectedLogDevice"
                    class="bg-slate-50 border-2 border-slate-200 text-xs font-bold text-[#1D1616] rounded-xl px-3.5 py-2 outline-none focus:ring-2 focus:ring-[#D84040] cursor-pointer">
                <option value="all">🌐 Seluruh Armada (All Devices)</option>
                @foreach($devices as $d)
                <option value="{{ $d->device_id }}" {{ ($filterDevice ?? '') === $d->device_id ? 'selected' : '' }}>
                    {{ $d->icon ?? '⚡' }} {{ $d->name }} ({{ $d->device_id }})
                </option>
                @endforeach
            </select>
        </div>

        <!-- AC Unit Sub-filter -->
        <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl">
            <button @click="logTab = 'all'" 
                    :class="logTab === 'all' ? 'bg-[#1D1616] text-white font-black shadow-xs' : 'text-slate-600 font-bold hover:text-[#D84040]'"
                    class="px-3.5 py-1.5 rounded-lg text-xs transition uppercase tracking-wider cursor-pointer">
                Semua Unit ({{ count($recentLogsAll) }})
            </button>
            <button @click="logTab = 'ac1'" 
                    :class="logTab === 'ac1' ? 'bg-[#D84040] text-white font-black shadow-xs' : 'text-slate-600 font-bold hover:text-[#D84040]'"
                    class="px-3.5 py-1.5 rounded-lg text-xs transition uppercase tracking-wider cursor-pointer">
                AC 1 ({{ count($recentLogsAc1) }})
            </button>
            <button @click="logTab = 'ac2'" 
                    :class="logTab === 'ac2' ? 'bg-[#8E1616] text-white font-black shadow-xs' : 'text-slate-600 font-bold hover:text-[#8E1616]'"
                    class="px-3.5 py-1.5 rounded-lg text-xs transition uppercase tracking-wider cursor-pointer">
                AC 2 ({{ count($recentLogsAc2) }})
            </button>
        </div>
    </div>

    <!-- 3. MAIN LOG TABLE -->
    <div class="bg-white rounded-[40px] p-6 sm:p-8 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 space-y-4">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#8E1616]/15">
            <span class="text-xs font-black uppercase tracking-wider text-[#1D1616] flex items-center gap-2">
                <span>Aliran Data Telemetri</span>
                <span class="text-[10px] text-slate-400 font-normal">({{ ($filterDevice && $filterDevice !== 'all') ? $filterDevice : 'Semua Perangkat' }})</span>
            </span>
            <span class="text-[10px] font-bold text-emerald-800 bg-emerald-100 border border-emerald-300 px-3 py-1 rounded-full flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Auto-Recording Aktif</span>
            </span>
        </div>

        <!-- 1. TAB: SEMUA LOG -->
        <div x-show="logTab === 'all'" class="divide-y divide-slate-100 font-mono text-xs max-h-[500px] overflow-y-auto pr-1">
            @forelse($recentLogsAll as $log)
                <div class="py-3 px-2 hover:bg-slate-50 rounded-xl transition flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center space-x-3">
                        <span class="text-slate-400 font-bold text-[11px]">
                            {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                        </span>
                        
                        @if(str_contains($log->active_ac, 'AC_1'))
                            <span class="bg-rose-50 text-[#D84040] border border-rose-200 px-2 py-0.5 rounded-md text-[9px] font-black uppercase">
                                AC 1 • {{ str_contains($log->active_ac, 'ON') ? 'ON' : 'OFF' }}
                            </span>
                        @else
                            <span class="bg-[#8E1616]/10 text-[#8E1616] border border-[#8E1616]/20 px-2 py-0.5 rounded-md text-[9px] font-black uppercase">
                                AC 2 • {{ str_contains($log->active_ac, 'ON') ? 'ON' : 'OFF' }}
                            </span>
                        @endif

                        <span class="text-[#1D1616] font-bold text-xs">
                            {{ $log->device_id ?? 'RPI3B_PINDAD_ROOM_1' }}
                        </span>
                        <span class="opacity-30">→</span>
                        <span class="text-slate-600">
                            Arus: <strong class="text-[#1D1616] font-black">{{ number_format($log->current_ampere, 4) }} A</strong>
                            <span class="text-[10px] text-slate-400 font-medium">({{ round($log->current_ampere * 220) }} W @ 220V)</span>
                        </span>
                    </div>
                    <span class="text-slate-400 text-[10px] font-sans">
                        {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->diffForHumans() }}
                    </span>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 italic">Belum ada riwayat telemetri tercatat untuk filter ini.</div>
            @endforelse
        </div>

        <!-- 2. TAB: KHUSUS AC 1 -->
        <div x-show="logTab === 'ac1'" class="divide-y divide-slate-100 font-mono text-xs max-h-[500px] overflow-y-auto pr-1">
            @forelse($recentLogsAc1 as $log)
                <div class="py-3 px-2 hover:bg-slate-50 rounded-xl transition flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center space-x-3">
                        <span class="text-slate-400 font-bold text-[11px]">
                            {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                        </span>
                        <span class="bg-rose-50 text-[#D84040] border border-rose-200 px-2 py-0.5 rounded-md text-[9px] font-black uppercase">
                            AC 1 • {{ str_contains($log->active_ac, 'ON') ? 'ON' : 'OFF' }}
                        </span>
                        <span class="text-[#1D1616] font-bold text-xs">{{ $log->device_id ?? 'RPI3B_PINDAD_ROOM_1' }}</span>
                        <span class="opacity-30">→</span>
                        <span class="text-slate-600">
                            Arus: <strong class="text-[#1D1616] font-black">{{ number_format($log->current_ampere, 4) }} A</strong>
                            <span class="text-[10px] text-slate-400 font-medium">({{ round($log->current_ampere * 220) }} W)</span>
                        </span>
                    </div>
                    <span class="text-slate-400 text-[10px] font-sans">
                        {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->diffForHumans() }}
                    </span>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 italic">Belum ada riwayat khusus AC 1.</div>
            @endforelse
        </div>

        <!-- 3. TAB: KHUSUS AC 2 -->
        <div x-show="logTab === 'ac2'" class="divide-y divide-slate-100 font-mono text-xs max-h-[500px] overflow-y-auto pr-1">
            @forelse($recentLogsAc2 as $log)
                <div class="py-3 px-2 hover:bg-slate-50 rounded-xl transition flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center space-x-3">
                        <span class="text-slate-400 font-bold text-[11px]">
                            {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                        </span>
                        <span class="bg-[#8E1616]/10 text-[#8E1616] border border-[#8E1616]/20 px-2 py-0.5 rounded-md text-[9px] font-black uppercase">
                            AC 2 • {{ str_contains($log->active_ac, 'ON') ? 'ON' : 'OFF' }}
                        </span>
                        <span class="text-[#1D1616] font-bold text-xs">{{ $log->device_id ?? 'RPI3B_PINDAD_ROOM_1' }}</span>
                        <span class="opacity-30">→</span>
                        <span class="text-slate-600">
                            Arus: <strong class="text-[#1D1616] font-black">{{ number_format($log->current_ampere, 4) }} A</strong>
                            <span class="text-[10px] text-slate-400 font-medium">({{ round($log->current_ampere * 220) }} W)</span>
                        </span>
                    </div>
                    <span class="text-slate-400 text-[10px] font-sans">
                        {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->diffForHumans() }}
                    </span>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 italic">Belum ada riwayat khusus AC 2.</div>
            @endforelse
        </div>
    </div>
</div>
