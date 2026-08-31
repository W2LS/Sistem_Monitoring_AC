<!-- SEKSI MANAJEMEN PERANGKAT IOT (BLYNK-STYLE DEVICE FLEET & PROVISIONING) -->
<div x-show="activeTab === 'perangkat'" 
     x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-98"
     x-transition:enter-start="opacity-0 scale-98"
     x-transition:enter-end="opacity-100 scale-100"
     class="space-y-6 pb-20"
     x-data="{ 
        modalTambah: false, 
        modalEdit: false,
        editDevice: { id: '', name: '', location: '', ip_address: '', hardware_type: '', num_ac: 2, description: '' }
     }">

    <!-- ================= HERO HEADER: DEVICE FLEET PLATFORM ================= -->
    <div class="bg-[#1D1616] rounded-[32px] p-6 sm:p-7 lg:p-8 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.35)] border border-[#8E1616]/30 text-white relative overflow-hidden">
        <!-- Ambient Decorative Glows -->
        <div class="absolute -right-20 -top-20 w-72 h-72 bg-[#8E1616]/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-72 h-72 bg-[#D84040]/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <!-- Left Info -->
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[9px] font-black uppercase tracking-widest text-[#D84040] bg-[#D84040]/15 px-2.5 py-0.5 rounded-full border border-[#D84040]/30">
                        IoT Fleet Console
                    </span>
                    <span class="text-[9px] font-black uppercase tracking-widest text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-500/25 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Blynk IoT Standard
                    </span>
                </div>
                
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Manajemen Armada Perangkat IoT
                </h2>
                
                <p class="text-xs text-[#EEEEEE]/70 max-w-2xl leading-relaxed">
                    Daftarkan node kontroler baru, pantau kesehatan koneksi secara terpusat, dan beralih kontrol antar ruangan server PT PINDAD secara <i>real-time</i>.
                </p>
            </div>

            <!-- Right Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button @click="modalTambah = true" 
                        type="button"
                        class="px-4 sm:px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#D84040] to-[#8E1616] hover:from-[#8E1616] hover:to-[#D84040] text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-[#D84040]/30 transition-all transform active:scale-95 flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Tambah Perangkat</span>
                </button>

                <!-- Master Controls -->
                <div class="flex items-center gap-1 bg-white/5 p-1 rounded-2xl border border-white/10">
                    <form action="{{ route('devices.masterControl') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="command" value="ON">
                        <button type="submit" 
                                onclick="return confirm('Nyalakan SELURUH unit AC di semua node perangkat?')"
                                class="px-3 py-1.5 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 font-bold text-[11px] uppercase tracking-wider transition flex items-center gap-1.5 cursor-pointer"
                                title="Nyalakan Seluruh AC di Semua Ruangan">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>Master ON</span>
                        </button>
                    </form>

                    <form action="{{ route('devices.masterControl') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="command" value="OFF">
                        <button type="submit" 
                                onclick="return confirm('Matikan SELURUH unit AC di semua node perangkat?')"
                                class="px-3 py-1.5 rounded-xl bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 font-bold text-[11px] uppercase tracking-wider transition flex items-center gap-1.5 cursor-pointer"
                                title="Matikan Seluruh AC di Semua Ruangan">
                            <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                            <span>Master OFF</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================= FLEET SUMMARY STATS TILES ================= -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mt-6 pt-5 border-t border-white/10">
            <div class="bg-white/5 rounded-2xl p-3 sm:p-4 border border-white/10">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#EEEEEE]/50 block">Total Perangkat</span>
                <div class="text-xl sm:text-2xl font-black text-white mt-0.5">
                    {{ count($devices) }} <span class="text-xs text-[#EEEEEE]/50 font-normal">Node</span>
                </div>
            </div>

            <div class="bg-white/5 rounded-2xl p-3 sm:p-4 border border-white/10">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#EEEEEE]/50 block">Node Online</span>
                <div class="text-xl sm:text-2xl font-black text-emerald-400 mt-0.5 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>1</span> <span class="text-xs text-emerald-400/60 font-normal">Live</span>
                </div>
            </div>

            <div class="bg-white/5 rounded-2xl p-3 sm:p-4 border border-white/10">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#EEEEEE]/50 block">Kapasitas AC</span>
                <div class="text-xl sm:text-2xl font-black text-[#D84040] mt-0.5">
                    {{ $devices->sum('num_ac') }} <span class="text-xs text-[#EEEEEE]/50 font-normal">Unit</span>
                </div>
            </div>

            <div class="bg-white/5 rounded-2xl p-3 sm:p-4 border border-white/10">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#EEEEEE]/50 block">Total Beban</span>
                <div class="text-xl sm:text-2xl font-black text-amber-400 mt-0.5">
                    935 <span class="text-xs text-amber-400/60 font-normal">Watt</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= DEVICE FLEET CARDS (PERFECT 3-COLUMN LAYOUT) ================= -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-black text-[#1D1616] tracking-tight flex items-center gap-2">
                <span>Daftar Node Perangkat Terpasang</span>
                <span class="text-xs font-bold text-[#8E1616] bg-[#8E1616]/10 px-2.5 py-0.5 rounded-full border border-[#8E1616]/20">
                    {{ count($devices) }} Unit
                </span>
            </h3>
            <span class="text-xs text-slate-500 hidden sm:inline">Pilih <b>"Pantau Ruangan Ini"</b> untuk memantau telemetri</span>
        </div>

        <!-- 3-COLUMN EQUAL GRID (WITH TABLET ODD-CARD SPAN BALANCING) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($devices as $device)
            @php
                $stat = $fleetStats[$device->device_id] ?? ['is_online' => false, 'total_watt' => 0, 'total_current' => 0, 'last_seen' => 'Standby'];
                $isSelected = ($device->device_id === $selectedDeviceId);
                $isLastOdd = ($loop->last && $loop->count % 2 !== 0);
            @endphp
            <div class="bg-white rounded-[28px] p-5 sm:p-6 shadow-[0_15px_35px_-10px_rgba(29,22,22,0.08)] border-2 transition-all duration-200 hover:shadow-lg relative flex flex-col justify-between {{ $isSelected ? 'border-[#D84040] ring-4 ring-[#D84040]/10' : 'border-slate-100 hover:border-slate-300' }} {{ $isLastOdd ? 'md:col-span-2 lg:col-span-1' : '' }}">
                
                <!-- TOP ACTIVE BADGE -->
                @if($isSelected)
                <div class="absolute -top-3 right-5 bg-[#D84040] text-white text-[9px] font-black uppercase tracking-widest px-3 py-0.5 rounded-full shadow-md flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                    <span>Aktif Dipantau</span>
                </div>
                @endif

                <div class="space-y-4">
                    <!-- HEADER SECTION -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 {{ $stat['is_online'] ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-black text-sm sm:text-base text-[#1D1616] leading-tight truncate" title="{{ $device->name }}">{{ $device->name }}</h4>
                                <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5 truncate" title="{{ $device->location }}">
                                    <svg class="w-3 h-3 text-[#8E1616] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span class="truncate">{{ $device->location }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- STATUS BADGE -->
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider shrink-0 {{ $stat['is_online'] ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $stat['is_online'] ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                            {{ $stat['is_online'] ? 'Online' : 'Standby' }}
                        </span>
                    </div>

                    <!-- META INFO GRID (2x2 Clean Compact Tiles) -->
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Beban Daya</span>
                            <span class="text-xs font-black text-emerald-600 mt-0.5 block truncate">
                                {{ $stat['total_watt'] }} W <span class="text-[9px] text-slate-400 font-normal">({{ $stat['total_current'] }} A)</span>
                            </span>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Kapasitas</span>
                            <span class="text-xs font-black text-[#D84040] mt-0.5 block truncate">
                                {{ $device->num_ac ?? 2 }} Unit AC
                            </span>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Hardware / IP</span>
                            <span class="text-[10px] font-bold text-slate-700 mt-0.5 truncate block" title="{{ $device->hardware_type }} ({{ $device->ip_address }})">
                                {{ $device->ip_address ?? '192.168.x.x' }}
                            </span>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">MQTT ID</span>
                            <span class="text-[9px] font-mono font-bold text-[#1D1616] mt-0.5 truncate block" title="{{ $device->device_id }}">
                                {{ $device->device_id }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- BOTTOM ACTIONS BAR -->
                <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ route('dashboard', ['device_id' => $device->device_id]) }}" 
                       class="flex-1 py-2 px-3 rounded-xl text-center font-black text-xs uppercase tracking-wider transition-all cursor-pointer {{ $isSelected ? 'bg-slate-100 text-slate-400 pointer-events-none' : 'bg-gradient-to-r from-[#1D1616] to-[#8E1616] hover:from-[#8E1616] hover:to-[#D84040] text-white shadow-md' }}">
                        {{ $isSelected ? '✓ Sedang Dipantau' : '🎯 Pantau Ruangan Ini' }}
                    </a>

                    <!-- Edit Button -->
                    <button @click="editDevice = { 
                                id: '{{ $device->id }}', 
                                name: '{{ addslashes($device->name) }}', 
                                location: '{{ addslashes($device->location) }}', 
                                ip_address: '{{ $device->ip_address }}', 
                                hardware_type: '{{ $device->hardware_type }}', 
                                num_ac: '{{ $device->num_ac }}', 
                                description: '{{ addslashes($device->description) }}' 
                            }; modalEdit = true" 
                            type="button" 
                            class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition cursor-pointer shrink-0"
                            title="Edit Perangkat">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>

                    <!-- Delete Button (Except Default Primary Node) -->
                    @if($device->device_id !== 'RPI3B_PINDAD_ROOM_1')
                    <form action="{{ route('devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('Hapus perangkat {{ $device->name }} dari Fleet?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition cursor-pointer shrink-0" title="Hapus Perangkat">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- ================= MODAL 1: TAMBAH PERANGKAT IOT BARU ================= -->
    <div x-show="modalTambah" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="modalTambah = false" 
             class="bg-white rounded-[32px] p-6 sm:p-7 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#D84040]/10 text-[#D84040] flex items-center justify-center font-black text-lg">
                        +
                    </div>
                    <div>
                        <h4 class="text-base font-black text-[#1D1616]">Daftarkan Node IoT Baru</h4>
                        <p class="text-xs text-slate-500">Tambahkan kontroler baru ke Fleet PT PINDAD</p>
                    </div>
                </div>
                <button @click="modalTambah = false" class="text-slate-400 hover:text-[#D84040] text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('devices.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Nama Perangkat / Ruangan *</label>
                    <input type="text" name="name" required placeholder="Contoh: Ruang Server Lt. 2 (Divisi Rekayasa)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Lokasi / Gedung *</label>
                        <input type="text" name="location" required placeholder="Contoh: Gedung 10 Lt. 2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">ID Perangkat (MQTT) *</label>
                        <input type="text" name="device_id" required placeholder="RPI3B_PINDAD_ROOM_2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#D84040] outline-none uppercase">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">IP Address</label>
                        <input type="text" name="ip_address" placeholder="192.168.196.50" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Tipe Hardware</label>
                        <select name="hardware_type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#D84040] outline-none">
                            <option value="Raspberry Pi 3B+">Raspberry Pi 3B+</option>
                            <option value="Raspberry Pi 4 Model B">Raspberry Pi 4 Model B</option>
                            <option value="ESP32 Dual-Core IoT">ESP32 Dual-Core IoT</option>
                            <option value="Industrial PLC Gateway">Industrial PLC Gateway</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Jumlah Unit AC Terkendali</label>
                    <input type="number" name="num_ac" min="1" max="8" value="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Deskripsi</label>
                    <textarea name="description" rows="2" placeholder="Catatan fungsi ruangan atau tipe pendingin..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none"></textarea>
                </div>

                <div class="pt-2.5 flex items-center justify-end gap-2.5 border-t border-slate-100">
                    <button @click="modalTambah = false" type="button" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#D84040] to-[#8E1616] text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Simpan Perangkat</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL 2: EDIT PERANGKAT ================= -->
    <div x-show="modalEdit" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="modalEdit = false" 
             class="bg-white rounded-[32px] p-6 sm:p-7 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#8E1616]/10 text-[#8E1616] flex items-center justify-center font-black text-base">
                        ✏️
                    </div>
                    <div>
                        <h4 class="text-base font-black text-[#1D1616]">Edit Informasi Perangkat</h4>
                        <p class="text-xs text-slate-500">Perbarui konfigurasi node perangkat</p>
                    </div>
                </div>
                <button @click="modalEdit = false" class="text-slate-400 hover:text-[#8E1616] text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/devices/' + editDevice.id" method="POST" class="space-y-3.5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Nama Perangkat / Ruangan *</label>
                    <input type="text" name="name" x-model="editDevice.name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Lokasi / Gedung *</label>
                        <input type="text" name="location" x-model="editDevice.location" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">IP Address</label>
                        <input type="text" name="ip_address" x-model="editDevice.ip_address" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Tipe Hardware</label>
                        <input type="text" name="hardware_type" x-model="editDevice.hardware_type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Jumlah Unit AC</label>
                        <input type="number" name="num_ac" x-model="editDevice.num_ac" min="1" max="8" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Deskripsi</label>
                    <textarea name="description" x-model="editDevice.description" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none"></textarea>
                </div>

                <div class="pt-2.5 flex items-center justify-end gap-2.5 border-t border-slate-100">
                    <button @click="modalEdit = false" type="button" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#8E1616] to-[#1D1616] text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Perbarui Perangkat</button>
                </div>
            </form>
        </div>
    </div>
</div>
