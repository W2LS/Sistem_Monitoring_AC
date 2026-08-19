<!-- SECTION 4: MANAGEMENT PENJADWALAN OTOMATIS AC -->
<div class="space-y-6">
    
    <!-- PAGE HEADER & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <h2 class="font-outfit font-black text-2xl text-slate-800 uppercase tracking-wide flex items-center space-x-2">
                <span class="text-teal-600">⏰</span>
                <span>Penjadwalan ON/OFF AC</span>
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Atur jadwal otomatis untuk mengontrol penyalaan & pemadaman AC sesuai siklus kerja ruang server.
            </p>
        </div>

        <button 
            @click="modalJadwalOpen = true; editMode = false;" 
            class="bg-teal-600 hover:bg-teal-500 text-white text-xs font-black py-2.5 px-4 rounded-xl shadow-md transition duration-200 flex items-center space-x-2 shrink-0">
            <span class="text-base leading-none">+</span>
            <span>Tambah Jadwal Baru</span>
        </button>
    </div>

    <!-- MAIN SCHEDULING TABLE CONTAINER -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-black uppercase text-slate-400 tracking-wider">
                        <th class="py-4 px-6">No.</th>
                        <th class="py-4 px-6">Nama Jadwal</th>
                        <th class="py-4 px-6">Unit AC</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Jadwal ON</th>
                        <th class="py-4 px-6">Jadwal OFF</th>
                        <th class="py-4 px-6">Hari</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-bold text-slate-700">
                    
                    <!-- Row 1: Jam Kerja (Panasonic 1) -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-6 font-mono text-slate-400">1</td>
                        <td class="py-4 px-6 font-black text-slate-800">Jadwal Kerja</td>
                        <td class="py-4 px-6">
                            <span class="bg-teal-50 text-teal-700 border border-teal-200 px-2.5 py-1 rounded-lg text-[11px] font-extrabold uppercase">
                                PANASONIC 1
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-700">
                                Aktif
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-1.5 font-mono text-slate-800">
                                <span class="text-emerald-500 font-extrabold">08:00</span>
                                <span class="text-[10px] text-emerald-600 bg-emerald-50 p-1 rounded-md">⚡ ON</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-1.5 font-mono text-slate-800">
                                <span class="text-rose-500 font-extrabold">17:00</span>
                                <span class="text-[10px] text-rose-600 bg-rose-50 p-1 rounded-md">🛑 OFF</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">Senin - Jumat</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="p-1.5 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition" title="Edit">
                                    ✏️
                                </button>
                                <button class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2: Jam Malam (Panasonic 2) -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-6 font-mono text-slate-400">2</td>
                        <td class="py-4 px-6 font-black text-slate-800">Jadwal Malam</td>
                        <td class="py-4 px-6">
                            <span class="bg-cyan-50 text-cyan-700 border border-cyan-200 px-2.5 py-1 rounded-lg text-[11px] font-extrabold uppercase">
                                PANASONIC 2
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-700">
                                Aktif
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-1.5 font-mono text-slate-800">
                                <span class="text-emerald-500 font-extrabold">18:00</span>
                                <span class="text-[10px] text-emerald-600 bg-emerald-50 p-1 rounded-md">⚡ ON</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-1.5 font-mono text-slate-800">
                                <span class="text-rose-500 font-extrabold">07:00</span>
                                <span class="text-[10px] text-rose-600 bg-rose-50 p-1 rounded-md">🛑 OFF</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">Senin - Minggu</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="p-1.5 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition" title="Edit">
                                    ✏️
                                </button>
                                <button class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3: Jadwal Weekend (Panasonic 1) -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-6 font-mono text-slate-400">3</td>
                        <td class="py-4 px-6 font-black text-slate-800">Jadwal Weekend</td>
                        <td class="py-4 px-6">
                            <span class="bg-teal-50 text-teal-700 border border-teal-200 px-2.5 py-1 rounded-lg text-[11px] font-extrabold uppercase">
                                PANASONIC 1
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-slate-100 text-slate-500">
                                Nonaktif
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-1.5 font-mono text-slate-800">
                                <span class="text-emerald-500 font-extrabold">09:00</span>
                                <span class="text-[10px] text-emerald-600 bg-emerald-50 p-1 rounded-md">⚡ ON</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-1.5 font-mono text-slate-800">
                                <span class="text-rose-500 font-extrabold">17:00</span>
                                <span class="text-[10px] text-rose-600 bg-rose-50 p-1 rounded-md">🛑 OFF</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">Sabtu - Minggu</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="p-1.5 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition" title="Edit">
                                    ✏️
                                </button>
                                <button class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>
