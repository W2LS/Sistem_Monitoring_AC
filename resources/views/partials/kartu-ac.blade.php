@props([
    'id' => 1,
    'pin' => 18,
    'name' => 'PANASONIC 1',
    'location' => 'Lampu Panel Bawah',
    'color' => 'red',
    'schedules' => []
])

<!-- KARTU INFORMASI UTAMA AC {{ $id }} (PALETTE: #1D1616, #8E1616, #D84040, #EEEEEE) -->
<div x-data="{ 
        showModal: false, 
        unitName: localStorage.getItem('ac_unit_name_{{ $id }}') || '{{ $name }}',
        saveName() {
            localStorage.setItem('ac_unit_name_{{ $id }}', this.unitName);
        }
     }" 
     class="bg-white rounded-[40px] p-7 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 flex flex-col justify-between space-y-6 relative overflow-hidden transition-all hover:shadow-[0_25px_60px_-15px_rgba(142,22,22,0.12)]">
    
    <!-- Decorative subtle blob in top-right -->
    <div class="absolute -top-10 -right-10 w-44 h-44 {{ $id === 1 ? 'bg-[#8E1616]/10' : 'bg-[#D84040]/10' }} rounded-full blur-2xl pointer-events-none"></div>

    <!-- TOP HEADER: ICON HOLDER + UNIT NAME + SETTING -->
    <div class="flex items-start justify-between relative z-10">
        <div class="flex items-center space-x-4">
            <!-- 64x64px Icon Holder -->
            <div class="w-16 h-16 rounded-[22px] bg-[#EEEEEE] border border-[#8E1616]/20 flex items-center justify-center text-2xl shadow-inner shrink-0">
                <span>❄️</span>
            </div>
            
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">
                    Ruang Server 1 • Pin GPIO {{ $pin }}
                </span>
                <h3 @click="showModal = true" 
                    class="text-2xl font-black text-[#1D1616] tracking-tight hover:text-[#D84040] transition cursor-pointer" 
                    x-text="unitName" 
                    title="Klik untuk ubah nama unit">
                </h3>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">{{ $location }}</p>
            </div>
        </div>

        <!-- Setting Gear Button -->
        <button @click="showModal = true" 
                type="button" 
                title="Atur Nama & Jadwal"
                class="w-11 h-11 rounded-full bg-[#EEEEEE] hover:bg-[#8E1616] hover:text-white text-[#1D1616] border border-[#8E1616]/20 flex items-center justify-center transition-all cursor-pointer shadow-2xs hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </button>
    </div>

    <!-- NESTED 2-COLUMN BENTO METRIC CARDS (16px Radius) -->
    <div class="grid grid-cols-2 gap-3 relative z-10">
        
        <!-- Bento 1: Arus Listrik (Ampere) -->
        <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[16px] p-4 border border-[#8E1616]/15 flex flex-col justify-between shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">Beban Arus</span>
                <span class="w-6 h-6 rounded-full bg-[#D84040]/10 text-[#D84040] text-xs font-black flex items-center justify-center">⚡</span>
            </div>
            <div class="my-1.5">
                <span id="ac{{ $id }}-current" class="text-2xl font-black text-[#1D1616] font-mono tracking-tight">0.0000</span>
                <span class="text-xs font-black text-[#8E1616] font-sans">A</span>
            </div>
            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">
                Log: <span id="ac{{ $id }}-time" class="font-mono">Live</span>
            </span>
        </div>

        <!-- Bento 2: Estimasi Daya (Watt) & Slot Jadwal -->
        <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[16px] p-4 border border-[#8E1616]/15 flex flex-col justify-between shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616]">Status Operasi</span>
                <span class="w-6 h-6 rounded-full bg-emerald-500/10 text-emerald-700 text-xs font-black flex items-center justify-center">⏱️</span>
            </div>
            <div class="my-1.5">
                <span id="ac{{ $id }}-badge-label" class="text-sm font-black text-[#1D1616] uppercase tracking-wide block truncate">
                    Standby OFF
                </span>
                <span class="text-[10px] text-slate-500 font-semibold block truncate">
                    {{ $id === 1 ? 'Shift Siang (06:00 - 18:00)' : 'Shift Malam (18:00 - 06:00)' }}
                </span>
            </div>
            <span class="text-[9px] font-bold text-emerald-700 uppercase tracking-wider">
                ● Hardware Ready
            </span>
        </div>

    </div>

    <!-- HIGH-IMPACT SAKLAR ON/OFF TOGGLE (DIRECTLY UNDER INFO CARDS) -->
    <div class="bg-[#EEEEEE] rounded-[24px] p-4 border border-[#8E1616]/20 flex items-center justify-between relative z-10">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Saklar Relay AC {{ $id }}</span>
            <span id="ac{{ $id }}-switch-text" class="text-sm font-black uppercase tracking-wider text-[#1D1616]">
                OFF (MATI)
            </span>
        </div>

        <!-- Interactive Switch Slider (Checks to #D84040 Coral Red) -->
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" id="ac{{ $id }}-switch" onchange="sendAcControlViaSwitch({{ $id }}, this)" class="sr-only peer">
            <div class="w-16 h-9 bg-slate-300 rounded-full peer peer-focus:ring-2 peer-focus:ring-[#D84040]/30 transition-all peer-checked:bg-[#D84040] after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all after:shadow-md peer-checked:after:translate-x-7"></div>
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
