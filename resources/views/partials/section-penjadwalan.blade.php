<!-- SECTION 4: MANAGEMENT PENJADWALAN OTOMATIS AC (MODERN INDUSTRIAL GUI) -->
<div class="space-y-6" x-data="{ modalTambah: false }">
    
    <!-- PAGE HEADER & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4 font-sans text-slate-800">
        <div>
            <h2 class="font-outfit font-black text-2xl uppercase tracking-wide flex items-center space-x-2">
                <span>⏰</span>
                <span>Penjadwalan Rotasi ON/OFF AC</span>
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Atur jadwal otomatis 12 jam untuk mengontrol penyalaan & pemadaman AC sesuai siklus kerja ruang server.
            </p>
        </div>

        <button 
            @click="modalTambah = true" 
            class="bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs shadow-md font-black py-2.5 px-4 transition duration-200 flex items-center space-x-2 shrink-0 cursor-pointer">
            <span class="text-base leading-none">+</span>
            <span>Tambah Jadwal Baru</span>
        </button>
    </div>

    <!-- MAIN SCHEDULING TABLE CONTAINER -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm font-sans overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[11px] font-black uppercase tracking-wider">
                        <th class="py-4 px-6">No.</th>
                        <th class="py-4 px-6">Nama Jadwal</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Jadwal ON</th>
                        <th class="py-4 px-6">Jadwal OFF</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-bold">
                    
                    @forelse($schedules as $index => $schedule)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-6 font-mono opacity-60">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-black text-slate-800">
                                {{ $schedule->label }}
                            </td>
                            <td class="py-4 px-6">
                                @if($schedule->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 font-extrabold bg-emerald-100 text-emerald-700 rounded-full text-[11px]">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 font-extrabold bg-slate-100 text-slate-500 rounded-full text-[11px]">
                                        Non-Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-1.5 font-mono">
                                    <span class="text-emerald-500 font-extrabold">
                                        {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }}
                                    </span>
                                    <span class="text-[10px] text-emerald-600 bg-emerald-50 p-1 rounded-md">⚡ ON</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-1.5 font-mono">
                                    <span class="text-rose-500 font-extrabold">
                                        {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </span>
                                    <span class="text-[10px] text-rose-600 bg-rose-50 p-1 rounded-md">🛑 OFF</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center space-x-3">
                                    <!-- Toggle Status Form -->
                                    <form action="{{ route('schedules.toggle', $schedule->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs text-teal-600 hover:underline font-bold cursor-pointer">
                                            Ubah
                                        </button>
                                    </form>

                                    <!-- Delete Form -->
                                    <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus aturan jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-bold cursor-pointer">
                                            🗑️ Hapus
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
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
         @keydown.escape.window="modalTambah = false">
        
        <div class="bg-white rounded-3xl text-slate-900 font-sans border border-slate-200 max-w-lg w-full p-6 shadow-2xl space-y-5 transform transition-all"
             @click.away="modalTambah = false">
            
            <div class="border-b border-slate-100 pb-4 flex justify-between items-center">
                <h3 class="font-outfit font-black text-lg text-slate-900">
                    Tambah Jadwal ON/OFF Baru
                </h3>
                <button @click="modalTambah = false" class="text-slate-400 hover:text-slate-700 font-bold text-xl">&times;</button>
            </div>

            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="modal_label" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-slate-600">Nama / Label Jadwal</label>
                    <input type="text" id="modal_label" name="label" required placeholder="Contoh: Shift Pagi (06:00 - 18:00)" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm px-4 py-3 placeholder-slate-500 focus:outline-none focus:border-teal-500 font-medium">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="modal_start" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-slate-600">Jam Mulai (ON)</label>
                        <input type="time" id="modal_start" name="start_time" required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm px-4 py-3 font-mono focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label for="modal_end" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-slate-600">Jam Selesai (OFF)</label>
                        <input type="time" id="modal_end" name="end_time" required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm px-4 py-3 font-mono focus:outline-none focus:border-teal-500">
                    </div>
                </div>

                <div class="flex space-x-3 pt-3">
                    <button type="button" @click="modalTambah = false" 
                            class="w-1/3 py-3 font-bold text-xs uppercase tracking-wider bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl">
                        Batal
                    </button>
                    <button type="submit" 
                            class="w-2/3 py-3 font-extrabold text-xs uppercase tracking-wider bg-teal-600 hover:bg-teal-700 text-white rounded-2xl shadow-md">
                        + Simpan Jadwal
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
