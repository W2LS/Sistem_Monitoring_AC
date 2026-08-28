<!-- SECTION 2: PUSAT PENJADWALAN & ROTASI OTOMATIS AC 1 & AC 2 (PALETTE: #1D1616, #8E1616, #D84040, #EEEEEE) -->
<div class="space-y-6 pb-24" x-data="{ modalTambah: false }">
    
    <!-- PAGE HEADER & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#8E1616]/20 pb-4">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Otomasi Hardware RTC DS3231</span>
            <h2 class="text-3xl font-black text-[#1D1616] tracking-tight">
                Pusat Penjadwalan & Rotasi AC
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Atur jadwal pergantian shift 12 jam otomatis untuk mendinginkan server secara bergiliran.
            </p>
        </div>

        <button 
            @click="modalTambah = true" 
            class="bg-[#D84040] hover:bg-[#8E1616] text-white rounded-[24px] text-xs font-black uppercase tracking-wider py-3.5 px-6 shadow-lg shadow-[#D84040]/30 transition flex items-center space-x-2 shrink-0 cursor-pointer">
            <span class="text-base leading-none font-black">+</span>
            <span>Tambah Aturan Jadwal</span>
        </button>
    </div>

    <!-- 2 VISUAL ROTATION PRESETS HERO (40px Radius) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Shift Siang Card (AC 1) -->
        <div class="bg-white rounded-[40px] p-6 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 space-y-4 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#D84040]/10 rounded-full blur-xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-[20px] bg-[#EEEEEE] text-[#D84040] font-black text-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">Jadwal Aktif AC 1</span>
                        <h3 class="text-lg font-black text-[#1D1616]">Panasonic 1 (Lampu Bawah)</h3>
                    </div>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[11px] font-black uppercase">
                    Aktif
                </span>
            </div>

            <div class="bg-[#EEEEEE] rounded-[24px] p-4 flex items-center justify-between border border-[#8E1616]/15">
                <div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Aturan Jadwal Aktif</span>
                    <span class="text-base sm:text-lg font-black text-[#1D1616] font-mono">{{ $shiftAc1 ?? 'Shift Siang (06:00 - 18:00 WIB)' }}</span>
                </div>
                <span class="text-xs font-bold text-[#8E1616] bg-white px-3 py-1 rounded-[12px] shadow-2xs shrink-0">
                    AC 1
                </span>
            </div>
            
            <p class="text-[11px] text-slate-500 font-medium">
                Target: <strong>Panasonic 1 (GPIO 17)</strong> mendinginkan server sesuai jadwal aktif.
            </p>
        </div>

        <!-- Shift Malam Card (AC 2) -->
        <div class="bg-white rounded-[40px] p-6 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 space-y-4 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#8E1616]/10 rounded-full blur-xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-[20px] bg-[#EEEEEE] text-[#8E1616] font-black text-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">Jadwal Aktif AC 2</span>
                        <h3 class="text-lg font-black text-[#1D1616]">Panasonic 2 (Lampu Atas)</h3>
                    </div>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[11px] font-black uppercase">
                    Aktif
                </span>
            </div>

            <div class="bg-[#EEEEEE] rounded-[24px] p-4 flex items-center justify-between border border-[#8E1616]/15">
                <div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Aturan Jadwal Aktif</span>
                    <span class="text-base sm:text-lg font-black text-[#1D1616] font-mono">{{ $shiftAc2 ?? 'Shift Malam (18:00 - 06:00 WIB)' }}</span>
                </div>
                <span class="text-xs font-bold text-[#8E1616] bg-white px-3 py-1 rounded-[12px] shadow-2xs shrink-0">
                    AC 2
                </span>
            </div>
            
            <p class="text-[11px] text-slate-500 font-medium">
                Target: <strong>Panasonic 2 (GPIO 27)</strong> mendinginkan server sesuai jadwal aktif.
            </p>
        </div>

    </div>

    <!-- MAIN SCHEDULING TABLE CONTAINER -->
    <div class="bg-white rounded-[40px] shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 p-6 space-y-4 overflow-hidden">
        <div class="flex items-center justify-between pb-3 border-b border-[#8E1616]/15">
            <h3 class="text-sm font-black uppercase tracking-wider text-[#1D1616]">
                Tabel Aturan Penjadwalan Aktif di Database
            </h3>
            <span class="text-xs font-bold text-slate-400">Total: {{ count($schedules) }} Aturan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#EEEEEE] text-slate-600 text-[10px] font-black uppercase tracking-wider rounded-[16px]">
                        <th class="py-3.5 px-4 rounded-l-[16px]">No.</th>
                        <th class="py-3.5 px-4">Nama Label Jadwal</th>
                        <th class="py-3.5 px-4">Target AC</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Jam Mulai (ON)</th>
                        <th class="py-3.5 px-4">Jam Selesai (OFF)</th>
                        <th class="py-3.5 px-4 text-center rounded-r-[16px]">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-bold text-[#1D1616]">
                    @forelse($schedules as $index => $schedule)
                        <tr class="hover:bg-[#EEEEEE]/50 transition">
                            <td class="py-4 px-4 font-mono opacity-50">{{ $index + 1 }}</td>
                            <td class="py-4 px-4 font-black text-sm">
                                {{ $schedule->label }}
                            </td>
                            <td class="py-4 px-4">
                                @if(($schedule->target_ac ?? 'all') === '1')
                                    <span class="inline-flex items-center px-2.5 py-1 font-bold bg-rose-50 text-[#D84040] border border-rose-200 rounded-full text-[10px]">
                                        🔵 Panasonic 1
                                    </span>
                                @elseif(($schedule->target_ac ?? 'all') === '2')
                                    <span class="inline-flex items-center px-2.5 py-1 font-bold bg-rose-50 text-[#8E1616] border border-rose-200 rounded-full text-[10px]">
                                        🔴 Panasonic 2
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 font-bold bg-slate-100 text-[#1D1616] rounded-full text-[10px]">
                                        ⚡ Semua AC
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($schedule->is_active)
                                    <span class="inline-flex items-center px-3 py-1 font-black bg-emerald-100 text-emerald-800 rounded-full text-[10px] uppercase">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 font-black bg-slate-100 text-slate-500 rounded-full text-[10px] uppercase">
                                        Non-Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 font-mono font-black text-[#D84040]">
                                {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }} WIB
                            </td>
                            <td class="py-4 px-4 font-mono font-black text-[#8E1616]">
                                {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center justify-center space-x-3">
                                    <form action="{{ route('schedules.toggle', $schedule->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs text-[#8E1616] hover:text-[#D84040] font-black underline cursor-pointer">
                                            Ubah Status
                                        </button>
                                    </form>

                                    <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus aturan jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-black cursor-pointer">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400 italic">
                                Belum ada aturan jadwal. Klik tombol "+ Tambah Aturan Jadwal" di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH JADWAL -->
    <div x-show="modalTambah" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="modalTambah = false">
        
        <div class="bg-white rounded-[40px] text-[#1D1616] font-sans border border-[#8E1616]/30 max-w-lg w-full p-8 shadow-2xl space-y-6 transform transition-all"
             @click.away="modalTambah = false">
            
            <div class="border-b border-[#8E1616]/20 pb-4 flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Formulir Otomasi</span>
                    <h3 class="text-2xl font-black text-[#1D1616]">
                        Tambah Jadwal ON/OFF Baru
                    </h3>
                </div>
                <button @click="modalTambah = false" class="text-slate-400 hover:text-[#D84040] font-bold text-2xl cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="modal_label" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-[#1D1616]">Nama / Label Jadwal</label>
                    <input type="text" id="modal_label" name="label" required placeholder="Contoh: Shift Pagi (06:00 - 18:00)" 
                           class="w-full bg-[#EEEEEE] border border-[#8E1616]/30 rounded-[20px] text-[#1D1616] text-sm px-4 py-3 placeholder-slate-400 focus:outline-none focus:border-[#D84040] font-bold">
                </div>

                <div>
                    <label for="modal_target_ac" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-[#1D1616]">Target Unit Pendingin</label>
                    <select id="modal_target_ac" name="target_ac" required
                            class="w-full bg-[#EEEEEE] border border-[#8E1616]/30 rounded-[20px] text-[#1D1616] text-sm px-4 py-3 font-bold focus:outline-none focus:border-[#D84040]">
                        <option value="1">🔵 Panasonic 1 (AC 1 / Lampu Bawah)</option>
                        <option value="2">🔴 Panasonic 2 (AC 2 / Lampu Atas)</option>
                        <option value="all">⚡ Keduanya (Semua Unit AC)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="modal_start" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-[#1D1616]">Jam Mulai (ON)</label>
                        <input type="time" id="modal_start" name="start_time" required 
                               class="w-full bg-[#EEEEEE] border border-[#8E1616]/30 rounded-[20px] text-[#1D1616] text-sm px-4 py-3 font-mono font-bold focus:outline-none focus:border-[#D84040]">
                    </div>
                    <div>
                        <label for="modal_end" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-[#1D1616]">Jam Selesai (OFF)</label>
                        <input type="time" id="modal_end" name="end_time" required 
                               class="w-full bg-[#EEEEEE] border border-[#8E1616]/30 rounded-[20px] text-[#1D1616] text-sm px-4 py-3 font-mono font-bold focus:outline-none focus:border-[#D84040]">
                    </div>
                </div>

                <div class="flex space-x-3 pt-3">
                    <button type="button" @click="modalTambah = false" 
                            class="w-1/3 py-3.5 font-black text-xs uppercase tracking-wider bg-[#EEEEEE] hover:bg-slate-200 text-[#1D1616] rounded-[20px] transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" 
                            class="w-2/3 py-3.5 font-black text-xs uppercase tracking-wider bg-[#D84040] hover:bg-[#8E1616] text-white rounded-[20px] shadow-lg shadow-[#D84040]/30 transition cursor-pointer">
                        + Simpan Aturan Jadwal
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
