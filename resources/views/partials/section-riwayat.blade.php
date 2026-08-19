<!-- SECTION 5: RIWAYAT & AKTIVITAS SISTEM (DUAL THEME) -->
<div class="space-y-6">
    
    <!-- PAGE HEADER -->
    <div :class="currentTheme === 'cli' ? 'border-b border-[#1f521f] pb-3 text-[#33ff00] font-mono' : 'border-b border-slate-200 pb-4 font-sans text-slate-800'" class="flex items-center justify-between">
        <div>
            <h2 :class="currentTheme === 'cli' ? 'text-xl font-mono font-bold cli-glow' : 'font-outfit font-black text-2xl'" class="uppercase tracking-wide flex items-center space-x-2">
                <span x-text="currentTheme === 'cli' ? '> TELEMETRY_STREAM :' : '📜'"></span>
                <span x-text="currentTheme === 'cli' ? 'SYSTEM_AUDIT_LOG_BUFFER' : 'Riwayat Aktivitas System (Industrial Log)'"></span>
            </h2>
            <p :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-xs font-semibold text-slate-500 mt-1'">
                Catatan kronologis perubahan status ON/OFF, fluktuasi arus, dan aksi kontrol otomatis.
            </p>
        </div>
        <button onclick="alert('Log berhasil diekspor ke format CSV.')" 
                :class="currentTheme === 'cli' ? 'border border-[#33ff00] bg-[#0a0a0a] text-[#33ff00] rounded-none font-mono cli-btn-invert text-xs' : 'bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs shadow-sm'"
                class="font-bold py-2 px-3.5 transition cursor-pointer">
            <span x-text="currentTheme === 'cli' ? '[ 📥 EXPORT_CSV ]' : '📥 Ekspor Log CSV'"></span>
        </button>
    </div>

    <!-- MAIN LOG TABLE -->
    <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] rounded-none text-[#33ff00] font-mono' : 'bg-white rounded-3xl border border-slate-200 shadow-sm font-sans'" class="overflow-hidden">
        <div :class="currentTheme === 'cli' ? 'bg-[#0a0a0a] border-b border-[#1f521f]' : 'bg-slate-50 border-b border-slate-200'" class="p-4 flex items-center justify-between">
            <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-700'" class="text-xs font-black uppercase tracking-wider">
                <span x-text="currentTheme === 'cli' ? 'BUFFER: /var/log/pindad_ac.log (STREAMING)' : 'Live System Logs'"></span>
            </span>
            <span :class="currentTheme === 'cli' ? 'border border-[#33ff00] text-[#33ff00] bg-[#0a0a0a] text-[10px] cli-glow' : 'text-emerald-600 bg-emerald-50 border border-emerald-200 text-[11px] rounded-full'" class="font-bold px-2.5 py-0.5">
                ● Auto-Recording Active
            </span>
        </div>

        <div :class="currentTheme === 'cli' ? 'divide-y divide-[#1f521f]' : 'divide-y divide-slate-100'" class="font-mono text-xs max-h-96 overflow-y-auto">
            
            @forelse($recentLogs as $log)
                <div :class="currentTheme === 'cli' ? 'hover:bg-[#1f521f]/20' : 'hover:bg-slate-50/80'" class="p-3.5 transition flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span :class="currentTheme === 'cli' ? 'text-[#1f521f]' : 'text-slate-400'" class="font-bold">
                            {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                        </span>
                        
                        @if(str_contains($log->active_ac, 'ON'))
                            <span :class="currentTheme === 'cli' ? 'border border-[#33ff00] text-[#33ff00] bg-[#0a0a0a] px-1 text-[10px]' : 'bg-teal-50 text-teal-700 border border-teal-200 px-2 py-0.5 rounded text-[10px] font-extrabold'">
                                {{ $log->active_ac }}
                            </span>
                        @else
                            <span :class="currentTheme === 'cli' ? 'border border-[#1f521f] text-[#1f521f] bg-[#0a0a0a] px-1 text-[10px]' : 'bg-slate-100 text-slate-500 border border-slate-200 px-2 py-0.5 rounded text-[10px] font-extrabold'">
                                {{ $log->active_ac }}
                            </span>
                        @endif

                        <span :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-700'" class="font-bold">
                            {{ $log->device_id }}
                        </span>
                        <span class="opacity-40">→</span>
                        <span :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'">
                            Current: <strong :class="currentTheme === 'cli' ? 'text-[#ffb000] cli-amber-glow' : 'text-slate-900'">{{ number_format($log->current_ampere, 4) }} A</strong>
                            <span class="opacity-60 text-[10px]">({{ round($log->current_ampere * 220) }} W @ 220V)</span>
                        </span>
                    </div>
                    <span :class="currentTheme === 'cli' ? 'text-[#1f521f]' : 'text-slate-400'" class="text-[10px]">
                        {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->diffForHumans() }}
                    </span>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 italic">
                    Belum ada riwayat telemetri tercatat di database.
                </div>
            @endforelse

        </div>
    </div>

</div>
