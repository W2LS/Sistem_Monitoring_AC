<!-- Top Header Bar (Modern Industrial GUI + Global Device Switcher) -->
<header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 h-16 flex-shrink-0 z-40 shadow-xs">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        
        <!-- Left: Global Device Selector (Blynk-Style Console Switcher) -->
        <div class="flex items-center gap-3">
            <div class="relative" x-data="{ openDeviceDropdown: false }">
                <button @click="openDeviceDropdown = !openDeviceDropdown" 
                        type="button"
                        class="bg-[#1D1616] hover:bg-[#8E1616] text-white px-4 py-2 rounded-full text-xs font-bold flex items-center gap-2 transition-all shadow-sm cursor-pointer border border-[#8E1616]/40">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-black uppercase tracking-wider text-[10px] text-[#D84040]">Node:</span>
                    <span class="font-bold">{{ $currentDevice->name ?? 'Ruang Server Utama (Lt. 1)' }}</span>
                    <svg class="w-3.5 h-3.5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="openDeviceDropdown" 
                     @click.away="openDeviceDropdown = false"
                     x-cloak
                     class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-slate-200 py-2 z-50">
                    <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Pilih Node Ruangan IoT:</span>
                        <span class="text-[10px] font-bold text-[#8E1616]">{{ count($devices) }} Unit</span>
                    </div>
                    @foreach($devices as $dev)
                    <a href="{{ route('dashboard', ['device_id' => $dev->device_id]) }}" 
                       class="px-4 py-2.5 flex items-center justify-between hover:bg-slate-50 transition-colors {{ $dev->device_id === $selectedDeviceId ? 'bg-slate-50 font-bold border-l-4 border-[#D84040]' : '' }}">
                        <div>
                            <div class="text-xs font-bold text-[#1D1616]">{{ $dev->name }}</div>
                            <div class="text-[10px] text-slate-400 font-mono">{{ $dev->device_id }}</div>
                        </div>
                        @if($dev->device_id === $selectedDeviceId)
                        <span class="text-xs text-[#D84040] font-black">✓ Aktif</span>
                        @endif
                    </a>
                    @endforeach
                    <div class="pt-2 mt-1 border-t border-slate-100 px-4">
                        <button @click="activeTab = 'perangkat'; openDeviceDropdown = false" class="text-[11px] font-bold text-[#8E1616] hover:underline flex items-center gap-1 cursor-pointer w-full py-1">
                            <span>⚙️ Kelola Seluruh Armada Perangkat IoT</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Badges & Controls -->
        <div class="flex items-center space-x-3">
            
            <!-- MQTT Status Badge -->
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full inline-flex items-center space-x-2 px-3.5 py-1.5 text-xs font-semibold shadow-2xs">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="tracking-wider uppercase text-[11px] font-bold">Mosquitto MQTT Live</span>
            </div>

            <!-- Real-time Clock Badge -->
            <div class="bg-slate-50 border border-slate-200 text-slate-700 rounded-full inline-flex items-center space-x-2 px-3.5 py-1.5 text-xs shadow-2xs">
                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 hidden sm:inline">Jam Server:</span>
                <span id="server-clock" class="text-xs font-mono font-black text-teal-600">
                    {{ date('H:i:s') }} WIB
                </span>
            </div>

        </div>
    </div>
</header>
