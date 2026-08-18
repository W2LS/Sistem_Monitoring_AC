<!-- Schedule Form Card -->
<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm h-fit">
    <h3 class="font-outfit font-extrabold text-lg text-slate-800 mb-6 flex items-center space-x-2">
        <svg class="w-5 h-5 text-teal-650" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        <span>Tambah Jadwal AC</span>
    </h3>
    <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="label" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Label Jadwal</label>
            <input type="text" id="label" name="label" required placeholder="Contoh: Shift Pagi, Lembur" 
                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="start_time" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Jam Mulai</label>
                <input type="time" id="start_time" name="start_time" required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all">
            </div>
            <div>
                <label for="end_time" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Jam Selesai</label>
                <input type="time" id="end_time" name="end_time" required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all">
            </div>
        </div>
        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 active:scale-[0.98] text-white font-bold text-sm uppercase tracking-wider rounded-2xl transition-all duration-200 shadow-md shadow-teal-500/10">
            Simpan Jadwal
        </button>
    </form>
</div>
