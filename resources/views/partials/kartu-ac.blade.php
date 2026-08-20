@props([
    'id' => 1,
    'pin' => 18,
    'name' => 'PANASONIC 1',
    'location' => 'LAMPU PANEL BAWAH',
    'shift' => 'Shift Siang (06:00 - 18:00 WIB)',
    'color' => 'red',
    'schedules' => []
])

<!-- HERO FEATURE CARD AC {{ $id }} (PROPORSI PRESISI PERSIS SEPERTI GAMBAR CONTOH) -->
<div x-data="{ 
        showModal: false, 
        unitName: localStorage.getItem('ac_unit_name_{{ $id }}') || '{{ $name }}',
        saveName() {
            localStorage.setItem('ac_unit_name_{{ $id }}', this.unitName);
        }
     }" 
     class="bg-white rounded-[28px] sm:rounded-[36px] lg:rounded-[40px] p-4 sm:p-6 lg:p-7 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.06)] border border-[#8E1616]/20 flex flex-col justify-between space-y-3 sm:space-y-4 relative overflow-hidden transition-all hover:shadow-[0_25px_60px_-15px_rgba(142,22,22,0.1)]">
    
    <!-- Decorative subtle blob in top-right -->
    <div class="absolute -top-10 -right-10 w-32 sm:w-48 h-32 sm:h-48 {{ $id === 1 ? 'bg-[#D84040]/8' : 'bg-[#8E1616]/8' }} rounded-full blur-xl sm:blur-2xl pointer-events-none"></div>

    <!-- 1. TOP HEADER: ICON HOLDER + UNIT NAME + BADGES + GEAR BUTTON -->
    <div class="flex items-start justify-between relative z-10 gap-2">
        <div class="flex items-center space-x-2.5 sm:space-x-4 min-w-0">
            <!-- Icon Holder -->
            <div class="w-10 h-10 sm:w-13 sm:h-13 lg:w-14 lg:h-14 rounded-[16px] sm:rounded-[20px] bg-[#EEEEEE] border border-[#8E1616]/20 flex items-center justify-center text-lg sm:text-2xl shadow-inner shrink-0">
                <span>❄️</span>
            </div>
            
            <div class="space-y-0.5 min-w-0">
                <div class="flex flex-wrap items-center gap-1 sm:gap-1.5">
                    <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-wider bg-[#EEEEEE] text-[#8E1616] px-2 py-0.5 rounded-full border border-[#8E1616]/20 whitespace-nowrap">
                        GPIO {{ $pin }}
                    </span>
                    <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-wider bg-[#8E1616]/10 text-[#8E1616] px-2 py-0.5 rounded-full whitespace-nowrap">
                        {{ $location }}
                    </span>
                </div>
                
                <h3 @click="showModal = true" 
                    class="text-base sm:text-2xl lg:text-2xl font-black text-[#1D1616] tracking-tight hover:text-[#D84040] transition cursor-pointer leading-tight" 
                    x-text="unitName" 
                    title="Klik untuk ubah nama unit">
                </h3>
            </div>
        </div>

        <!-- Trailing Circular Action / Setting Button -->
        <button @click="showModal = true" 
                type="button" 
                title="Pengaturan & Penjadwalan Unit"
                class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#EEEEEE] hover:bg-[#8E1616] hover:text-white text-[#1D1616] border border-[#8E1616]/20 flex items-center justify-center transition-all cursor-pointer shadow-xs hover:scale-105 shrink-0">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </button>
    </div>

    <!-- 2. BENTO CARD 1: BEBAN ARUS REAL-TIME -->
    <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[18px] sm:rounded-[22px] p-3 sm:p-4 border border-[#8E1616]/15 space-y-1 sm:space-y-1.5 shadow-2xs relative z-10">
        <div class="flex items-center justify-between">
            <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">BEBAN ARUS</span>
            <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-[#D84040]/10 text-[#D84040] text-[10px] sm:text-xs font-black flex items-center justify-center">⚡</span>
        </div>
        <div class="flex items-baseline space-x-1">
            <span id="ac{{ $id }}-current" class="text-xl sm:text-2xl lg:text-3xl font-black text-[#1D1616] font-mono tracking-tight">0.0000</span>
            <span class="text-xs font-black text-[#8E1616] font-sans">A</span>
        </div>
        <div class="flex items-center justify-between text-[8px] sm:text-[10px] text-slate-500 font-medium pt-1 border-t border-[#8E1616]/10">
            <span>ACS712</span>
            <span class="font-mono font-bold text-[#1D1616]" id="ac{{ $id }}-time">07:47:00</span>
        </div>
    </div>

    <!-- 3. BENTO CARD 2: STATUS RELAY & SIKLUS ROTASI -->
    <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[18px] sm:rounded-[22px] p-3 sm:p-4 border border-[#8E1616]/15 space-y-1 sm:space-y-1.5 shadow-2xs relative z-10">
        <div class="flex items-center justify-between">
            <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">STATUS RELAY</span>
            <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-emerald-500/10 text-emerald-700 text-[10px] sm:text-xs font-black flex items-center justify-center">⏱️</span>
        </div>
        <div>
            <span id="ac{{ $id }}-badge-label" class="text-xs sm:text-sm lg:text-base font-black text-[#1D1616] uppercase tracking-wide block">
                STANDBY OFF
            </span>
            <span class="text-[9px] sm:text-[11px] text-slate-600 font-bold block truncate mt-0.5">
                {{ $shift }}
            </span>
        </div>
        <div class="flex items-center justify-between text-[8px] sm:text-[10px] text-emerald-700 font-extrabold pt-1 border-t border-[#8E1616]/10">
            <span>Relay</span>
            <span>● ESP32</span>
        </div>
    </div>

    <!-- 4. INFO STRIP: ROTASI OTOMATIS + MAKS BEBAN -->
    <div class="bg-[#EEEEEE]/70 rounded-[16px] sm:rounded-[20px] p-2.5 sm:p-3 border border-[#8E1616]/10 flex items-center justify-between gap-2 text-[9px] sm:text-[11px] text-slate-600 relative z-10">
        <div class="flex items-center space-x-1.5 min-w-0">
            <span class="text-xs shrink-0">🛡️</span>
            <span class="font-semibold truncate">
                Rotasi otomatis via <strong>RTC DS3231</strong>
            </span>
        </div>
        <span class="text-[8px] sm:text-[10px] font-black uppercase tracking-wider text-[#8E1616] bg-white px-2 sm:px-2.5 py-0.5 rounded-full border border-[#8E1616]/20 shrink-0 shadow-2xs">
            MAKS: 2.5A
        </span>
    </div>

    <!-- 5. HIGH-IMPACT SAKLAR ON/OFF (MATCHING REFERENCE BOTTOM SWITCH ROW) -->
    <div class="bg-[#EEEEEE] rounded-[20px] sm:rounded-[24px] p-3 sm:p-3.5 border border-[#8E1616]/20 flex items-center justify-between relative z-10">
        <div class="space-y-0.5 min-w-0">
            <span class="text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest text-[#8E1616] block">SAKLAR MANUAL</span>
            <span id="ac{{ $id }}-switch-text" class="text-xs sm:text-base font-black uppercase tracking-wider text-[#1D1616] block truncate">
                OFF (MATI)
            </span>
        </div>

        <!-- Switch Slider (Pill Toggle on the Right) -->
        <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-2">
            <input type="checkbox" id="ac{{ $id }}-switch" onchange="sendAcControlViaSwitch({{ $id }}, this)" class="sr-only peer">
            <div class="w-14 sm:w-16 h-8 sm:h-9 bg-slate-300 rounded-full peer peer-focus:ring-2 peer-focus:ring-[#D84040]/30 transition-all peer-checked:bg-[#D84040] after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:rounded-full after:h-6.5 sm:after:h-7.5 after:w-6.5 sm:after:w-7.5 after:transition-all after:shadow-md peer-checked:after:translate-x-6 sm:peer-checked:after:translate-x-7"></div>
        </label>
    </div>

    <!-- MODAL DIALOG PENGATURAN -->
    <div x-show="showModal" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="showModal = false">
        
        <div class="bg-white rounded-[40px] text-[#1D1616] font-sans border border-[#8E1616]/30 max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6 transform transition-all"
             @click.away="showModal = false">
            
            <!-- MODAL HEADER -->
            <div class="border-b border-[#8E1616]/20 pb-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-[20px] bg-[#EEEEEE] text-[#8E1616] flex items-center justify-center font-black text-xl">
                        ⚙️
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">Konfigurasi Perangkat</span>
                        <h3 class="text-xl font-black text-[#1D1616]">
                            Pengaturan AC {{ $id }} (Pin {{ $pin }})
                        </h3>
                    </div>
                </div>
                <button @click="showModal = false" class="text-slate-400 hover:text-[#D84040] font-bold text-2xl cursor-pointer">&times;</button>
            </div>

            <!-- FITUR 1: UBAH NAMA UNIT PERANGKAT -->
            <div class="bg-[#EEEEEE]/60 border border-[#8E1616]/20 rounded-[24px] p-5 space-y-2">
                <label for="unit_name_input_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider text-[#1D1616]">
                    ✏️ Ganti Label Nama Unit AC
                </label>
                <input type="text" id="unit_name_input_{{ $id }}" x-model="unitName" @input="saveName()" 
                       placeholder="Masukkan nama baru unit AC" 
                       class="w-full bg-white border border-[#8E1616]/30 rounded-[16px] text-[#1D1616] text-sm px-4 py-3 font-bold focus:border-[#D84040] focus:ring-2 focus:ring-[#D84040]/20 focus:outline-none">
                <p class="text-[10px] text-slate-500 font-semibold">Tersimpan otomatis secara real-time di browser.</p>
            </div>

            <!-- FITUR 2: FORM TAMBAH JADWAL ON/OFF AC -->
            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <h4 class="text-[#1D1616] text-xs font-black uppercase tracking-wider">
                    ⏱️ Tambah Jadwal Operasional Baru
                </h4>
                <div>
                    <label for="label_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-slate-600">Label Jadwal</label>
                    <input type="text" id="label_{{ $id }}" name="label" required placeholder="Contoh: Jam Kantor Utama" 
                           class="w-full bg-[#EEEEEE]/60 border border-[#8E1616]/30 rounded-[16px] text-[#1D1616] text-sm px-4 py-3 placeholder-slate-400 focus:outline-none focus:border-[#D84040] font-bold">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-slate-600">Jam Mulai (ON)</label>
                        <input type="time" id="start_time_{{ $id }}" name="start_time" required 
                               class="w-full bg-[#EEEEEE]/60 border border-[#8E1616]/30 rounded-[16px] text-[#1D1616] text-sm px-4 py-3 font-mono font-bold focus:outline-none focus:border-[#D84040]">
                    </div>
                    <div>
                        <label for="end_time_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-slate-600">Jam Selesai (OFF)</label>
                        <input type="time" id="end_time_{{ $id }}" name="end_time" required 
                               class="w-full bg-[#EEEEEE]/60 border border-[#8E1616]/30 rounded-[16px] text-[#1D1616] text-sm px-4 py-3 font-mono font-bold focus:outline-none focus:border-[#D84040]">
                    </div>
                </div>

                <div class="flex space-x-3 pt-2">
                    <button type="button" @click="showModal = false" 
                            class="w-1/3 py-3.5 font-black text-xs uppercase tracking-wider bg-[#EEEEEE] hover:bg-slate-200 text-[#1D1616] rounded-[20px] transition cursor-pointer">
                        Tutup
                    </button>
                    <button type="submit" 
                            class="w-2/3 py-3.5 font-black text-xs uppercase tracking-wider bg-[#D84040] hover:bg-[#8E1616] text-white rounded-[20px] shadow-lg shadow-[#D84040]/30 transition cursor-pointer">
                        + Simpan Jadwal
                    </button>
                </div>
            </form>

        </div>

    </div>

</div>
