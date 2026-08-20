<!-- SECTION 2: PUSAT PENJADWALAN & ROTASI OTOMATIS AC 1 & AC 2 (SOPHISTICATED NEO-CARD) -->
<div class="space-y-6 pb-24" x-data="{ modalTambah: false }">
    
    <!-- PAGE HEADER & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#b7c6c2]/30 pb-4">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#b7c6c2] block">Otomasi Hardware RTC DS3231</span>
            <h2 class="text-3xl font-black text-[#171e19] tracking-tight">
                Pusat Penjadwalan & Rotasi AC
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Atur jadwal pergantian shift 12 jam otomatis untuk mendinginkan server secara bergiliran.
            </p>
        </div>

        <button 
            @click="modalTambah = true" 
            class="bg-[#ca0013] hover:bg-[#b00010] text-white rounded-[24px] text-xs font-black uppercase tracking-wider py-3.5 px-6 shadow-lg shadow-[#ca0013]/30 transition flex items-center space-x-2 shrink-0 cursor-pointer">
            <span class="text-base leading-none font-black">+</span>
            <span>Tambah Aturan Jadwal</span>
        </button>
    </div>

    <!-- 2 VISUAL ROTATION PRESETS HERO (40px Radius) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Shift Siang Card -->
        <div class="bg-white rounded-[40px] p-6 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.08)] border border-[#b7c6c2]/30 space-y-4 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#ca0013]/5 rounded-full blur-xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-[20px] bg-rose-50 text-[#ca0013] font-black text-xl flex items-center justify-center">
                        ☀️
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#b7c6c2]">Siklus Utama</span>
                        <h3 class="text-lg font-black text-[#171e19]">Shift Siang (AC 1)</h3>
                    </div>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[11px] font-black uppercase">
                    Aktif
                </span>
            </div>

            <div class="bg-[#eeebe3]/50 rounded-[24px] p-4 flex items-center justify-between border border-[#b7c6c2]/20">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Jam Operasional</span>
                    <span class="text-xl font-black text-[#171e19] font-mono">06:00 - 18:00 WIB</span>
                </div>
                <span class="text-xs font-bold text-slate-600 bg-white px-3 py-1 rounded-[12px] shadow-2xs">
                    Durasi: 12 Jam
                </span>
            </div>
            
            <p class="text-[11px] text-slate-500 font-medium">
                Target: <strong>Panasonic 1 (Relay Pin 18)</strong> bertugas mendinginkan server di siang hari.
            </p>
        </div>

        <!-- Shift Malam Card -->
        <div class="bg-white rounded-[40px] p-6 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.08)] border border-[#b7c6c2]/30 space-y-4 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-cyan-500/5 rounded-full blur-xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-[20px] bg-cyan-50 text-cyan-700 font-black text-xl flex items-center justify-center">
                        🌙
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#b7c6c2]">Siklus Bergantian</span>
                        <h3 class="text-lg font-black text-[#171e19]">Shift Malam (AC 2)</h3>
                    </div>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[11px] font-black uppercase">
                    Aktif
                </span>
            </div>

            <div class="bg-[#eeebe3]/50 rounded-[24px] p-4 flex items-center justify-between border border-[#b7c6c2]/20">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Jam Operasional</span>
                    <span class="text-xl font-black text-[#171e19] font-mono">18:00 - 06:00 WIB</span>
                </div>
                <span class="text-xs font-bold text-slate-600 bg-white px-3 py-1 rounded-[12px] shadow-2xs">
                    Durasi: 12 Jam
                </span>
            </div>
            
            <p class="text-[11px] text-slate-500 font-medium">
                Target: <strong>Panasonic 2 (Relay Pin 19)</strong> bertugas mendinginkan server di malam hari.
            </p>
        </div>

    </div>

    <!-- MAIN SCHEDULING TABLE CONTAINER -->
    <div class="bg-white rounded-[40px] shadow-[0_20px_50px_-12px_rgba(0,0,0,0.08)] border border-[#b7c6c2]/30 p-6 space-y-4 overflow-hidden">
        <div class="flex items-center justify-between pb-3 border-b border-[#b7c6c2]/20">
            <h3 class="text-sm font-black uppercase tracking-wider text-[#171e19]">
                Tabel Aturan Penjadwalan Aktif di Database
            </h3>
            <span class="text-xs font-bold text-slate-400">Total: {{ count($schedules) }} Aturan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#eeebe3]/60 text-slate-500 text-[10px] font-black uppercase tracking-wider rounded-[16px]">
                        <th class="py-3.5 px-4 rounded-l-[16px]">No.</th>
                        <th class="py-3.5 px-4">Nama Label Jadwal</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Jam Mulai (ON)</th>
                        <th class="py-3.5 px-4">Jam Selesai (OFF)</th>
                        <th class="py-3.5 px-4 text-center rounded-r-[16px]">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-bold text-[#171e19]">
                    @forelse($schedules as $index => $schedule)
                        <tr class="hover:bg-[#eeebe3]/30 transition">
                            <td class="py-4 px-4 font-mono opacity-50">{{ $index + 1 }}</td>
                            <td class="py-4 px-4 font-black text-sm">
                                {{ $schedule->label }}
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
                            <td class="py-4 px-4 font-mono font-black text-[#ca0013]">
                                {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }} WIB
                            </td>
                            <td class="py-4 px-4 font-mono font-black text-slate-600">
                                {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center justify-center space-x-3">
                                    <form action="{{ route('schedules.toggle', $schedule->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs text-[#171e19] hover:text-[#ca0013] font-black underline cursor-pointer">
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
                            <td colspan="6" class="text-center py-8 text-slate-400 italic">
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
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
         @keydown.escape.window="modalTambah = false">
        
        <div class="bg-white rounded-[40px] text-[#171e19] font-sans border border-[#b7c6c2]/30 max-w-lg w-full p-8 shadow-2xl space-y-6 transform transition-all"
             @click.away="modalTambah = false">
            
            <div class="border-b border-[#b7c6c2]/20 pb-4 flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#b7c6c2] block">Formulir Otomasi</span>
                    <h3 class="text-2xl font-black text-[#171e19]">
                        Tambah Jadwal ON/OFF Baru
                    </h3>
                </div>
                <button @click="modalTambah = false" class="text-slate-400 hover:text-slate-700 font-bold text-2xl cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="modal_label" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-[#171e19]">Nama / Label Jadwal</label>
                    <input type="text" id="modal_label" name="label" required placeholder="Contoh: Shift Pagi (06:00 - 18:00)" 
                           class="w-full bg-[#eeebe3]/50 border border-[#b7c6c2]/40 rounded-[20px] text-[#171e19] text-sm px-4 py-3 placeholder-slate-400 focus:outline-none focus:border-[#ca0013] font-bold">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="modal_start" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-[#171e19]">Jam Mulai (ON)</label>
                        <input type="time" id="modal_start" name="start_time" required 
                               class="w-full bg-[#eeebe3]/50 border border-[#b7c6c2]/40 rounded-[20px] text-[#171e19] text-sm px-4 py-3 font-mono font-bold focus:outline-none focus:border-[#ca0013]">
                    </div>
                    <div>
                        <label for="modal_end" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-[#171e19]">Jam Selesai (OFF)</label>
                        <input type="time" id="modal_end" name="end_time" required 
                               class="w-full bg-[#eeebe3]/50 border border-[#b7c6c2]/40 rounded-[20px] text-[#171e19] text-sm px-4 py-3 font-mono font-bold focus:outline-none focus:border-[#ca0013]">
                    </div>
                </div>

                <div class="flex space-x-3 pt-3">
                    <button type="button" @click="modalTambah = false" 
                            class="w-1/3 py-3.5 font-black text-xs uppercase tracking-wider bg-[#eeebe3] hover:bg-slate-200 text-[#171e19] rounded-[20px] transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" 
                            class="w-2/3 py-3.5 font-black text-xs uppercase tracking-wider bg-[#ca0013] hover:bg-[#b00010] text-white rounded-[20px] shadow-lg shadow-[#ca0013]/30 transition cursor-pointer">
                        + Simpan Aturan Jadwal
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
