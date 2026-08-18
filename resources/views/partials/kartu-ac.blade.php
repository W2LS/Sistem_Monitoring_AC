<!-- AC {{ $id }} CARD ({{ $name }}) -->
<div id="ac{{ $id }}-card" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:shadow-md">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-2xl bg-{{ $color }}-50 flex items-center justify-center text-{{ $color }}-600">
                <!-- AC / Fan Icon -->
                <svg class="w-6 h-6 animate-[spin_{{ $id === 1 ? 10 : 12 }}s_linear_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-{{ $color }}-600 uppercase tracking-wider block">AC Unit {{ $id }} (Pin {{ $pin }})</span>
                <h4 class="font-outfit font-bold text-slate-800 text-lg">{{ $name }}</h4>
            </div>
        </div>
        
        <!-- iOS Switch Toggle with Label -->
        <div class="flex items-center space-x-2">
            <span id="ac{{ $id }}-switch-text" class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">OFF</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="ac{{ $id }}-switch" onchange="sendAcControlViaSwitch({{ $id }}, this)" class="sr-only peer">
                <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-{{ $color }}-100 transition-all peer-checked:bg-{{ $color }}-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-6"></div>
            </label>
        </div>
    </div>

    <!-- Current Reading Section -->
    <div class="bg-slate-50/80 rounded-2xl p-4 flex items-center justify-between border border-slate-100">
        <div>
            <span class="text-xs text-slate-400 font-semibold block">Arus Listrik (Current)</span>
            <div class="flex items-baseline space-x-1 mt-1">
                <span id="ac{{ $id }}-current" class="font-outfit font-extrabold text-3xl text-slate-800 font-mono">0.0000</span>
                <span class="text-xs font-bold text-slate-400">A</span>
            </div>
        </div>
        <div id="ac{{ $id }}-status-text" class="text-right">
            <span class="text-xs text-slate-400 font-semibold block">Status</span>
            <span id="ac{{ $id }}-badge-label" class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 block">Offline / Loading</span>
        </div>
    </div>

    <!-- Timestamp -->
    <div class="mt-4 flex justify-between items-center text-[10px] text-slate-400 font-medium">
        <span>ESP32 Telemetry</span>
        <span>Updated: <span id="ac{{ $id }}-time" class="font-mono">Never</span></span>
    </div>
</div>
