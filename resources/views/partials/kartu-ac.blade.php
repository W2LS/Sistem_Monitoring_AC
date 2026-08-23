@props([
    'id' => 1,
    'pin' => 18,
    'name' => 'PANASONIC 1',
    'location' => 'Lampu Panel Bawah',
    'shift' => 'Shift Siang (06:00 - 18:00 WIB)',
    'color' => 'red',
    'schedules' => []
])

<!-- HERO FEATURE CARD AC {{ $id }} (DESAIN AWAL ELEGAN, LEGA & RESPONSIP SPASIAL) -->
<div x-data="{ 
        showModal: false, 
        unitName: localStorage.getItem('ac_unit_name_{{ $id }}') || '{{ $name }}',
        saveName() {
            localStorage.setItem('ac_unit_name_{{ $id }}', this.unitName);
        }
     }" 
     class="bg-white rounded-[32px] sm:rounded-[40px] p-5 sm:p-7 lg:p-8 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.06)] border border-[#8E1616]/20 flex flex-col justify-between space-y-5 sm:space-y-6 relative overflow-hidden transition-all hover:shadow-[0_25px_60px_-15px_rgba(142,22,22,0.1)]">
    
    <!-- Decorative subtle blob in top-right -->
    <div class="absolute -top-12 -right-12 w-48 h-48 {{ $id === 1 ? 'bg-[#D84040]/8' : 'bg-[#8E1616]/8' }} rounded-full blur-2xl pointer-events-none"></div>

    <!-- 1. TOP HEADER: ICON HOLDER + UNIT NAME + BADGES + GEAR BUTTON -->
    <div class="flex items-start justify-between relative z-10 gap-3">
        <div class="flex items-center space-x-3.5 sm:space-x-5 min-w-0">
            <!-- Icon Holder -->
            <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-[18px] sm:rounded-[22px] lg:rounded-[24px] bg-[#EEEEEE] border border-[#8E1616]/20 flex items-center justify-center text-xl sm:text-2xl lg:text-3xl shadow-inner shrink-0">
                <span>❄️</span>
            </div>
            
            <div class="space-y-1 min-w-0">
                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest bg-[#EEEEEE] text-[#8E1616] px-2.5 py-0.5 rounded-full border border-[#8E1616]/20 shrink-0">
                        PIN GPIO {{ $pin }}
                    </span>
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest bg-[#8E1616]/10 text-[#8E1616] px-2.5 py-0.5 rounded-full truncate">
                        {{ $location }}
                    </span>
                </div>
                
                <h3 @click="showModal = true" 
                    class="text-xl sm:text-2xl lg:text-3xl font-black text-[#1D1616] tracking-tight hover:text-[#D84040] transition cursor-pointer truncate" 
                    x-text="unitName" 
                    title="Klik untuk ubah nama unit">
                </h3>
            </div>
        </div>

        <!-- Trailing Circular Action / Setting Button -->
        <button @click="showModal = true" 
                type="button" 
                title="Pengaturan & Penjadwalan Unit"
                class="w-10 h-10 sm:w-11 sm:h-11 lg:w-12 lg:h-12 rounded-full bg-[#EEEEEE] hover:bg-[#8E1616] hover:text-white text-[#1D1616] border border-[#8E1616]/20 flex items-center justify-center transition-all cursor-pointer shadow-xs hover:scale-105 shrink-0">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </button>
    </div>

    <!-- 2. NESTED METRIC BENTO CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4 relative z-10">
        
        <!-- Bento 1: Arus Listrik (Ampere) -->
        <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[18px] sm:rounded-[20px] p-4 sm:p-5 border border-[#8E1616]/15 flex flex-col justify-between shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">BEBAN ARUS REAL-TIME</span>
                <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#D84040]/10 text-[#D84040] text-xs font-black flex items-center justify-center shrink-0">⚡</span>
            </div>
            <div class="my-2 flex items-baseline space-x-1.5 flex-wrap">
                <span id="ac{{ $id }}-current" class="text-2xl sm:text-3xl font-black text-[#1D1616] font-mono tracking-tight">0.0000</span>
                <span class="text-xs font-black text-[#8E1616] font-sans shrink-0">Ampere</span>
            </div>
            <div class="flex items-center justify-between text-[9px] sm:text-[10px] text-slate-500 font-medium pt-1.5 border-t border-[#8E1616]/10">
                <span class="truncate">ACS712</span>
                <span class="font-mono font-bold text-[#1D1616] truncate" id="ac{{ $id }}-time">Live Telemetry</span>
            </div>
        </div>

        <!-- Bento 2: Status Operasional & Slot Rotasi -->
        <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[18px] sm:rounded-[20px] p-4 sm:p-5 border border-[#8E1616]/15 flex flex-col justify-between shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">SIKLUS ROTASI SERVER</span>
                <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-emerald-500/10 text-emerald-700 text-xs font-black flex items-center justify-center shrink-0">⏱️</span>
            </div>
            <div class="my-2">
                <span id="ac{{ $id }}-badge-label" class="text-sm sm:text-base font-black text-[#1D1616] uppercase tracking-wide block truncate">
                    Standby OFF
                </span>
                <span class="text-xs text-slate-600 font-bold block truncate mt-0.5">
                    {{ $shift }}
                </span>
            </div>
            <div class="flex items-center justify-between text-[9px] sm:text-[10px] text-emerald-700 font-extrabold pt-1.5 border-t border-[#8E1616]/10">
                <span class="truncate">Relay Hardware</span>
                <span class="truncate">● ESP32</span>
            </div>
        </div>

    </div>

    <!-- 3. INFO STRIP -->
    <div class="bg-[#EEEEEE]/60 rounded-[18px] sm:rounded-[20px] p-3.5 sm:p-4 border border-[#8E1616]/10 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 text-xs text-slate-600 relative z-10">
        <div class="flex items-center space-x-2 sm:space-x-2.5">
            <span class="text-sm sm:text-base shrink-0">🛡️</span>
            <span class="font-semibold leading-tight">
                Sistem rotasi aktif via <strong>RTC DS3231</strong>.
            </span>
        </div>
        <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-[#8E1616] bg-white px-2.5 py-1 rounded-full border border-[#8E1616]/20 shrink-0 shadow-2xs w-max">
            Maks: 2.5A
        </span>
    </div>

    <!-- 4. HIGH-IMPACT INTERACTIVE SAKLAR ON/OFF -->
    <div class="bg-[#EEEEEE] rounded-[20px] sm:rounded-[24px] p-4 sm:p-5 border border-[#8E1616]/20 flex items-center justify-between relative z-10">
        <div class="space-y-0.5 min-w-0 pr-2">
            <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Kontrol Manual Langsung</span>
            <div class="flex items-center space-x-2">
                <span id="ac{{ $id }}-switch-text" class="text-sm sm:text-base font-black uppercase tracking-wider text-[#1D1616] truncate">
                    OFF (MATI)
                </span>
                <span class="text-xs text-slate-500 font-semibold hidden sm:inline">• Override</span>
            </div>
        </div>

        <!-- Interactive Switch Slider -->
        <label class="relative inline-flex items-center cursor-pointer shrink-0">
            <input type="checkbox" id="ac{{ $id }}-switch" onchange="sendAcControlViaSwitch({{ $id }}, this)" class="sr-only peer">
            <div class="w-16 h-9 sm:w-20 sm:h-11 bg-slate-300 rounded-full peer peer-focus:ring-2 peer-focus:ring-[#D84040]/30 transition-all peer-checked:bg-[#D84040] after:content-[''] after:absolute after:top-[4px] sm:after:top-[5px] after:left-[4px] sm:after:left-[5px] after:bg-white after:rounded-full after:h-7 sm:after:h-8 after:w-7 sm:after:w-8 after:transition-all after:shadow-md peer-checked:after:translate-x-7 sm:peer-checked:after:translate-x-9"></div>
        </label>
    </div>

    <!-- MODAL DIALOG PENGATURAN -->
    <div x-show="showModal" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="showModal = false">
        
        <div class="bg-white rounded-[40px] text-[#1D1616] font-sans border border-[#8E1616]/30 max-w-lg w-full p-8 shadow-2xl space-y-6 transform transition-all"
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
