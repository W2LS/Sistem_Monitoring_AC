<!-- Schedule List Card -->
<div class="lg:col-span-2 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
    <h3 class="font-outfit font-extrabold text-lg text-slate-800 mb-6 flex items-center space-x-2">
        <svg class="w-5 h-5 text-teal-650" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        <span>Daftar Jadwal Penjadwalan</span>
    </h3>
    
    <div class="space-y-3">
        @forelse($schedules as $schedule)
            <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-4 flex items-center justify-between transition-all hover:bg-slate-50">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm leading-snug">{{ $schedule->label }}</h4>
                        <p class="text-xs text-slate-400 font-semibold font-mono mt-0.5">
                            {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Status Toggle -->
                    <form action="{{ route('schedules.toggle', $schedule->id) }}" method="POST" id="toggle-form-{{ $schedule->id }}">
                        @csrf
                        @method('PATCH')
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" onchange="document.getElementById('toggle-form-{{ $schedule->id }}').submit()" class="sr-only peer" {{ $schedule->is_active ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:bg-teal-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                        </label>
                    </form>

                    <!-- Delete button -->
                    <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-2 bg-white hover:bg-red-50 border border-slate-200 hover:border-red-200 rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-slate-400 font-semibold text-sm">Belum ada jadwal yang diatur.</div>
        @endforelse
    </div>
</div>
