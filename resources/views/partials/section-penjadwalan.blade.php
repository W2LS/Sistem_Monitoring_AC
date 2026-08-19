<!-- SECTION 4: MANAGEMENT PENJADWALAN OTOMATIS AC (DUAL THEME) -->
<div class="space-y-6" x-data="{ modalTambah: false }">
    
    <!-- PAGE HEADER & ACTION BUTTON -->
    <div :class="currentTheme === 'cli' ? 'border-b border-[#1f521f] pb-3 text-[#33ff00] font-mono' : 'border-b border-slate-200 pb-4 font-sans text-slate-800'"
         class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 :class="currentTheme === 'cli' ? 'text-xl font-mono font-bold cli-glow' : 'font-outfit font-black text-2xl'" class="uppercase tracking-wide flex items-center space-x-2">
                <span x-text="currentTheme === 'cli' ? '> CRON_ENGINE :' : '⏰'"></span>
                <span x-text="currentTheme === 'cli' ? 'AC_ROTATION_RULES_TABLE' : 'Penjadwalan ON/OFF AC'"></span>
            </h2>
            <p :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-xs font-semibold text-slate-500 mt-1'">
                Atur jadwal otomatis untuk mengontrol penyalaan & pemadaman AC sesuai siklus kerja ruang server.
            </p>
        </div>

        <button 
            @click="modalTambah = true" 
            :class="currentTheme === 'cli' 
                ? 'border border-[#33ff00] bg-[#0a0a0a] text-[#33ff00] rounded-none font-mono cli-btn-invert text-xs' 
                : 'bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs shadow-md'"
            class="font-black py-2.5 px-4 transition duration-200 flex items-center space-x-2 shrink-0 cursor-pointer">
            <span class="text-base leading-none">+</span>
            <span x-text="currentTheme === 'cli' ? '[ + ADD_NEW_RULE ]' : 'Tambah Jadwal Baru'"></span>
        </button>
    </div>

    <!-- MAIN SCHEDULING TABLE CONTAINER -->
    <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] rounded-none text-[#33ff00] font-mono' : 'bg-white rounded-3xl border border-slate-200 shadow-sm font-sans'"
         class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr :class="currentTheme === 'cli' ? 'bg-[#0a0a0a] border-b border-[#1f521f] text-[#ffb000]' : 'bg-slate-50 border-b border-slate-200 text-slate-400'"
                        class="text-[11px] font-black uppercase tracking-wider">
                        <th class="py-4 px-6">No.</th>
                        <th class="py-4 px-6">Nama Jadwal</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Jadwal ON</th>
                        <th class="py-4 px-6">Jadwal OFF</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody :class="currentTheme === 'cli' ? 'divide-y divide-[#1f521f] text-[#33ff00]' : 'divide-y divide-slate-100 text-slate-700'"
                       class="text-xs font-bold">
                    
                    @forelse($schedules as $index => $schedule)
                        <tr :class="currentTheme === 'cli' ? 'hover:bg-[#1f521f]/20' : 'hover:bg-slate-50/80'" class="transition">
                            <td class="py-4 px-6 font-mono opacity-60">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-black" :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-800'">
                                {{ $schedule->label }}
                            </td>
                            <td class="py-4 px-6">
                                @if($schedule->is_active)
                                    <span :class="currentTheme === 'cli' ? 'border border-[#33ff00] bg-[#0a0a0a] text-[#33ff00] rounded-none text-[10px]' : 'bg-emerald-100 text-emerald-700 rounded-full text-[11px]'" 
                                          class="inline-flex items-center px-2.5 py-0.5 font-extrabold">
                                        {{ $schedule->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                @else
                                    <span :class="currentTheme === 'cli' ? 'border border-[#1f521f] text-[#1f521f] rounded-none text-[10px]' : 'bg-slate-100 text-slate-500 rounded-full text-[11px]'" 
                                          class="inline-flex items-center px-2.5 py-0.5 font-extrabold">
                                        Non-Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-1.5 font-mono">
                                    <span :class="currentTheme === 'cli' ? 'text-[#33ff00] font-bold' : 'text-emerald-500 font-extrabold'">
                                        {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }}
                                    </span>
                                    <span :class="currentTheme === 'cli' ? 'text-[#33ff00] text-[10px]' : 'text-[10px] text-emerald-600 bg-emerald-50 p-1 rounded-md'">⚡ ON</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-1.5 font-mono">
                                    <span :class="currentTheme === 'cli' ? 'text-[#ff3333] font-bold' : 'text-rose-500 font-extrabold'">
                                        {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </span>
                                    <span :class="currentTheme === 'cli' ? 'text-[#ff3333] text-[10px]' : 'text-[10px] text-rose-600 bg-rose-50 p-1 rounded-md'">🛑 OFF</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center space-x-3">
                                    <!-- Toggle Status Form -->
                                    <form action="{{ route('schedules.toggle', $schedule->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                :class="currentTheme === 'cli' ? 'border border-[#33ff00] px-2 py-0.5 text-[10px] hover:bg-[#33ff00] hover:text-[#0a0a0a]' : 'text-xs text-teal-600 hover:underline font-bold'">
                                            <span x-text="currentTheme === 'cli' ? '[SWITCH]' : 'Ubah'"></span>
                                        </button>
                                    </form>

                                    <!-- Delete Form -->
                                    <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus aturan jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                :class="currentTheme === 'cli' ? 'border border-[#ff3333] px-2 py-0.5 text-[10px] text-[#ff3333] hover:bg-[#ff3333] hover:text-[#0a0a0a]' : 'text-xs text-rose-500 hover:text-rose-700 font-bold'">
                                            <span x-text="currentTheme === 'cli' ? '[DEL]' : '🗑️ Hapus'"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400 font-medium italic">
                                Belum ada aturan jadwal yang dikonfigurasi. Klik tombol "+ Tambah Jadwal Baru" di atas.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH JADWAL -->
    <div x-show="modalTambah" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs"
         @keydown.escape.window="modalTambah = false">
        
        <div :class="currentTheme === 'cli' ? 'bg-[#0a0a0a] border-2 border-[#33ff00] text-[#33ff00] font-mono rounded-none' : 'bg-white rounded-3xl text-slate-900 font-sans border border-slate-200'"
             class="max-w-lg w-full p-6 shadow-2xl space-y-5 transform transition-all"
             @click.away="modalTambah = false">
            
            <div :class="currentTheme === 'cli' ? 'border-b border-[#1f521f] pb-3' : 'border-b border-slate-100 pb-4'" class="flex justify-between items-center">
                <h3 :class="currentTheme === 'cli' ? 'font-mono font-bold text-[#33ff00] uppercase text-base cli-glow' : 'font-outfit font-black text-lg text-slate-900'">
                    <span x-text="currentTheme === 'cli' ? '> CREATE_NEW_CRON_RULE' : 'Tambah Jadwal ON/OFF Baru'"></span>
                </h3>
                <button @click="modalTambah = false" :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-400'" class="font-bold text-xl">&times;</button>
            </div>

            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="modal_label" :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-600'" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5">Nama / Label Jadwal</label>
                    <input type="text" id="modal_label" name="label" required placeholder="Contoh: Shift Pagi (07:00 - 15:00)" 
                           :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] text-[#33ff00] font-mono rounded-none text-xs' : 'bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm'"
                           class="w-full px-4 py-3 placeholder-slate-500 focus:outline-none focus:border-teal-500 font-medium">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="modal_start" :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5">Jam Mulai (ON)</label>
                        <input type="time" id="modal_start" name="start_time" required 
                               :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] text-[#33ff00] font-mono rounded-none text-xs' : 'bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm'"
                               class="w-full px-4 py-3 font-mono focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label for="modal_end" :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5">Jam Selesai (OFF)</label>
                        <input type="time" id="modal_end" name="end_time" required 
                               :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] text-[#33ff00] font-mono rounded-none text-xs' : 'bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm'"
                               class="w-full px-4 py-3 font-mono focus:outline-none focus:border-teal-500">
                    </div>
                </div>

                <div class="flex space-x-3 pt-3">
                    <button type="button" @click="modalTambah = false" 
                            :class="currentTheme === 'cli' ? 'border border-[#1f521f] text-[#33ff00] rounded-none font-mono' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl'"
                            class="w-1/3 py-3 font-bold text-xs uppercase tracking-wider">
                        Batal
                    </button>
                    <button type="submit" 
                            :class="currentTheme === 'cli' ? 'border border-[#33ff00] bg-[#33ff00] text-[#0a0a0a] font-mono rounded-none' : 'bg-teal-600 hover:bg-teal-700 text-white rounded-2xl shadow-md'"
                            class="w-2/3 py-3 font-extrabold text-xs uppercase tracking-wider">
                        + Simpan Jadwal
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
