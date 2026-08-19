<!-- CLEAN FORM TAMBAH JADWAL AC -->
<div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm h-fit space-y-4">
    <h3 class="font-outfit font-black text-lg text-slate-800 flex items-center space-x-2 border-b border-slate-100 pb-3">
        <span class="text-teal-600 font-extrabold text-base">⏱️</span>
        <span>Atur Jam Penjadwalan AC</span>
    </h3>

    <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="label" class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-2">Label Penjadwalan</label>
            <input type="text" id="label" name="label" required placeholder="Contoh: Jam Kerja Kantor, Lembur Malam" 
                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all font-medium">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="start_time" class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-2">Jam Mulai (ON)</label>
                <input type="time" id="start_time" name="start_time" required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-mono text-slate-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all">
            </div>
            <div>
                <label for="end_time" class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-2">Jam Selesai (OFF)</label>
                <input type="time" id="end_time" name="end_time" required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-mono text-slate-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all">
            </div>
        </div>

        <button type="submit" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 active:scale-[0.98] text-white font-extrabold text-xs uppercase tracking-wider rounded-2xl transition-all duration-200 shadow-md">
            + Simpan Jam Penjadwalan
        </button>
    </form>
</div>
