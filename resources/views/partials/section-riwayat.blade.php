<!-- SECTION 5: RIWAYAT & AKTIVITAS SISTEM (MODERN INDUSTRIAL GUI) -->
<div class="space-y-6">
    
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 font-sans text-slate-800">
        <div>
            <h2 class="font-outfit font-black text-2xl uppercase tracking-wide flex items-center space-x-2">
                <span>📜</span>
                <span>Riwayat Aktivitas System (Industrial Log)</span>
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Catatan kronologis perubahan status ON/OFF, fluktuasi arus, dan aksi kontrol otomatis.
            </p>
        </div>
        <button onclick="alert('Log berhasil diekspor ke format CSV.')" 
                class="bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs shadow-sm font-bold py-2 px-3.5 transition cursor-pointer">
            <span>📥 Ekspor Log CSV</span>
        </button>
    </div>

    <!-- MAIN LOG TABLE -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm font-sans overflow-hidden">
        <div class="bg-slate-50 border-b border-slate-200 p-4 flex items-center justify-between">
            <span class="text-slate-700 text-xs font-black uppercase tracking-wider">
                Live System Telemetry Logs
            </span>
            <span class="text-emerald-600 bg-emerald-50 border border-emerald-200 text-[11px] rounded-full font-bold px-2.5 py-0.5">
                ● Auto-Recording Active
            </span>
        </div>

        <div class="divide-y divide-slate-100 font-mono text-xs max-h-96 overflow-y-auto">
            
            @forelse($recentLogs as $log)
                <div class="hover:bg-slate-50/80 p-3.5 transition flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-slate-400 font-bold">
                            {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('H:i:s') }}
                        </span>
                        
                        @if(str_contains($log->active_ac, 'ON'))
                            <span class="bg-teal-50 text-teal-700 border border-teal-200 px-2 py-0.5 rounded text-[10px] font-extrabold">
                                {{ $log->active_ac }}
                            </span>
                        @else
                            <span class="bg-slate-100 text-slate-500 border border-slate-200 px-2 py-0.5 rounded text-[10px] font-extrabold">
                                {{ $log->active_ac }}
                            </span>
                        @endif

                        <span class="text-slate-700 font-bold">
                            {{ $log->device_id }}
                        </span>
                        <span class="opacity-40">→</span>
                        <span class="text-slate-600">
                            Current: <strong class="text-slate-900">{{ number_format($log->current_ampere, 4) }} A</strong>
                            <span class="opacity-60 text-[10px]">({{ round($log->current_ampere * 220) }} W @ 220V)</span>
                        </span>
                    </div>
                    <span class="text-slate-400 text-[10px]">
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
