<!-- SEKSI MANAJEMEN PERANGKAT IOT (BLYNK-STYLE DEVICE FLEET & PROVISIONING) -->
<div class="space-y-6 pb-24" x-data="{ 
    modalTambah: false, 
    modalEdit: false, 
    editDevice: { id: '', name: '', location: '', ip_address: '', hardware_type: '', num_ac: 2, description: '' }
}">
    
    <!-- 1. PAGE HEADER & ACTION BUTTONS (RESPONSIF & TIDAK MELESAT KE PINGGIR) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[#8E1616]/20 pb-4">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#8E1616] flex items-center gap-1.5">
                <span>📡</span>
                <span>SISTEM MULTI-NODE • BLYNK PROTOCOL STANDARD</span>
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-[#1D1616] tracking-tight mt-0.5">
                Manajemen Armada Perangkat IoT
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Daftarkan kontroler baru, pantau status koneksi secara terpusat, dan beralih kontrol antar ruangan server PT PINDAD.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <button 
                @click="modalTambah = true" 
                class="bg-[#D84040] hover:bg-[#8E1616] text-white rounded-[24px] text-xs font-black uppercase tracking-wider py-3.5 px-6 shadow-lg shadow-[#D84040]/30 transition flex items-center space-x-2 shrink-0 cursor-pointer">
                <span class="text-base leading-none font-black">+</span>
                <span>Tambah Perangkat IoT</span>
            </button>

            <!-- Master Emergency Controls -->
            <div class="flex items-center gap-1.5 bg-white border border-[#8E1616]/20 p-1.5 rounded-full shadow-xs">
                <form action="{{ route('devices.masterControl') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="command" value="ON">
                    <button type="submit" 
                            onclick="return confirm('Nyalakan SELURUH unit AC di semua node perangkat?')"
                            class="px-3 py-1.5 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[10px] uppercase tracking-wider transition flex items-center gap-1 cursor-pointer"
                            title="Nyalakan Seluruh AC di Semua Ruangan">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Master ON</span>
                    </button>
                </form>

                <form action="{{ route('devices.masterControl') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="command" value="OFF">
                    <button type="submit" 
                            onclick="return confirm('Matikan SELURUH unit AC di semua node perangkat?')"
                            class="px-3 py-1.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[10px] uppercase tracking-wider transition flex items-center gap-1 cursor-pointer"
                            title="Matikan Seluruh AC di Semua Ruangan">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span>Master OFF</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. KPI SUMMARY HERO WIDGET (SELEBAR HERO HOMEPAGE) -->
    <div class="bg-[#1D1616] rounded-[40px] p-6 sm:p-7 text-white shadow-[0_20px_50px_-12px_rgba(29,22,22,0.35)] border border-[#8E1616]/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#D84040] block">Status Armada IoT Terpasang</span>
            <div class="flex items-baseline space-x-3">
                <span class="text-3xl sm:text-4xl font-black font-mono text-white tracking-tight">{{ count($devices) }} Node</span>
                <span class="text-xs sm:text-sm font-extrabold text-[#EEEEEE]/80">• {{ $devices->sum('num_ac') }} Unit Pendingin Terkendali</span>
            </div>
            <span class="text-xs font-bold text-[#D84040] block">Total Estimasi Beban Semua Ruangan: 935 Watt</span>
        </div>

        <div class="bg-white/10 backdrop-blur-md rounded-[28px] px-6 py-3.5 border border-white/10 flex items-center space-x-4 shrink-0">
            <span class="text-2xl">🌐</span>
            <div>
                <span class="text-sm font-black text-white block">1 Node Live Aktif</span>
                <span class="text-xs text-[#EEEEEE]/70 font-semibold">Ruang Server Utama (Lt. 1)</span>
            </div>
        </div>
    </div>

    <!-- 3. UNIFIED FLEET DEVICES TABLE (BERSIH, RAPI, PROPORSIONAL DI SEMUA LAYAR) -->
    <div class="bg-white rounded-[40px] p-6 sm:p-8 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-black text-[#1D1616] tracking-tight flex items-center gap-2">
                    <span>Daftar Node Kontroler Ruangan</span>
                    <span class="text-xs font-bold text-[#8E1616] bg-[#8E1616]/10 px-2.5 py-0.5 rounded-full">{{ count($devices) }} Terdaftar</span>
                </h3>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Pilih salah satu node untuk mengarahkan kontrol dan telemetri langsung di Dashboard.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-400">
                        <th class="py-3 px-4">Nama Perangkat & Lokasi</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Device ID & IP</th>
                        <th class="py-3 px-4">Beban & AC</th>
                        <th class="py-3 px-4 text-right">Aksi Kontrol</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach($devices as $device)
                    @php
                        $stat = $fleetStats[$device->device_id] ?? ['is_online' => false, 'total_watt' => 0, 'total_current' => 0, 'last_seen' => 'Standby'];
                        $isSelected = ($device->device_id === $selectedDeviceId);
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors {{ $isSelected ? 'bg-rose-50/40' : '' }}">
                        <!-- Nama & Lokasi -->
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 {{ $stat['is_online'] ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-black text-sm text-[#1D1616] flex items-center gap-2">
                                        <span>{{ $device->name }}</span>
                                        @if($isSelected)
                                        <span class="text-[9px] font-black uppercase text-[#D84040] bg-[#D84040]/10 px-2 py-0.5 rounded-full border border-[#D84040]/20">Aktif</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-[#8E1616]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        <span>{{ $device->location }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $stat['is_online'] ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                <span class="w-2 h-2 rounded-full {{ $stat['is_online'] ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                {{ $stat['is_online'] ? 'Online' : 'Standby' }}
                            </span>
                        </td>

                        <!-- ID & IP -->
                        <td class="py-4 px-4 font-mono">
                            <div class="font-bold text-[#1D1616]">{{ $device->device_id }}</div>
                            <div class="text-[11px] text-slate-500">{{ $device->hardware_type }} ({{ $device->ip_address }})</div>
                        </td>

                        <!-- Beban & Kapasitas -->
                        <td class="py-4 px-4">
                            <div class="font-black text-emerald-600">{{ $stat['total_watt'] }} Watt <span class="text-slate-400 font-normal">({{ $stat['total_current'] }} A)</span></div>
                            <div class="text-[11px] text-slate-500 font-medium">{{ $device->num_ac ?? 2 }} Unit Pendingin AC</div>
                        </td>

                        <!-- Aksi -->
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($isSelected)
                                <span class="py-2 px-3.5 rounded-xl bg-slate-100 text-slate-500 font-bold text-xs">
                                    ✓ Sedang Dipantau
                                </span>
                                @else
                                <a href="{{ route('dashboard', ['device_id' => $device->device_id]) }}" 
                                   class="py-2 px-3.5 rounded-xl bg-[#1D1616] hover:bg-[#8E1616] text-white font-bold text-xs transition shadow-sm cursor-pointer flex items-center gap-1">
                                    <span>🎯</span>
                                    <span>Pantau</span>
                                </a>
                                @endif

                                <!-- Edit -->
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
                                        class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition cursor-pointer"
                                        title="Edit Informasi Node">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                <!-- Delete -->
                                @if($device->device_id !== 'RPI3B_PINDAD_ROOM_1')
                                <form action="{{ route('devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('Hapus perangkat {{ $device->name }} dari Fleet?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition cursor-pointer" title="Hapus Perangkat">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. MODAL TAMBAH PERANGKAT IOT (40px RADIUS) -->
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
             class="bg-white rounded-[40px] p-7 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-[20px] bg-[#D84040]/10 text-[#D84040] flex items-center justify-center font-black text-xl">
                        +
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-[#1D1616]">Daftarkan Node IoT Baru</h4>
                        <p class="text-xs text-slate-500">Tambahkan kontroler baru ke Fleet PT PINDAD</p>
                    </div>
                </div>
                <button @click="modalTambah = false" class="text-slate-400 hover:text-[#D84040] text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('devices.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Nama Perangkat / Ruangan *</label>
                    <input type="text" name="name" required placeholder="Contoh: Ruang Server Lt. 2 (Divisi Rekayasa)" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Lokasi / Gedung *</label>
                        <input type="text" name="location" required placeholder="Contoh: Gedung 10 Lt. 2" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">ID Perangkat (MQTT) *</label>
                        <input type="text" name="device_id" required placeholder="RPI3B_PINDAD_ROOM_2" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#D84040] outline-none uppercase">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">IP Address</label>
                        <input type="text" name="ip_address" placeholder="192.168.196.50" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#D84040] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Tipe Hardware</label>
                        <select name="hardware_type" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#D84040] outline-none">
                            <option value="Raspberry Pi 3B+">Raspberry Pi 3B+</option>
                            <option value="Raspberry Pi 4 Model B">Raspberry Pi 4 Model B</option>
                            <option value="ESP32 Dual-Core IoT">ESP32 Dual-Core IoT</option>
                            <option value="Industrial PLC Gateway">Industrial PLC Gateway</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Jumlah Unit AC Terkendali</label>
                    <input type="number" name="num_ac" min="1" max="8" value="2" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Deskripsi Fungsi</label>
                    <textarea name="description" rows="2" placeholder="Catatan fungsi ruangan atau tipe pendingin..." class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button @click="modalTambah = false" type="button" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#D84040] to-[#8E1616] text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Simpan Perangkat</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. MODAL EDIT PERANGKAT (40px RADIUS) -->
    <div x-show="modalEdit" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="modalEdit = false" 
             class="bg-white rounded-[40px] p-7 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-[20px] bg-[#8E1616]/10 text-[#8E1616] flex items-center justify-center font-black text-lg">
                        ✏️
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-[#1D1616]">Edit Informasi Perangkat</h4>
                        <p class="text-xs text-slate-500">Perbarui konfigurasi node perangkat</p>
                    </div>
                </div>
                <button @click="modalEdit = false" class="text-slate-400 hover:text-[#8E1616] text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/devices/' + editDevice.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Nama Perangkat / Ruangan *</label>
                    <input type="text" name="name" x-model="editDevice.name" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Lokasi / Gedung *</label>
                        <input type="text" name="location" x-model="editDevice.location" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">IP Address</label>
                        <input type="text" name="ip_address" x-model="editDevice.ip_address" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Tipe Hardware</label>
                        <input type="text" name="hardware_type" x-model="editDevice.hardware_type" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Jumlah Unit AC</label>
                        <input type="number" name="num_ac" x-model="editDevice.num_ac" min="1" max="8" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Deskripsi</label>
                    <textarea name="description" x-model="editDevice.description" rows="2" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button @click="modalEdit = false" type="button" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#8E1616] to-[#1D1616] text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Perbarui Perangkat</button>
                </div>
            </form>
        </div>
    </div>
</div>
