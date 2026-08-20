<!-- SECTION 3: RIWAYAT TELEMETRI & LOG TERPISAH AC 1 VS AC 2 (SOPHISTICATED NEO-CARD) -->
<div class="space-y-6 pb-24" x-data="{ logTab: 'all' }">
    
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#b7c6c2]/30 pb-4">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#b7c6c2] block">Pusat Audit Data</span>
            <h2 class="text-3xl font-black text-[#171e19] tracking-tight">
                Riwayat & Log Telemetri
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Data beban arus, status relay, dan catatan operasional terpisah untuk AC 1 dan AC 2.
            </p>
        </div>

        <!-- DOWNLOAD CSV BUTTON (VIBRANT RED CTA) -->
        <a :href="'{{ route('logs.export') }}?unit=' + logTab" 
           class="inline-flex items-center space-x-2 bg-[#ca0013] hover:bg-[#b00010] text-white text-xs font-black uppercase tracking-wider py-3.5 px-6 rounded-[24px] shadow-lg shadow-[#ca0013]/30 transition shrink-0">
            <span>📥</span>
            <span x-text="logTab === 'all' ? 'Unduh Semua Log (CSV)' : (logTab === 'ac1' ? 'Unduh Log AC 1 (CSV)' : 'Unduh Log AC 2 (CSV)')"></span>
        </a>
    </div>

    <!-- HORIZONTAL LOG FILTER SELECTOR (PILL FORMAT) -->
    <div class="flex items-center space-x-2 bg-white/80 p-2 rounded-[24px] border border-[#b7c6c2]/30 overflow-x-auto">
        <button 
            @click="logTab = 'all'" 
            :class="logTab === 'all' ? 'bg-[#171e19] text-white font-black shadow-md' : 'text-slate-600 hover:text-[#171e19] font-bold'"
            class="px-5 py-2.5 rounded-[16px] text-xs transition uppercase tracking-wider cursor-pointer shrink-0">
            📊 Semua Log ({{ count($recentLogsAll) }})
        </button>

        <button 
            @click="logTab = 'ac1'" 
            :class="logTab === 'ac1' ? 'bg-[#171e19] text-white font-black shadow-md' : 'text-slate-600 hover:text-[#171e19] font-bold'"
            class="px-5 py-2.5 rounded-[16px] text-xs transition uppercase tracking-wider cursor-pointer shrink-0 flex items-center space-x-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-[#ca0013]"></span>
            <span>Khusus AC 1 : Panasonic 1 ({{ count($recentLogsAc1) }})</span>
        </button>

        <button 
            @click="logTab = 'ac2'" 
            :class="logTab === 'ac2' ? 'bg-[#171e19] text-white font-black shadow-md' : 'text-slate-600 hover:text-[#171e19] font-bold'"
            class="px-5 py-2.5 rounded-[16px] text-xs transition uppercase tracking-wider cursor-pointer shrink-0 flex items-center space-x-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
            <span>Khusus AC 2 : Panasonic 2 ({{ count($recentLogsAc2) }})</span>
        </button>
    </div>

    <!-- MAIN LOG CONTAINER (40px Radius Card) -->
    <div class="bg-white rounded-[40px] p-6 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.08)] border border-[#b7c6c2]/30 space-y-4">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#b7c6c2]/20">
            <span class="text-xs font-black uppercase tracking-wider text-[#171e19]">
                <span x-show="logTab === 'all'">Aliran Data Gabungan (Live Telemetry Stream)</span>
                <span x-show="logTab === 'ac1'">Catatan Khusus Unit AC 1 (Relay Pin 18 - Lampu Bawah)</span>
                <span x-show="logTab === 'ac2'">Catatan Khusus Unit AC 2 (Relay Pin 19 - Lampu Atas)</span>
            </span>
            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                ● Auto-Recording Aktif
            </span>
        </div>

        <!-- 1. TAB: SEMUA LOG -->
        <div x-show="logTab === 'all'" class="divide-y divide-slate-100 font-mono text-xs max-h-[480px] overflow-y-auto pr-1">
            @forelse($recentLogsAll as $log)
                <div class="py-3.5 px-2 hover:bg-[#eeebe3]/50 rounded-[16px] transition flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center space-x-3">
                        <span class="text-slate-400 font-bold">
                            {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                        </span>
                        
                        @if(str_contains($log->active_ac, 'AC_1'))
                            <span class="bg-rose-50 text-[#ca0013] border border-rose-200 px-2.5 py-0.5 rounded-[12px] text-[10px] font-black uppercase">
                                AC 1 • {{ str_contains($log->active_ac, 'ON') ? 'ON' : 'OFF' }}
                            </span>
                        @else
                            <span class="bg-cyan-50 text-cyan-700 border border-cyan-200 px-2.5 py-0.5 rounded-[12px] text-[10px] font-black uppercase">
                                AC 2 • {{ str_contains($log->active_ac, 'ON') ? 'ON' : 'OFF' }}
                            </span>
                        @endif

                        <span class="text-[#171e19] font-bold">
                            {{ $log->device_id }}
                        </span>
                        <span class="opacity-30">→</span>
                        <span class="text-slate-600">
                            Arus: <strong class="text-[#171e19] font-black">{{ number_format($log->current_ampere, 4) }} A</strong>
                            <span class="text-[10px] text-slate-400 font-medium">({{ round($log->current_ampere * 220) }} W @ 220V)</span>
                        </span>
                    </div>
                    <span class="text-slate-400 text-[10px] font-sans">
                        {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->diffForHumans() }}
                    </span>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 italic">Belum ada riwayat telemetri tercatat.</div>
            @endforelse
        </div>

        <!-- 2. TAB: LOG KHUSUS AC 1 -->
        <div x-show="logTab === 'ac1'" class="divide-y divide-slate-100 font-mono text-xs max-h-[480px] overflow-y-auto pr-1">
            @forelse($recentLogsAc1 as $log)
                <div class="py-3.5 px-2 hover:bg-[#eeebe3]/50 rounded-[16px] transition flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center space-x-3">
                        <span class="text-slate-400 font-bold">
                            {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                        </span>
                        
                        <span class="bg-rose-50 text-[#ca0013] border border-rose-200 px-2.5 py-0.5 rounded-[12px] text-[10px] font-black uppercase">
                            {{ str_contains($log->active_ac, 'ON') ? '⚡ STATUS: ON' : '🛑 STATUS: OFF' }}
                        </span>

                        <span class="text-[#171e19] font-bold">Panasonic 1 (Bawah)</span>
                        <span class="opacity-30">→</span>
                        <span class="text-slate-600">
                            Beban: <strong class="text-[#ca0013] font-black">{{ number_format($log->current_ampere, 4) }} A</strong>
                            <span class="text-[10px] text-slate-400 font-medium">({{ round($log->current_ampere * 220) }} Watt)</span>
                        </span>
                    </div>
                    <span class="text-slate-400 text-[10px] font-sans">
                        {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->diffForHumans() }}
                    </span>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 italic">Belum ada log khusus AC 1.</div>
            @endforelse
        </div>

        <!-- 3. TAB: LOG KHUSUS AC 2 -->
        <div x-show="logTab === 'ac2'" class="divide-y divide-slate-100 font-mono text-xs max-h-[480px] overflow-y-auto pr-1">
            @forelse($recentLogsAc2 as $log)
                <div class="py-3.5 px-2 hover:bg-[#eeebe3]/50 rounded-[16px] transition flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center space-x-3">
                        <span class="text-slate-400 font-bold">
                            {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                        </span>
                        
                        <span class="bg-cyan-50 text-cyan-700 border border-cyan-200 px-2.5 py-0.5 rounded-[12px] text-[10px] font-black uppercase">
                            {{ str_contains($log->active_ac, 'ON') ? '⚡ STATUS: ON' : '🛑 STATUS: OFF' }}
                        </span>

                        <span class="text-[#171e19] font-bold">Panasonic 2 (Atas)</span>
                        <span class="opacity-30">→</span>
                        <span class="text-slate-600">
                            Beban: <strong class="text-cyan-700 font-black">{{ number_format($log->current_ampere, 4) }} A</strong>
                            <span class="text-[10px] text-slate-400 font-medium">({{ round($log->current_ampere * 220) }} Watt)</span>
                        </span>
                    </div>
                    <span class="text-slate-400 text-[10px] font-sans">
                        {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->diffForHumans() }}
                    </span>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 italic">Belum ada log khusus AC 2.</div>
            @endforelse
        </div>

    </div>

</div>
