@props([
    'id' => 1,
    'pin' => 18,
    'name' => 'PANASONIC 1',
    'location' => 'Lampu Panel Bawah',
    'color' => 'red',
    'schedules' => []
])

<!-- KARTU INFORMASI UTAMA AC {{ $id }} (SOPHISTICATED PLAYFUL HERO CARD - 40px RADIUS) -->
<div x-data="{ 
        showModal: false, 
        unitName: localStorage.getItem('ac_unit_name_{{ $id }}') || '{{ $name }}',
        saveName() {
            localStorage.setItem('ac_unit_name_{{ $id }}', this.unitName);
        }
     }" 
     class="bg-white rounded-[40px] p-7 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.08)] border border-[#b7c6c2]/30 flex flex-col justify-between space-y-6 relative overflow-hidden transition-all hover:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.12)]">
    
    <!-- Decorative subtle blob in top-right -->
    <div class="absolute -top-10 -right-10 w-44 h-44 {{ $id === 1 ? 'bg-[#ca0013]/5' : 'bg-[#b7c6c2]/20' }} rounded-full blur-2xl pointer-events-none"></div>

    <!-- TOP HEADER: ICON HOLDER + UNIT NAME + SETTING -->
    <div class="flex items-start justify-between relative z-10">
        <div class="flex items-center space-x-4">
            <!-- 64x64px Icon Holder -->
            <div class="w-16 h-16 rounded-[22px] bg-[#eeebe3] border border-[#b7c6c2]/40 flex items-center justify-center text-2xl shadow-inner shrink-0">
                <span>❄️</span>
            </div>
            
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#b7c6c2] block">
                    Ruang Server 1 • Pin GPIO {{ $pin }}
                </span>
                <h3 @click="showModal = true" 
                    class="text-2xl font-black text-[#171e19] tracking-tight hover:text-[#ca0013] transition cursor-pointer" 
                    x-text="unitName" 
                    title="Klik untuk ubah nama unit">
                </h3>
                <p class="text-xs font-semibold text-slate-400 mt-0.5">{{ $location }}</p>
            </div>
        </div>

        <!-- Setting Gear Button -->
        <button @click="showModal = true" 
                type="button" 
                title="Atur Nama & Jadwal"
                class="w-11 h-11 rounded-full bg-[#eeebe3] hover:bg-white text-[#171e19] hover:text-[#ca0013] border border-[#b7c6c2]/40 flex items-center justify-center transition-all cursor-pointer shadow-2xs hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </button>
    </div>

    <!-- NESTED 2-COLUMN BENTO METRIC CARDS (16px Radius) -->
    <div class="grid grid-cols-2 gap-3 relative z-10">
        
        <!-- Bento 1: Arus Listrik (Ampere) -->
        <div class="bg-white/80 backdrop-blur-md rounded-[16px] p-4 border border-[#b7c6c2]/30 flex flex-col justify-between shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#b7c6c2]">Beban Arus</span>
                <span class="w-6 h-6 rounded-full bg-[#ca0013]/10 text-[#ca0013] text-xs font-black flex items-center justify-center">⚡</span>
            </div>
            <div class="my-1.5">
                <span id="ac{{ $id }}-current" class="text-2xl font-black text-[#171e19] font-mono tracking-tight">0.0000</span>
                <span class="text-xs font-black text-slate-400 font-sans">A</span>
            </div>
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                Log: <span id="ac{{ $id }}-time" class="font-mono">Live</span>
            </span>
        </div>

        <!-- Bento 2: Estimasi Daya (Watt) & Slot Jadwal -->
        <div class="bg-white/80 backdrop-blur-md rounded-[16px] p-4 border border-[#b7c6c2]/30 flex flex-col justify-between shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#b7c6c2]">Status Operasi</span>
                <span class="w-6 h-6 rounded-full bg-emerald-500/10 text-emerald-700 text-xs font-black flex items-center justify-center">⏱️</span>
            </div>
            <div class="my-1.5">
                <span id="ac{{ $id }}-badge-label" class="text-sm font-black text-[#171e19] uppercase tracking-wide block truncate">
                    Standby OFF
                </span>
                <span class="text-[10px] text-slate-400 font-semibold block truncate">
                    {{ $id === 1 ? 'Shift Siang (06:00 - 18:00)' : 'Shift Malam (18:00 - 06:00)' }}
                </span>
            </div>
            <span class="text-[9px] font-bold text-emerald-700 uppercase tracking-wider">
                ● Hardware Ready
            </span>
        </div>

    </div>

    <!-- HIGH-IMPACT SAKLAR ON/OFF TOGGLE (DIRECTLY UNDER INFO CARDS) -->
    <div class="bg-[#eeebe3]/70 rounded-[24px] p-4 border border-[#b7c6c2]/30 flex items-center justify-between relative z-10">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#b7c6c2] block">Saklar Relay AC {{ $id }}</span>
            <span id="ac{{ $id }}-switch-text" class="text-sm font-black uppercase tracking-wider text-[#171e19]">
                OFF (MATI)
            </span>
        </div>

        <!-- Interactive Switch Slider -->
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" id="ac{{ $id }}-switch" onchange="sendAcControlViaSwitch({{ $id }}, this)" class="sr-only peer">
            <div class="w-16 h-9 bg-slate-300 rounded-full peer peer-focus:ring-2 peer-focus:ring-[#ca0013]/30 transition-all peer-checked:bg-[#ca0013] after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all after:shadow-md peer-checked:after:translate-x-7"></div>
        </label>
    </div>

    <!-- MODAL DIALOG PENGATURAN -->
    <div x-show="showModal" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
         @keydown.escape.window="showModal = false">
        
        <div class="bg-white rounded-[40px] text-[#171e19] font-sans border border-[#b7c6c2]/30 max-w-lg w-full p-8 shadow-2xl space-y-6 transform transition-all"
             @click.away="showModal = false">
            
            <!-- MODAL HEADER -->
            <div class="border-b border-[#b7c6c2]/20 pb-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-[20px] bg-[#eeebe3] text-[#171e19] flex items-center justify-center font-black text-xl">
                        ⚙️
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#b7c6c2]">Konfigurasi Perangkat</span>
                        <h3 class="text-xl font-black text-[#171e19]">
                            Pengaturan AC {{ $id }} (Pin {{ $pin }})
                        </h3>
                    </div>
                </div>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-700 font-bold text-2xl cursor-pointer">&times;</button>
            </div>

            <!-- FITUR 1: UBAH NAMA UNIT PERANGKAT -->
            <div class="bg-[#eeebe3]/50 border border-[#b7c6c2]/30 rounded-[24px] p-5 space-y-2">
                <label for="unit_name_input_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider text-[#171e19]">
                    ✏️ Ganti Label Nama Unit AC
                </label>
                <input type="text" id="unit_name_input_{{ $id }}" x-model="unitName" @input="saveName()" 
                       placeholder="Masukkan nama baru unit AC" 
                       class="w-full bg-white border border-[#b7c6c2]/40 rounded-[16px] text-[#171e19] text-sm px-4 py-3 font-bold focus:border-[#ca0013] focus:ring-2 focus:ring-[#ca0013]/20 focus:outline-none">
                <p class="text-[10px] text-slate-400 font-semibold">Tersimpan otomatis secara real-time di browser.</p>
            </div>

            <!-- FITUR 2: FORM TAMBAH JADWAL ON/OFF AC -->
            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <h4 class="text-[#171e19] text-xs font-black uppercase tracking-wider">
                    ⏱️ Tambah Jadwal Operasional Baru
                </h4>
                <div>
                    <label for="label_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-slate-600">Label Jadwal</label>
                    <input type="text" id="label_{{ $id }}" name="label" required placeholder="Contoh: Jam Kantor Utama" 
                           class="w-full bg-[#eeebe3]/50 border border-[#b7c6c2]/40 rounded-[16px] text-[#171e19] text-sm px-4 py-3 placeholder-slate-400 focus:outline-none focus:border-[#ca0013] font-bold">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-slate-600">Jam Mulai (ON)</label>
                        <input type="time" id="start_time_{{ $id }}" name="start_time" required 
                               class="w-full bg-[#eeebe3]/50 border border-[#b7c6c2]/40 rounded-[16px] text-[#171e19] text-sm px-4 py-3 font-mono font-bold focus:outline-none focus:border-[#ca0013]">
                    </div>
                    <div>
                        <label for="end_time_{{ $id }}" class="block text-[11px] font-extrabold uppercase tracking-wider mb-1.5 text-slate-600">Jam Selesai (OFF)</label>
                        <input type="time" id="end_time_{{ $id }}" name="end_time" required 
                               class="w-full bg-[#eeebe3]/50 border border-[#b7c6c2]/40 rounded-[16px] text-[#171e19] text-sm px-4 py-3 font-mono font-bold focus:outline-none focus:border-[#ca0013]">
                    </div>
                </div>

                <div class="flex space-x-3 pt-2">
                    <button type="button" @click="showModal = false" 
                            class="w-1/3 py-3.5 font-black text-xs uppercase tracking-wider bg-[#eeebe3] hover:bg-slate-200 text-[#171e19] rounded-[20px] transition cursor-pointer">
                        Tutup
                    </button>
                    <button type="submit" 
                            class="w-2/3 py-3.5 font-black text-xs uppercase tracking-wider bg-[#ca0013] hover:bg-[#b00010] text-white rounded-[20px] shadow-lg shadow-[#ca0013]/30 transition cursor-pointer">
                        + Simpan Jadwal
                    </button>
                </div>
            </form>

        </div>

    </div>

</div>
