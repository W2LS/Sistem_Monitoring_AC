<!-- ================= MODUL 2: DEVELOPER ZONE (BLYNK IOT TEMPLATES & DATASTREAMS CONSOLE) ================= -->
<div class="space-y-6 pb-20" x-data="{ 
    selectedTemplateId: '{{ $templates->first()->id ?? '' }}',
    modalNewTemplate: false,
    modalNewDatastream: false,
}">

    <!-- 1. PAGE HEADER & ACTION BUTTONS -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[#8E1616]/20 pb-4">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#8E1616] flex items-center gap-1.5">
                <span>🛠️</span>
                <span>DEVELOPER ZONE • BLYNK PROTOCOL ENGINE</span>
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-[#1D1616] tracking-tight mt-0.5">
                Manajemen Template & Datastreams
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Rancang blueprint perangkat keras dan konfigurasikan saluran Virtual Pin (V0 - V255) ala Blynk IoT.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <button @click="modalNewTemplate = true" 
                    type="button"
                    class="bg-[#D84040] hover:bg-[#8E1616] text-white rounded-[24px] text-xs font-black uppercase tracking-wider py-3.5 px-6 shadow-lg shadow-[#D84040]/30 transition flex items-center space-x-2 shrink-0 cursor-pointer active:scale-95">
                <span class="text-base leading-none font-black">+</span>
                <span>Tambah Template Baru</span>
            </button>
        </div>
    </div>

    <!-- 2. TEMPLATE SELECTOR TABS & BLUEPRINT DETAILS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT: TEMPLATES LIST -->
        <div class="space-y-3">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 px-1">Pilih Blueprint Template</h3>
            
            @foreach($templates as $tmpl)
            <div @click="selectedTemplateId = '{{ $tmpl->id }}'"
                 class="p-4 rounded-[24px] border-2 cursor-pointer transition-all duration-200 flex items-center justify-between gap-3 shadow-xs"
                 :class="selectedTemplateId === '{{ $tmpl->id }}' ? 'bg-[#1D1616] border-[#8E1616] text-white shadow-md' : 'bg-white border-slate-100 hover:border-slate-200 text-[#1D1616]'">
                
                <div class="flex items-center gap-3 min-w-0">
                    <span class="text-2xl shrink-0">{{ $tmpl->icon ?? '⚡' }}</span>
                    <div class="min-w-0">
                        <h4 class="font-black text-sm truncate">{{ $tmpl->name }}</h4>
                        <p class="text-[11px] opacity-70 truncate">{{ $tmpl->hardware_type }}</p>
                    </div>
                </div>

                <div class="text-right shrink-0">
                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full"
                          :class="selectedTemplateId === '{{ $tmpl->id }}' ? 'bg-[#D84040] text-white' : 'bg-slate-100 text-slate-600'">
                        {{ count($tmpl->datastreams ?? []) }} Pins
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- RIGHT: ACTIVE TEMPLATE DATASTREAMS CONSOLE -->
        <div class="lg:col-span-2 space-y-6">
            @foreach($templates as $tmpl)
            <div x-show="selectedTemplateId === '{{ $tmpl->id }}'" 
                 x-transition:enter="transition ease-out duration-200 transform opacity-0"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="space-y-6">
                
                <!-- Blueprint Summary Card -->
                <div class="bg-white rounded-[32px] p-6 shadow-sm border border-[#8E1616]/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">{{ $tmpl->icon ?? '⚡' }}</span>
                            <h3 class="text-xl font-black text-[#1D1616]">{{ $tmpl->name }}</h3>
                        </div>
                        <p class="text-xs text-slate-500">{{ $tmpl->description }}</p>
                        <div class="flex items-center gap-2 pt-2 text-[11px] font-bold text-slate-700">
                            <span class="bg-slate-100 px-2.5 py-1 rounded-lg">📟 Hardware: {{ $tmpl->hardware_type }}</span>
                            <span class="bg-slate-100 px-2.5 py-1 rounded-lg">🌐 Koneksi: {{ $tmpl->connection_type }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 shrink-0">
                        <button @click="modalNewDatastream = true"
                                type="button"
                                class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#D84040] to-[#8E1616] text-white font-bold text-xs uppercase tracking-wider shadow-md hover:opacity-95 transition cursor-pointer flex items-center gap-1.5 active:scale-95">
                            <span>+ Add Datastream</span>
                        </button>

                        <form action="{{ route('templates.destroy', $tmpl->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Template {{ $tmpl->name }} beserta seluruh Datastreams-nya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="p-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition cursor-pointer flex items-center gap-1 text-xs font-bold" 
                                    title="Hapus Template Ini">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="hidden sm:inline">Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Datastreams Table (Virtual Pins) -->
                <div class="bg-white rounded-[32px] p-6 shadow-sm border border-[#8E1616]/20 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h4 class="font-black text-sm text-[#1D1616] flex items-center gap-2">
                            <span>Daftar Virtual Pins (Datastreams)</span>
                            <span class="text-[10px] font-bold text-[#8E1616] bg-[#8E1616]/10 px-2 py-0.5 rounded-full">{{ count($tmpl->datastreams ?? []) }} Terdaftar</span>
                        </h4>
                        <span class="text-xs text-slate-400 font-mono">Blynk V-Pin Standard</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    <th class="py-2.5 px-3">Virtual Pin</th>
                                    <th class="py-2.5 px-3">Nama Datastream</th>
                                    <th class="py-2.5 px-3">Tipe Data</th>
                                    <th class="py-2.5 px-3">Rentang Nilai</th>
                                    <th class="py-2.5 px-3">Satuan</th>
                                    <th class="py-2.5 px-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($tmpl->datastreams ?? [] as $ds)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-3">
                                        <span class="font-mono font-black text-xs px-2.5 py-1 rounded-lg bg-[#1D1616] text-white">
                                            {{ $ds['pin'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 font-bold text-[#1D1616]">
                                        {{ $ds['name'] }}
                                        <div class="text-[10px] text-slate-400 font-normal">{{ $ds['desc'] ?? '-' }}</div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase {{ $ds['type'] === 'Integer' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                            {{ $ds['type'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 font-mono font-semibold text-slate-600">
                                        {{ $ds['min'] ?? 0 }} - {{ $ds['max'] ?? 1 }}
                                    </td>
                                    <td class="py-3 px-3 font-bold text-slate-700">
                                        {{ !empty($ds['unit']) ? $ds['unit'] : '-' }}
                                    </td>
                                    <td class="py-3 px-3 text-right">
                                        <form action="{{ route('templates.deleteDatastream', ['id' => $tmpl->id, 'pin' => $ds['pin']]) }}" method="POST" onsubmit="return confirm('Hapus Datastream {{ $ds['pin'] }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition cursor-pointer" title="Hapus Datastream">
                                                🗑️
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400">Belum ada datastream yang terdaftar pada template ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>


    <!-- ================= MODAL 1: TAMBAH TEMPLATE BARU ================= -->
    <div x-show="modalNewTemplate" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="modalNewTemplate = false" 
             class="bg-white rounded-[40px] p-7 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-[20px] bg-[#D84040]/10 text-[#D84040] flex items-center justify-center font-black text-xl">
                        🛠️
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-[#1D1616]">Buat Device Template Baru</h4>
                        <p class="text-xs text-slate-500">Rancang blueprint IoT baru PT PINDAD</p>
                    </div>
                </div>
                <button @click="modalNewTemplate = false" class="text-slate-400 hover:text-[#D84040] text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('templates.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Nama Template *</label>
                    <input type="text" name="name" required placeholder="Contoh: Smart Sensor Suhu Server Rack" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Tipe Hardware *</label>
                        <select name="hardware_type" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#D84040] outline-none">
                            <option value="Raspberry Pi 3B+">Raspberry Pi 3B+</option>
                            <option value="Raspberry Pi 4 Model B">Raspberry Pi 4 Model B</option>
                            <option value="ESP32 Dual-Core IoT">ESP32 Dual-Core IoT</option>
                            <option value="Industrial PLC Gateway">Industrial PLC Gateway</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Tipe Koneksi *</label>
                        <select name="connection_type" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#D84040] outline-none">
                            <option value="MQTT Broker (TCP 1883)">MQTT Broker (TCP 1883)</option>
                            <option value="WiFi (IEEE 802.11 b/g/n)">WiFi (IEEE 802.11 b/g/n)</option>
                            <option value="Ethernet LAN">Ethernet LAN</option>
                            <option value="RS485 Modbus RTU">RS485 Modbus RTU</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Ikon Representasi</label>
                    <input type="text" name="icon" value="⚡" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Deskripsi Template</label>
                    <textarea name="description" rows="2" placeholder="Tuliskan spesifikasi umum blueprint..." class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#D84040] outline-none"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button @click="modalNewTemplate = false" type="button" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#D84040] to-[#8E1616] text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Buat Template</button>
                </div>
            </form>
        </div>
    </div>


    <!-- ================= MODAL 2: TAMBAH DATASTREAM (VIRTUAL PIN) ================= -->
    <div x-show="modalNewDatastream" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="modalNewDatastream = false" 
             class="bg-white rounded-[40px] p-7 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5 relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-[20px] bg-[#8E1616]/10 text-[#8E1616] flex items-center justify-center font-black text-xl">
                        📍
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-[#1D1616]">Tambah Datastream Virtual Pin</h4>
                        <p class="text-xs text-slate-500">Kaitkan parameter telemetri / saklar kontrol</p>
                    </div>
                </div>
                <button @click="modalNewDatastream = false" class="text-slate-400 hover:text-[#8E1616] text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form :action="'/templates/' + selectedTemplateId + '/datastreams'" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Virtual Pin *</label>
                        <input type="text" name="pin" required placeholder="V0, V1, V2..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-mono uppercase focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Tipe Data *</label>
                        <select name="type" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#8E1616] outline-none">
                            <option value="Integer">Integer (0, 1, Bilangan Bulat)</option>
                            <option value="Double">Double (Desimal / Float)</option>
                            <option value="String">String (Teks / JSON)</option>
                            <option value="Enum">Enum (Status Pilihan)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Nama Datastream *</label>
                    <input type="text" name="name" required placeholder="Contoh: Sensor Arus ACS712" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Min Value</label>
                        <input type="number" step="any" name="min" value="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Max Value</label>
                        <input type="number" step="any" name="max" value="100" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Satuan Unit</label>
                        <input type="text" name="unit" placeholder="A, W, °C, %" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-1.5">Deskripsi Fungsi</label>
                    <input type="text" name="desc" placeholder="Penjelasan fungsi pin ini..." class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button @click="modalNewDatastream = false" type="button" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#8E1616] to-[#1D1616] text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Simpan Datastream</button>
                </div>
            </form>
        </div>
    </div>
</div>
