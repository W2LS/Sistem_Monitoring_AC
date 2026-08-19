@props([
    'id' => 1,
    'pin' => 18,
    'name' => 'AC 1 (Panel Bawah)',
    'color' => 'teal',
    'schedules' => []
])

@php
    $bgCard = $color === 'teal' ? 'border-teal-200/80 hover:border-teal-400' : 'border-cyan-200/80 hover:border-cyan-400';
    $badgeBg = $color === 'teal' ? 'bg-teal-50 text-teal-700 border-teal-200' : 'bg-cyan-50 text-cyan-700 border-cyan-200';
    $accentText = $color === 'teal' ? 'text-teal-600' : 'text-cyan-600';
    $accentBg = $color === 'teal' ? 'bg-teal-500' : 'bg-cyan-500';
@endphp

<!-- CLEAN CARD UNTUK UNIT AC {{ $id }} DENGAN MODAL PENGATURAN BUILT-IN -->
<div x-data="{ showModal: false }" class="bg-white border-2 {{ $bgCard }} rounded-3xl p-6 shadow-sm transition-all duration-200 flex flex-col justify-between space-y-6 relative">
    
    <!-- HEADER CARD: NAMA AC + BADGE STATUS + SWITCH ON/OFF -->
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-2xl {{ $accentBg }} text-white font-black text-lg flex items-center justify-center shadow-md font-outfit">
                AC{{ $id }}
            </div>
            <div>
                <h3 class="font-outfit font-black text-slate-800 text-lg uppercase tracking-wide">
                    {{ $name }}
                </h3>
                <div class="flex items-center space-x-2 mt-0.5">
                    <span id="ac{{ $id }}-badge-label" class="px-2.5 py-0.5 rounded-md text-[11px] font-extrabold border uppercase tracking-wider {{ $badgeBg }}">
                        Offline
                    </span>
                    <span class="text-xs text-slate-400 font-semibold">ESP32 Pin {{ $pin }}</span>
                </div>
            </div>
        </div>

        <!-- IOS TOGGLE SWITCH ON/OFF -->
        <div class="flex items-center space-x-3 bg-slate-100 px-4 py-2 rounded-2xl border border-slate-200">
            <span id="ac{{ $id }}-switch-text" class="text-xs font-black uppercase tracking-wider text-slate-400">OFF</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="ac{{ $id }}-switch" onchange="sendAcControlViaSwitch({{ $id }}, this)" class="sr-only peer">
                <div class="w-14 h-7 bg-slate-300 rounded-full peer peer-focus:ring-2 peer-focus:ring-teal-400 transition-all peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-7"></div>
            </label>
        </div>
    </div>

    <!-- 3 KARTU INFORMASI PENTING (Arus, Suhu/Pendingin, Jam Atur AC) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <!-- 1. ARUS LISTRIK (AMPERE ⚡) -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Arus Listrik</span>
                <span class="text-amber-500 font-black text-sm">⚡</span>
            </div>
            <div class="my-2">
                <span id="ac{{ $id }}-current" class="font-outfit font-black text-2xl text-slate-900 font-mono">0.0000</span>
                <span class="text-xs font-bold text-slate-500">Ampere</span>
            </div>
            <span class="text-[10px] text-slate-400 font-medium">Recorded: <span id="ac{{ $id }}-time">Live</span></span>
        </div>

        <!-- 2. INFORMASI PENDINGIN / SUHU AC -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Info Pendingin</span>
                <span class="text-cyan-500 font-black text-sm">❄️</span>
            </div>
            <div class="my-2">
                <span class="font-outfit font-black text-2xl text-slate-900">24°C</span>
                <span class="text-xs font-bold text-slate-500">Cooling Mode</span>
            </div>
            <span class="text-[10px] text-teal-600 font-bold uppercase tracking-wider">Suhu Optimal</span>
        </div>

        <!-- 3. JAM ATUR AC DENGAN TOMBOL PENGATURAN BUILT-IN -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex flex-col justify-between hover:bg-slate-100/80 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Jam Atur AC</span>
                <button @click="showModal = true" type="button" class="text-xs font-extrabold text-teal-600 hover:text-teal-800 flex items-center space-x-1 bg-teal-50 hover:bg-teal-100 px-2 py-0.5 rounded-lg border border-teal-200 transition-colors">
                    <span>⚙️</span>
                    <span>Atur Jam</span>
                </button>
            </div>
            <div class="my-2">
                @if(count($schedules) > 0)
                    <span class="font-outfit font-black text-lg text-slate-900 font-mono">
                        {{ \Illuminate\Support\Carbon::parse($schedules[0]->start_time)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($schedules[0]->end_time)->format('H:i') }}
                    </span>
                @else
                    <span class="font-outfit font-black text-lg text-slate-900 font-mono">08:00 - 18:00</span>
                @endif
                <span class="text-[11px] font-semibold text-slate-500 block truncate">
                    @if(count($schedules) > 0) {{ $schedules[0]->label }} @else Jam Kantor Otomatis @endif
                </span>
            </div>
            <button @click="showModal = true" type="button" class="text-[10px] text-teal-700 font-bold hover:underline text-left">
                Klik untuk ubah jadwal &rarr;
            </button>
        </div>

    </div>

    <!-- MODAL DIALOG PENGATURAN JAM PENJADWALAN AC (Terbuka saat tombol Atur Jam diklik) -->
    <div x-show="showModal" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
         @keydown.escape.window="showModal = false">
        
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-6 border border-slate-200 transform transition-all"
             @click.away="showModal = false">
            
            <!-- MODAL HEADER -->
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-700 font-bold flex items-center justify-center text-lg">
                        ⚙️
                    </div>
                    <div>
                        <h3 class="font-outfit font-black text-lg text-slate-900">
                            Pengaturan Jam Penjadwalan {{ $name }}
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">Atur jam otomatis ON/OFF untuk unit AC ini.</p>
                    </div>
                </div>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-700 font-bold text-xl p-1 rounded-lg">&times;</button>
            </div>

            <!-- FORM TAMBAH JADWAL BARU -->
            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="label_{{ $id }}" class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-1.5">Label Penjadwalan</label>
                    <input type="text" id="label_{{ $id }}" name="label" required placeholder="Contoh: Shift Pagi {{ $name }}" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 font-medium">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time_{{ $id }}" class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-1.5">Jam Mulai (ON)</label>
                        <input type="time" id="start_time_{{ $id }}" name="start_time" required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-mono text-slate-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    </div>
                    <div>
                        <label for="end_time_{{ $id }}" class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-1.5">Jam Selesai (OFF)</label>
                        <input type="time" id="end_time_{{ $id }}" name="end_time" required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-mono text-slate-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    </div>
                </div>

                <div class="flex space-x-3 pt-2">
                    <button type="button" @click="showModal = false" class="w-1/3 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-2xl transition-all">
                        Batal
                    </button>
                    <button type="submit" class="w-2/3 py-3 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-2xl transition-all shadow-md">
                        + Simpan Jam Penjadwalan
                    </button>
                </div>
            </form>

            <!-- DAFTAR JADWAL AKTIF HARI INI -->
            <div class="border-t border-slate-100 pt-4 space-y-2">
                <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Daftar Jadwal Penjadwalan Aktif:</h4>
                <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                    @forelse($schedules as $schedule)
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-extrabold text-slate-800 block">{{ $schedule->label }}</span>
                                <span class="font-mono text-slate-500 font-bold">
                                    {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }} WIB - {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                                </span>
                            </div>
                            <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 font-bold">Hapus</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Belum ada jadwal yang tersimpan.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
