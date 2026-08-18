<!-- TAB 3: LOG REPORT TABLE -->
<section class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm space-y-6">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
        <div>
            <h3 class="font-outfit font-black text-xl text-slate-800 tracking-wide flex items-center space-x-2">
                <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Riwayat Log Telemetry & Sensor (LOG REPORT)</span>
            </h3>
            <p class="text-xs text-slate-400 font-semibold mt-1">Data riwayat penggunaan daya dan kontrol terisi secara otomatis dari subscriber MQTT.</p>
        </div>

        <div class="flex items-center space-x-2">
            <span class="text-xs bg-slate-100 px-3 py-1.5 rounded-xl font-extrabold text-slate-600 border border-slate-200">
                Total Logs: {{ count($recentLogs) }} Entries
            </span>
            <button onclick="window.location.reload()" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition-colors" title="Refresh Table">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
        </div>
    </div>

    <!-- LOG REPORT TABLE CONTAINER -->
    <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
        <table class="w-full text-left text-sm text-slate-700">
            <thead class="bg-slate-100/90 text-xs uppercase font-extrabold text-slate-600 tracking-wider border-b border-slate-200">
                <tr>
                    <th scope="col" class="px-6 py-4">ID Log</th>
                    <th scope="col" class="px-6 py-4">ID Perangkat</th>
                    <th scope="col" class="px-6 py-4">AC & Command Status</th>
                    <th scope="col" class="px-6 py-4">Arus Listrik (Current)</th>
                    <th scope="col" class="px-6 py-4">Waktu Pencatatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($recentLogs as $log)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs font-bold text-slate-400">#{{ $log->id }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800 flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>{{ $log->device_id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if(str_contains($log->active_ac, 'ON'))
                                <span class="px-3 py-1 bg-teal-50 text-teal-700 border border-teal-200 rounded-full text-xs font-black uppercase tracking-wider">
                                    🟢 {{ $log->active_ac }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 border border-slate-200 rounded-full text-xs font-black uppercase tracking-wider">
                                    ⚪ {{ $log->active_ac }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono font-black text-slate-800 text-base">
                            ⚡ {{ number_format($log->current_ampere, 4) }} <span class="text-xs font-bold text-slate-400">A</span>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-500 font-medium">
                            {{ \Illuminate\Support\Carbon::parse($log->recorded_at)->format('Y-m-d H:i:s') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-semibold text-sm">
                            Belum ada riwayat log telemetry tersimpan di database.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</section>
