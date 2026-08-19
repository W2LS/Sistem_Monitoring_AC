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

<!-- KARTU KONTROL AC {{ $id }} (DUAL THEME: MODERN GUI & RETRO TERMINAL CLI) -->
<div x-data="{ 
        showModal: false, 
        unitName: localStorage.getItem('ac_unit_name_{{ $id }}') || '{{ $name }}',
        saveName() {
            localStorage.setItem('ac_unit_name_{{ $id }}', this.unitName);
        }
     }" 
     :class="currentTheme === 'cli' 
        ? 'bg-[#050505] border border-[#1f521f] rounded-none p-5 text-[#33ff00] font-mono shadow-none' 
        : 'bg-white border-2 {{ $bgCard }} rounded-3xl p-6 shadow-sm font-sans'"
     class="transition-all duration-200 flex flex-col justify-between space-y-5 relative">
    
    <!-- CLI ASCII HEADER BAR -->
    <template x-if="currentTheme === 'cli'">
        <div class="border-b border-[#1f521f] pb-2 flex items-center justify-between text-xs">
            <span class="text-[#ffb000] font-bold">
                +-- [UNIT_0{{ $id }} : <span x-text="unitName.toUpperCase()"></span>] ----+
            </span>
            <span class="text-[#1f521f] text-[10px]">ADDR: 0x0{{ $id }}</span>
        </div>
    </template>

    <!-- HEADER CARD: ICON PENGATURAN + NAMA AC + SWITCH ON/OFF -->
    <div :class="currentTheme === 'cli' ? 'border-b border-[#1f521f] pb-3' : 'border-b border-slate-100 pb-4'" class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            
            <!-- KOTAK ICON PENGATURAN -->
            <button @click="showModal = true" 
                    type="button" 
                    title="Klik untuk Pengaturan Jam & Nama Unit" 
                    :class="currentTheme === 'cli' 
                        ? 'w-10 h-10 border border-[#33ff00] bg-[#0a0a0a] text-[#33ff00] rounded-none cli-btn-invert font-mono' 
                        : 'w-12 h-12 rounded-2xl {{ $accentBg }} text-white shadow-md hover:scale-105 active:scale-95 transition-all'"
                    class="flex items-center justify-center group cursor-pointer">
                <template x-if="currentTheme === 'gui'">
                    <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </template>
                <template x-if="currentTheme === 'cli'">
                    <span class="font-mono text-xs font-bold">[CFG]</span>
                </template>
            </button>

            <!-- NAMA AC & BADGE STATUS -->
            <div>
                <h3 @click="showModal = true" 
                    :class="currentTheme === 'cli' ? 'font-mono text-[#33ff00] cli-glow text-base' : 'font-outfit font-black text-slate-800 text-lg hover:text-teal-600'"
                    class="font-black uppercase tracking-wide cursor-pointer transition-colors" 
                    x-text="unitName" 
                    title="Klik untuk ubah nama">
                </h3>
                <div class="flex items-center space-x-2 mt-0.5">
                    <span id="ac{{ $id }}-badge-label" 
                          :class="currentTheme === 'cli' ? 'border border-[#1f521f] text-[#33ff00] bg-[#0a0a0a] rounded-none text-[10px]' : 'rounded-md text-[11px] border {{ $badgeBg }}'"
                          class="px-2 py-0.5 font-extrabold uppercase tracking-wider">
                        Offline
                    </span>
                    <span :class="currentTheme === 'cli' ? 'text-[#1f521f] text-[11px]' : 'text-xs text-slate-400 font-semibold'">
                        GPIO_PIN_{{ $pin }}
                    </span>
                </div>
            </div>
        </div>

        <!-- TOGGLE SWITCH ON/OFF (IOS VS CLI BUTTON) -->
        <div>
            <!-- GUI iOS Style Switch -->
            <template x-if="currentTheme === 'gui'">
                <div class="flex items-center space-x-3 bg-slate-100 px-4 py-2 rounded-2xl border border-slate-200">
                    <span id="ac{{ $id }}-switch-text" class="text-xs font-black uppercase tracking-wider text-slate-400">OFF</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="ac{{ $id }}-switch" onchange="sendAcControlViaSwitch({{ $id }}, this)" class="sr-only peer">
                        <div class="w-14 h-7 bg-slate-300 rounded-full peer peer-focus:ring-2 peer-focus:ring-teal-400 transition-all peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-7"></div>
                    </label>
                </div>
            </template>

            <!-- CLI Monospace Inverted Button Toggle -->
            <template x-if="currentTheme === 'cli'">
                <div class="flex items-center space-x-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="ac{{ $id }}-switch" onchange="sendAcControlViaSwitch({{ $id }}, this)" class="sr-only peer">
                        <div class="border-2 border-[#33ff00] bg-[#0a0a0a] px-3 py-1 text-xs font-mono font-bold uppercase cursor-pointer hover:bg-[#33ff00] hover:text-[#0a0a0a] transition-all peer-checked:bg-[#33ff00] peer-checked:text-[#0a0a0a] peer-checked:shadow-[0_0_10px_rgba(51,255,0,0.8)]">
                            <span id="ac{{ $id }}-switch-text">[ RELAY: OFF ]</span>
                        </div>
                    </label>
                </div>
            </template>
        </div>
    </div>

    <!-- 2 KARTU INFORMASI PENTING (Arus Listrik & Jam Atur AC) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        
        <!-- 1. ARUS LISTRIK (AMPERE ⚡) -->
        <div :class="currentTheme === 'cli' ? 'border border-[#1f521f] bg-[#0a0a0a] rounded-none p-3.5' : 'bg-slate-50 border border-slate-200/80 rounded-2xl p-4'"
             class="flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span :class="currentTheme === 'cli' ? 'text-[#ffb000] text-[10px] uppercase font-bold' : 'text-[11px] font-extrabold text-slate-500 uppercase tracking-wider'">
                    &gt; LOAD_CURRENT (AMP)
                </span>
                <span :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-amber-500'" class="font-black text-sm">⚡</span>
            </div>
            <div class="my-2">
                <span id="ac{{ $id }}-current" 
                      :class="currentTheme === 'cli' ? 'text-2xl text-[#33ff00] cli-glow font-mono font-bold' : 'text-2xl text-slate-900 font-outfit font-black font-mono'">
                    0.0000
                </span>
                <span :class="currentTheme === 'cli' ? 'text-xs text-[#1f521f] font-mono' : 'text-xs font-bold text-slate-500'">Ampere</span>
            </div>
            <span :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f] font-mono' : 'text-[10px] text-slate-400 font-medium'">
                RECORDED: <span id="ac{{ $id }}-time">STREAMING</span>
            </span>
        </div>

        <!-- 2. JAM ATUR AC -->
        <div @click="showModal = true" 
             :class="currentTheme === 'cli' ? 'border border-[#1f521f] bg-[#0a0a0a] rounded-none p-3.5 hover:border-[#33ff00]' : 'bg-slate-50 border border-slate-200/80 rounded-2xl p-4 hover:bg-slate-100/80'"
             class="flex flex-col justify-between transition-colors cursor-pointer" title="Klik untuk atur jam operasional">
            <div class="flex items-center justify-between">
                <span :class="currentTheme === 'cli' ? 'text-[#ffb000] text-[10px] uppercase font-bold' : 'text-[11px] font-extrabold text-slate-500 uppercase tracking-wider'">
                    &gt; SCHEDULE_SLOT
                </span>
                <span :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-500'" class="font-black text-sm">⏱️</span>
            </div>
            <div class="my-2">
                @if(count($schedules) > 0)
                    <span :class="currentTheme === 'cli' ? 'text-lg text-[#33ff00] font-mono font-bold' : 'text-lg text-slate-900 font-outfit font-black font-mono'">
                        {{ \Illuminate\Support\Carbon::parse($schedules[0]->start_time)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($schedules[0]->end_time)->format('H:i') }}
                    </span>
                    <span :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f] font-mono' : 'text-[11px] font-semibold text-slate-500'" class="block truncate mt-0.5">
                        {{ $schedules[0]->label }}
                    </span>
                @else
                    <span :class="currentTheme === 'cli' ? 'text-lg text-[#1f521f] font-mono' : 'text-lg text-slate-900 font-outfit font-black font-mono'">00:00 - 00:00</span>
                    <span :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-[11px] text-slate-400 font-semibold'" class="block truncate mt-0.5">
                        [ NO_RULE_SET ]
                    </span>
                @endif
            </div>
            <span :class="currentTheme === 'cli' ? 'text-[10px] text-[#33ff00]' : 'text-[10px] text-teal-600 font-bold'">
                {{ count($schedules) > 0 ? '[ STATUS: ACTIVE ]' : '[ STANDBY ]' }}
            </span>
        </div>

    </div>

    <!-- MODAL DIALOG PENGATURAN -->
    <div x-show="showModal" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs"
         @keydown.escape.window="showModal = false">
        
        <div :class="currentTheme === 'cli' ? 'bg-[#0a0a0a] border-2 border-[#33ff00] text-[#33ff00] font-mono rounded-none' : 'bg-white rounded-3xl text-slate-900 font-sans border border-slate-200'"
             class="max-w-lg w-full p-6 shadow-2xl space-y-5 transform transition-all"
             @click.away="showModal = false">
            
            <!-- MODAL HEADER -->
            <div :class="currentTheme === 'cli' ? 'border-b border-[#1f521f] pb-3' : 'border-b border-slate-100 pb-4'" class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div :class="currentTheme === 'cli' ? 'border border-[#33ff00] text-[#33ff00] bg-transparent rounded-none text-xs p-1' : 'w-10 h-10 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-lg'">
                        <span x-text="currentTheme === 'cli' ? 'CFG' : '⚙️'"></span>
                    </div>
                    <div>
                        <h3 :class="currentTheme === 'cli' ? 'font-mono font-bold text-[#33ff00] uppercase text-base cli-glow' : 'font-outfit font-black text-lg text-slate-900'">
                            <span x-text="currentTheme === 'cli' ? '> CONFIG_UNIT_AC_0' + {{ $id }} : 'Pengaturan Unit Perangkat'"></span>
                        </h3>
                        <p :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-xs text-slate-500 font-medium'">
                            Ubah nama unit & atur jam operasional AC.
                        </p>
                    </div>
                </div>
                <button @click="showModal = false" :class="currentTheme === 'cli' ? 'text-[#33ff00] hover:bg-[#33ff00] hover:text-[#0a0a0a] px-2 font-mono' : 'text-slate-400 hover:text-slate-700'" class="font-bold text-xl">&times;</button>
            </div>

            <!-- FITUR 1: UBAH NAMA UNIT PERANGKAT -->
            <div :class="currentTheme === 'cli' ? 'border border-[#1f521f] bg-[#050505] p-3 space-y-2 rounded-none' : 'bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2'">
                <label for="unit_name_input_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider" :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-700'">
                    ✏️ Ubah Nama Perangkat AC
                </label>
                <input type="text" id="unit_name_input_{{ $id }}" x-model="unitName" @input="saveName()" 
                       placeholder="Masukkan nama baru unit AC" 
                       :class="currentTheme === 'cli' ? 'bg-[#0a0a0a] border border-[#33ff00] text-[#33ff00] font-mono rounded-none text-xs focus:ring-1 focus:ring-[#33ff00]' : 'bg-white border border-slate-200 rounded-xl text-slate-800 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100'"
                       class="w-full px-4 py-2.5 font-bold focus:outline-none">
                <p :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-[10px] text-slate-400 font-semibold'">Nama tersimpan otomatis secara real-time di browser.</p>
            </div>

            <!-- FITUR 2: FORM TAMBAH JADWAL ON/OFF AC -->
            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4 pt-2">
                @csrf
                <h4 :class="currentTheme === 'cli' ? 'text-[#ffb000] font-mono text-xs' : 'text-slate-700 font-sans text-xs'" class="font-extrabold uppercase tracking-wider">
                    ⏱️ Tambah Jam Penjadwalan AC
                </h4>
                <div>
                    <label for="label_{{ $id }}" :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5">Label Penjadwalan</label>
                    <input type="text" id="label_{{ $id }}" name="label" required placeholder="Contoh: Jam Kantor Utama" 
                           :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] text-[#33ff00] font-mono rounded-none text-xs' : 'bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm'"
                           class="w-full px-4 py-3 placeholder-slate-500 focus:outline-none focus:border-teal-500 font-medium">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time_{{ $id }}" :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5">Jam Mulai (ON)</label>
                        <input type="time" id="start_time_{{ $id }}" name="start_time" required 
                               :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] text-[#33ff00] font-mono rounded-none text-xs' : 'bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm'"
                               class="w-full px-4 py-3 font-mono focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label for="end_time_{{ $id }}" :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5">Jam Selesai (OFF)</label>
                        <input type="time" id="end_time_{{ $id }}" name="end_time" required 
                               :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] text-[#33ff00] font-mono rounded-none text-xs' : 'bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm'"
                               class="w-full px-4 py-3 font-mono focus:outline-none focus:border-teal-500">
                    </div>
                </div>

                <div class="flex space-x-3 pt-2">
                    <button type="button" @click="showModal = false" 
                            :class="currentTheme === 'cli' ? 'border border-[#1f521f] text-[#33ff00] bg-transparent rounded-none font-mono hover:bg-[#1f521f]/40' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl'"
                            class="w-1/3 py-3 font-bold text-xs uppercase tracking-wider transition-all">
                        Tutup
                    </button>
                    <button type="submit" 
                            :class="currentTheme === 'cli' ? 'border border-[#33ff00] bg-[#33ff00] text-[#0a0a0a] font-mono rounded-none hover:shadow-[0_0_10px_rgba(51,255,0,0.8)]' : 'bg-teal-600 hover:bg-teal-700 text-white rounded-2xl shadow-md'"
                            class="w-2/3 py-3 font-extrabold text-xs uppercase tracking-wider transition-all">
                        + Simpan Jadwal
                    </button>
                </div>
            </form>

            <!-- DAFTAR JADWAL AKTIF -->
            <div :class="currentTheme === 'cli' ? 'border-t border-[#1f521f] pt-3 space-y-2' : 'border-t border-slate-100 pt-4 space-y-2'">
                <h4 :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-700'" class="text-xs font-extrabold uppercase tracking-wider">Daftar Penjadwalan Aktif:</h4>
                <div class="space-y-2 max-h-36 overflow-y-auto pr-1">
                    @forelse($schedules as $schedule)
                        <div :class="currentTheme === 'cli' ? 'border border-[#1f521f] bg-[#050505] rounded-none' : 'bg-slate-50 border border-slate-200 rounded-xl'" 
                             class="p-3 flex items-center justify-between text-xs">
                            <div>
                                <span :class="currentTheme === 'cli' ? 'text-[#33ff00] font-bold' : 'text-slate-800 font-extrabold'" class="block">{{ $schedule->label }}</span>
                                <span :class="currentTheme === 'cli' ? 'text-[#1f521f] font-mono' : 'text-slate-500 font-mono font-bold'">
                                    {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }} WIB - {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                                </span>
                            </div>
                            <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" :class="currentTheme === 'cli' ? 'text-[#ff3333] hover:underline font-mono' : 'text-red-500 hover:text-red-700 font-bold'" class="p-1">[Hapus]</button>
                            </form>
                        </div>
                    @empty
                        <p :class="currentTheme === 'cli' ? 'text-[#1f521f]' : 'text-slate-400'" class="text-xs italic">Belum ada jadwal tersimpan.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
