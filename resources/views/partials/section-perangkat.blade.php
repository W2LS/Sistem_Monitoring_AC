<!-- SEKSI MANAJEMEN PERANGKAT IOT (BLYNK-STYLE DEVICE FLEET & PROVISIONING) -->
<div x-show="activeTab === 'perangkat'" 
     x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-98"
     x-transition:enter-start="opacity-0 scale-98"
     x-transition:enter-end="opacity-100 scale-100"
     class="space-y-8"
     x-data="{ 
        modalTambah: false, 
        modalEdit: false,
        editDevice: { id: '', name: '', location: '', ip_address: '', hardware_type: '', num_ac: 2, description: '' }
     }">

    <!-- HERO HEADER: DEVICE FLEET PLATFORM -->
    <div class="bg-[#1D1616] rounded-[40px] p-6 lg:p-10 shadow-[0_25px_60px_-15px_rgba(29,22,22,0.4)] border border-[#8E1616]/30 text-white relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-[#8E1616]/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-[#D84040]/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#8E1616] to-[#D84040] flex items-center justify-center shadow-lg shadow-[#8E1616]/40">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-[#D84040] bg-[#D84040]/10 px-2.5 py-0.5 rounded-full border border-[#D84040]/20">IoT Fleet Console</span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-500/20">Blynk Protocol Standard</span>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-black tracking-tight text-white mt-1">Manajemen Armada Perangkat IoT</h2>
                    </div>
                </div>
                <p class="text-xs lg:text-sm text-[#EEEEEE]/70 max-w-2xl leading-relaxed">
                    Daftarkan node kontroler baru, pantau status koneksi secara terpusat, dan beralih kontrol antar ruang server PT PINDAD secara *real-time*.
                </p>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex flex-wrap items-center gap-3">
                <button @click="modalTambah = true" 
                        type="button"
                        class="px-5 py-3 rounded-2xl bg-gradient-to-r from-[#D84040] to-[#8E1616] hover:from-[#8E1616] hover:to-[#D84040] text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-[#D84040]/30 transition-all transform active:scale-95 flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Tambah Perangkat IoT</span>
                </button>

                <!-- MASTER EMERGENCY ACTIONS -->
                <form action="{{ route('devices.masterControl') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="command" value="ON">
                    <button type="submit" 
                            onclick="return confirm('Nyalakan SELURUH unit AC di semua node perangkat?')"
                            class="px-4 py-3 rounded-2xl bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-400 border border-emerald-500/30 font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>Master ON (Semua)</span>
                    </button>
                </form>

                <form action="{{ route('devices.masterControl') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="command" value="OFF">
                    <button type="submit" 
                            onclick="return confirm('Matikan SELURUH unit AC di semua node perangkat?')"
                            class="px-4 py-3 rounded-2xl bg-rose-600/20 hover:bg-rose-600/30 text-rose-400 border border-rose-500/30 font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        <span>Master OFF</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- FLEET SUMMARY STATS GRID -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/10">
            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <span class="text-[10px] font-black uppercase tracking-wider text-[#EEEEEE]/50">Total Perangkat IoT</span>
                <div class="text-2xl font-black text-white mt-1">{{ count($devices) }} <span class="text-xs text-[#EEEEEE]/50 font-normal">Node</span></div>
            </div>

            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <span class="text-[10px] font-black uppercase tracking-wider text-[#EEEEEE]/50">Node Online Aktif</span>
                <div class="text-2xl font-black text-emerald-400 mt-1 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>1</span> <span class="text-xs text-emerald-400/60 font-normal">Live</span>
                </div>
            </div>

            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <span class="text-[10px] font-black uppercase tracking-wider text-[#EEEEEE]/50">Total Pendingin AC</span>
                <div class="text-2xl font-black text-[#D84040] mt-1">{{ $devices->sum('num_ac') }} <span class="text-xs text-[#EEEEEE]/50 font-normal">Unit</span></div>
            </div>

            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <span class="text-[10px] font-black uppercase tracking-wider text-[#EEEEEE]/50">Total Beban Terukur</span>
                <div class="text-2xl font-black text-amber-400 mt-1" id="fleetTotalWatt">935 <span class="text-xs text-amber-400/60 font-normal">Watt</span></div>
            </div>
        </div>
    </div>

    <!-- DEVICE FLEET LIST (BLYNK CONSOLE STYLE) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-black text-[#1D1616] tracking-tight flex items-center gap-2">
                <span>Daftar Node Perangkat Terdaftar</span>
                <span class="text-xs font-bold text-[#8E1616] bg-[#8E1616]/10 px-2.5 py-0.5 rounded-full">{{ count($devices) }} Terpasang</span>
            </h3>
            <span class="text-xs text-[#1D1616]/60 font-medium">Klik <b>"Pilih & Buka"</b> untuk memantau ruangan tersebut</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($devices as $device)
            @php
                $stat = $fleetStats[$device->device_id] ?? ['is_online' => false, 'total_watt' => 0, 'total_current' => 0, 'last_seen' => 'Standby'];
                $isSelected = ($device->device_id === $selectedDeviceId);
            @endphp
            <div class="bg-white rounded-[32px] p-6 shadow-[0_15px_35px_-10px_rgba(29,22,22,0.1)] border-2 transition-all hover:shadow-xl relative flex flex-col justify-between {{ $isSelected ? 'border-[#D84040] ring-4 ring-[#D84040]/10' : 'border-slate-100' }}">
                
                @if($isSelected)
                <div class="absolute -top-3 right-6 bg-[#D84040] text-white text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-md">
                    ● Aktif Dipantau Saat Ini
                </div>
                @endif

                <div class="space-y-4">
                    <!-- HEADER CARD -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $stat['is_online'] ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-400' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-black text-base text-[#1D1616] leading-tight">{{ $device->name }}</h4>
                                <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-[#8E1616]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $device->location }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- STATUS BADGE -->
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $stat['is_online'] ? 'bg-emerald-100 text-emerald-700 border border-emerald-300' : 'bg-slate-100 text-slate-600' }}">
                            <span class="w-2 h-2 rounded-full {{ $stat['is_online'] ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                            {{ $stat['is_online'] ? 'Online' : 'Standby' }}
                        </span>
                    </div>

                    <!-- META SPECS -->
                    <div class="bg-slate-50 rounded-2xl p-3.5 space-y-2 text-xs border border-slate-100">
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="text-slate-400 font-medium">Device ID:</span>
                            <span class="font-mono font-bold text-[#1D1616] bg-white px-2 py-0.5 rounded border border-slate-200">{{ $device->device_id }}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="text-slate-400 font-medium">Hardware / IP:</span>
                            <span class="font-bold text-slate-700">{{ $device->hardware_type ?? 'Raspberry Pi' }} ({{ $device->ip_address ?? '192.168.x.x' }})</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="text-slate-400 font-medium">Kapasitas Pendingin:</span>
                            <span class="font-bold text-[#D84040]">{{ $device->num_ac ?? 2 }} Unit AC Otomatis</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="text-slate-400 font-medium">Beban Real-Time:</span>
                            <span class="font-black text-emerald-600">{{ $stat['total_watt'] }} Watt ({{ $stat['total_current'] }} A)</span>
                        </div>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ route('dashboard', ['device_id' => $device->device_id]) }}" 
                       class="flex-1 py-2.5 px-4 rounded-xl text-center font-black text-xs uppercase tracking-wider transition-all cursor-pointer {{ $isSelected ? 'bg-slate-100 text-slate-500 pointer-events-none' : 'bg-gradient-to-r from-[#1D1616] to-[#8E1616] text-white hover:opacity-95 shadow-md shadow-[#1D1616]/20' }}">
                        {{ $isSelected ? '✓ Sedang Dipantau' : '🎯 Pilih & Buka' }}
                    </a>

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
                            class="p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-all cursor-pointer"
                            title="Edit Perangkat">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>

                    @if($device->device_id !== 'RPI3B_PINDAD_ROOM_1')
                    <form action="{{ route('devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('Hapus perangkat {{ $device->name }} dari Fleet?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition-all cursor-pointer" title="Hapus Perangkat">
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

    <!-- MODAL 1: TAMBAH PERANGKAT IOT BARU -->
    <div x-show="modalTambah" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="modalTambah = false" 
             class="bg-white rounded-[32px] p-6 lg:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-6 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#D84040]/10 text-[#D84040] flex items-center justify-center font-black">
                        +
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-[#1D1616]">Daftarkan Node IoT Baru</h4>
                        <p class="text-xs text-slate-500">Tambahkan ruangan atau controller baru ke Fleet PT PINDAD</p>
                    </div>
                </div>
                <button @click="modalTambah = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('devices.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Nama Perangkat / Ruangan *</label>
                    <input type="text" name="name" required placeholder="Contoh: Ruang Server Lt. 2 (Divisi Rekayasa)" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] focus:border-transparent outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Lokasi / Gedung *</label>
                        <input type="text" name="location" required placeholder="Contoh: Gedung 10 Lt. 2" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">ID Perangkat (MQTT ID) *</label>
                        <input type="text" name="device_id" required placeholder="RPI3B_PINDAD_ROOM_2" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#D84040] outline-none uppercase">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">IP Address</label>
                        <input type="text" name="ip_address" placeholder="192.168.196.50" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Tipe Hardware</label>
                        <select name="hardware_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#D84040] outline-none">
                            <option value="Raspberry Pi 3B+">Raspberry Pi 3B+</option>
                            <option value="Raspberry Pi 4 Model B">Raspberry Pi 4 Model B</option>
                            <option value="ESP32 Dual-Core IoT">ESP32 Dual-Core IoT</option>
                            <option value="Industrial PLC / Gateway">Industrial PLC / Gateway</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Jumlah Unit AC di Ruangan</label>
                    <input type="number" name="num_ac" min="1" max="8" value="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Deskripsi Tambahan</label>
                    <textarea name="description" rows="2" placeholder="Catatan fungsi ruangan atau tipe pendingin..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3">
                    <button @click="modalTambah = false" type="button" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#D84040] to-[#8E1616] text-white font-bold text-xs uppercase shadow-lg shadow-[#D84040]/30 hover:opacity-95 cursor-pointer">Simpan & Daftarkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT PERANGKAT -->
    <div x-show="modalEdit" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="modalEdit = false" 
             class="bg-white rounded-[32px] p-6 lg:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-6 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#8E1616]/10 text-[#8E1616] flex items-center justify-center font-black">
                        ✏️
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-[#1D1616]">Edit Informasi Perangkat</h4>
                        <p class="text-xs text-slate-500">Perbarui spesifikasi atau lokasi perangkat IoT</p>
                    </div>
                </div>
                <button @click="modalEdit = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/devices/' + editDevice.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Nama Perangkat / Ruangan *</label>
                    <input type="text" name="name" x-model="editDevice.name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Lokasi / Gedung *</label>
                        <input type="text" name="location" x-model="editDevice.location" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">IP Address</label>
                        <input type="text" name="ip_address" x-model="editDevice.ip_address" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Tipe Hardware</label>
                        <input type="text" name="hardware_type" x-model="editDevice.hardware_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Jumlah Unit AC</label>
                        <input type="number" name="num_ac" x-model="editDevice.num_ac" min="1" max="8" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1">Deskripsi Tambahan</label>
                    <textarea name="description" x-model="editDevice.description" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3">
                    <button @click="modalEdit = false" type="button" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#8E1616] to-[#1D1616] text-white font-bold text-xs uppercase shadow-lg hover:opacity-95 cursor-pointer">Perbarui Perangkat</button>
                </div>
            </form>
        </div>
    </div>
</div>
