@props([
    'id' => 1,
    'pin' => 18,
    'name' => 'AC 1 (Panel Bawah)',
    'color' => 'teal',
    'schedules' => []
])

@php
    $bgCard = $color === 'teal' ? 'border-teal-200/80 hover:border-teal-400' : 'border-cyan-200/80 hover:border-cyan-400';
    $badgeBg = $color === 'teal' ? 'bg-teal-50 text-teal-700 border-teal-200' : 'bg-cyan-50 text-cyan-700 border-cyan-200';
    $accentText = $color === 'teal' ? 'text-teal-600' : 'text-cyan-600';
    $accentBg = $color === 'teal' ? 'bg-teal-500' : 'bg-cyan-500';
@endphp

<!-- CLEAN CARD UNTUK UNIT AC {{ $id }} -->
<div class="bg-white border-2 {{ $bgCard }} rounded-3xl p-6 shadow-sm transition-all duration-200 flex flex-col justify-between space-y-6">
    
    <!-- HEADER CARD: NAMA AC + BADGE STATUS + SWITCH ON/OFF -->
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-2xl {{ $accentBg }} text-white font-black text-lg flex items-center justify-center shadow-md font-outfit">
                AC{{ $id }}
            </div>
            <div>
                <h3 class="font-outfit font-black text-slate-800 text-lg uppercase tracking-wide">
                    {{ $name }}
                </h3>
                <div class="flex items-center space-x-2 mt-0.5">
                    <span id="ac{{ $id }}-badge-label" class="px-2.5 py-0.5 rounded-md text-[11px] font-extrabold border uppercase tracking-wider {{ $badgeBg }}">
                        Offline
                    </span>
                    <span class="text-xs text-slate-400 font-semibold">ESP32 Pin {{ $pin }}</span>
                </div>
            </div>
        </div>

        <!-- IOS TOGGLE SWITCH ON/OFF -->
        <div class="flex items-center space-x-3 bg-slate-100 px-4 py-2 rounded-2xl border border-slate-200">
            <span id="ac{{ $id }}-switch-text" class="text-xs font-black uppercase tracking-wider text-slate-400">OFF</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="ac{{ $id }}-switch" onchange="sendAcControlViaSwitch({{ $id }}, this)" class="sr-only peer">
                <div class="w-14 h-7 bg-slate-300 rounded-full peer peer-focus:ring-2 peer-focus:ring-teal-400 transition-all peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-7"></div>
            </label>
        </div>
    </div>

    <!-- 3 KARTU INFORMASI PENTING (Arus, Suhu/Pendingin, Jam Atur AC) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <!-- 1. ARUS LISTRIK (AMPERE ⚡) -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Arus Listrik</span>
                <span class="text-amber-500 font-black text-sm">⚡</span>
            </div>
            <div class="my-2">
                <span id="ac{{ $id }}-current" class="font-outfit font-black text-2xl text-slate-900 font-mono">0.0000</span>
                <span class="text-xs font-bold text-slate-500">Ampere</span>
            </div>
            <span class="text-[10px] text-slate-400 font-medium">Recorded: <span id="ac{{ $id }}-time">Live</span></span>
        </div>

        <!-- 2. INFORMASI PENDINGIN / SUHU AC -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Info Pendingin</span>
                <span class="text-cyan-500 font-black text-sm">❄️</span>
            </div>
            <div class="my-2">
                <span class="font-outfit font-black text-2xl text-slate-900">24°C</span>
                <span class="text-xs font-bold text-slate-500">Cooling Mode</span>
            </div>
            <span class="text-[10px] text-teal-600 font-bold uppercase tracking-wider">Suhu Optimal</span>
        </div>

        <!-- 3. JAM ATUR AC / JADWAL AKTIFF -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Jam Atur AC</span>
                <span class="text-slate-500 font-black text-sm">⏱️</span>
            </div>
            <div class="my-2">
                @if(count($schedules) > 0)
                    <span class="font-outfit font-black text-lg text-slate-900 font-mono">
                        {{ \Illuminate\Support\Carbon::parse($schedules[0]->start_time)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($schedules[0]->end_time)->format('H:i') }}
                    </span>
                @else
                    <span class="font-outfit font-black text-lg text-slate-900 font-mono">08:00 - 18:00</span>
                @endif
                <span class="text-[11px] font-semibold text-slate-500 block truncate">
                    @if(count($schedules) > 0) {{ $schedules[0]->label }} @else Jam Kantor Otomatis @endif
                </span>
            </div>
            <span class="text-[10px] text-slate-400 font-medium">Status: Schedule Active</span>
        </div>

    </div>

</div>
