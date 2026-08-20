@props([
    'id' => 1,
    'pin' => 18,
    'name' => 'Lampu Panel Bawah (AC 1)',
    'color' => 'teal',
    'schedules' => []
])

@php
    $bgCard = $color === 'teal' ? 'border-teal-200/80 hover:border-teal-400' : 'border-cyan-200/80 hover:border-cyan-400';
    $badgeBg = $color === 'teal' ? 'bg-teal-50 text-teal-700 border-teal-200' : 'bg-cyan-50 text-cyan-700 border-cyan-200';
    $accentBg = $color === 'teal' ? 'bg-teal-500 hover:bg-teal-600' : 'bg-cyan-500 hover:bg-cyan-600';
@endphp

<!-- KARTU KONTROL AC {{ $id }} (MODERN INDUSTRIAL GUI) -->
<div x-data="{ 
        showModal: false, 
        unitName: localStorage.getItem('ac_unit_name_{{ $id }}') || '{{ $name }}',
        saveName() {
            localStorage.setItem('ac_unit_name_{{ $id }}', this.unitName);
        }
     }" 
     class="bg-white border-2 {{ $bgCard }} rounded-3xl p-6 shadow-sm font-sans transition-all duration-200 flex flex-col justify-between space-y-5 relative">
    
    <!-- HEADER CARD: ICON PENGATURAN + NAMA AC + SWITCH ON/OFF -->
    <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            
            <!-- KOTAK ICON PENGATURAN -->
            <button @click="showModal = true" 
                    type="button" 
                    title="Klik untuk Pengaturan Jam & Nama Unit" 
                    class="w-12 h-12 rounded-2xl {{ $accentBg }} text-white shadow-md hover:scale-105 active:scale-95 transition-all flex items-center justify-center group cursor-pointer">
                <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </button>

            <!-- NAMA AC & BADGE STATUS -->
            <div>
                <h3 @click="showModal = true" 
                    class="font-outfit font-black text-slate-800 text-lg hover:text-teal-600 uppercase tracking-wide cursor-pointer transition-colors" 
                    x-text="unitName" 
                    title="Klik untuk ubah nama">
                </h3>
                <div class="flex items-center space-x-2 mt-0.5">
                    <span id="ac{{ $id }}-badge-label" 
                          class="rounded-md text-[11px] border {{ $badgeBg }} px-2 py-0.5 font-extrabold uppercase tracking-wider">
                        Offline
                    </span>
                    <span class="text-xs text-slate-400 font-semibold">
                        Pin Relay GPIO {{ $pin }}
                    </span>
                </div>
            </div>
        </div>

        <!-- TOGGLE SWITCH ON/OFF (IOS STYLE) -->
        <div class="flex items-center space-x-3 bg-slate-100 px-4 py-2 rounded-2xl border border-slate-200">
            <span id="ac{{ $id }}-switch-text" class="text-xs font-black uppercase tracking-wider text-slate-400">OFF</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="ac{{ $id }}-switch" onchange="sendAcControlViaSwitch({{ $id }}, this)" class="sr-only peer">
                <div class="w-14 h-7 bg-slate-300 rounded-full peer peer-focus:ring-2 peer-focus:ring-teal-400 transition-all peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-7"></div>
            </label>
        </div>
    </div>

    <!-- 2 KARTU INFORMASI PENTING (Arus Listrik & Jam Atur AC) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        
        <!-- 1. ARUS LISTRIK (AMPERE ⚡) -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                    Arus Listrik (ACS712)
                </span>
                <span class="text-amber-500 font-black text-sm">⚡</span>
            </div>
            <div class="my-2">
                <span id="ac{{ $id }}-current" class="text-2xl text-slate-900 font-outfit font-black font-mono">
                    0.0000
                </span>
                <span class="text-xs font-bold text-slate-500">Ampere</span>
            </div>
            <span class="text-[10px] text-slate-400 font-medium">
                Pembaruan: <span id="ac{{ $id }}-time" class="font-mono">Real-time</span>
            </span>
        </div>

        <!-- 2. JAM ATUR AC -->
        <div @click="showModal = true" 
             class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 hover:bg-slate-100/80 flex flex-col justify-between transition-colors cursor-pointer" 
             title="Klik untuk atur jam operasional">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                    Slot Penjadwalan
                </span>
                <span class="text-slate-500 font-black text-sm">⏱️</span>
            </div>
            <div class="my-2">
                @if(count($schedules) > 0)
                    <span class="text-lg text-slate-900 font-outfit font-black font-mono">
                        {{ \Illuminate\Support\Carbon::parse($schedules[0]->start_time)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($schedules[0]->end_time)->format('H:i') }}
                    </span>
                    <span class="text-[11px] font-semibold text-slate-500 block truncate mt-0.5">
                        {{ $schedules[0]->label }}
                    </span>
                @else
                    <span class="text-lg text-slate-900 font-outfit font-black font-mono">00:00 - 00:00</span>
                    <span class="text-[11px] text-slate-400 font-semibold block truncate mt-0.5">
                        Belum ada aturan aktif
                    </span>
                @endif
            </div>
            <span class="text-[10px] text-teal-600 font-bold">
                {{ count($schedules) > 0 ? '● Aturan Rotasi Aktif' : '○ Mode Standby' }}
            </span>
        </div>

    </div>

    <!-- MODAL DIALOG PENGATURAN -->
    <div x-show="showModal" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
         @keydown.escape.window="showModal = false">
        
        <div class="bg-white rounded-3xl text-slate-900 font-sans border border-slate-200 max-w-lg w-full p-6 shadow-2xl space-y-5 transform transition-all"
             @click.away="showModal = false">
            
            <!-- MODAL HEADER -->
            <div class="border-b border-slate-100 pb-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-lg">
                        ⚙️
                    </div>
                    <div>
                        <h3 class="font-outfit font-black text-lg text-slate-900">
                            Pengaturan Unit Perangkat (AC {{ $id }})
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">
                            Ubah nama unit & atur jam operasional AC.
                        </p>
                    </div>
                </div>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-700 font-bold text-xl">&times;</button>
            </div>

            <!-- FITUR 1: UBAH NAMA UNIT PERANGKAT -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2">
                <label for="unit_name_input_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-700">
                    ✏️ Ubah Nama Perangkat AC
                </label>
                <input type="text" id="unit_name_input_{{ $id }}" x-model="unitName" @input="saveName()" 
                       placeholder="Masukkan nama baru unit AC" 
                       class="w-full bg-white border border-slate-200 rounded-xl text-slate-800 text-sm px-4 py-2.5 font-bold focus:border-teal-500 focus:ring-2 focus:ring-teal-100 focus:outline-none">
                <p class="text-[10px] text-slate-400 font-semibold">Nama tersimpan otomatis secara real-time di browser.</p>
            </div>

            <!-- FITUR 2: FORM TAMBAH JADWAL ON/OFF AC -->
            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4 pt-2">
                @csrf
                <h4 class="text-slate-700 font-sans text-xs font-extrabold uppercase tracking-wider">
                    ⏱️ Tambah Jam Penjadwalan AC
                </h4>
                <div>
                    <label for="label_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-slate-600">Label Penjadwalan</label>
                    <input type="text" id="label_{{ $id }}" name="label" required placeholder="Contoh: Jam Kantor Utama" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm px-4 py-3 placeholder-slate-500 focus:outline-none focus:border-teal-500 font-medium">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-slate-600">Jam Mulai (ON)</label>
                        <input type="time" id="start_time_{{ $id }}" name="start_time" required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm px-4 py-3 font-mono focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label for="end_time_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-slate-600">Jam Selesai (OFF)</label>
                        <input type="time" id="end_time_{{ $id }}" name="end_time" required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm px-4 py-3 font-mono focus:outline-none focus:border-teal-500">
                    </div>
                </div>

                <div class="flex space-x-3 pt-2">
                    <button type="button" @click="showModal = false" 
                            class="w-1/3 py-3 font-bold text-xs uppercase tracking-wider transition-all bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl">
                        Tutup
                    </button>
                    <button type="submit" 
                            class="w-2/3 py-3 font-extrabold text-xs uppercase tracking-wider transition-all bg-teal-600 hover:bg-teal-700 text-white rounded-2xl shadow-md">
                        + Simpan Jadwal
                    </button>
                </div>
            </form>

            <!-- DAFTAR JADWAL AKTIF -->
            <div class="border-t border-slate-100 pt-4 space-y-2">
                <h4 class="text-slate-700 text-xs font-extrabold uppercase tracking-wider">Daftar Penjadwalan Aktif:</h4>
                <div class="space-y-2 max-h-36 overflow-y-auto pr-1">
                    @forelse($schedules as $schedule)
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 flex items-center justify-between text-xs">
                            <div>
                                <span class="text-slate-800 font-extrabold block">{{ $schedule->label }}</span>
                                <span class="text-slate-500 font-mono font-bold">
                                    {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }} WIB - {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                                </span>
                            </div>
                            <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold p-1">[Hapus]</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs italic">Belum ada jadwal tersimpan.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
