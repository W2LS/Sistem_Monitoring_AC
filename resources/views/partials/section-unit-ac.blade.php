<!-- SECTION 2: UNIT AC (DETAIL & KONTROL MANUAL - MODERN GUI) -->
<div class="space-y-6">
    
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 font-sans text-slate-800">
        <div>
            <h2 class="font-outfit font-black text-2xl uppercase tracking-wide flex items-center space-x-2">
                <span>🖥️</span>
                <span>Detail & Kontrol Unit AC</span>
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Kelola status, mode operasi manual, dan informasi telemetri masing-masing unit pendingin.
            </p>
        </div>
        <span class="bg-teal-50 border border-teal-200 text-teal-700 text-xs font-bold px-3 py-1.5 rounded-full">
            2 Unit Konfigurasi Active
        </span>
    </div>

    <!-- GRID 2 UNIT AC PANASONIC -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- PANASONIC 1 -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6 hover:shadow-md transition font-sans">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-600 font-black text-xl flex items-center justify-center">
                        ❄️
                    </div>
                    <div>
                        <h3 class="font-outfit font-black text-lg text-slate-800 uppercase tracking-wide">
                            PANASONIC 1
                        </h3>
                        <p class="text-xs text-slate-500 font-semibold">Lampu Panel Bawah • ESP32 GPIO Pin 18</p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-700 border border-emerald-300 inline-flex items-center">
                    <span class="bg-emerald-500 w-2 h-2 rounded-full mr-1.5 animate-pulse"></span> ONLINE
                </span>
            </div>

            <!-- Operational Status Badges -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 my-4 grid grid-cols-2 gap-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider block text-slate-400">Status ESP32</span>
                    <span class="text-xs font-extrabold text-slate-700">Terhubung (GPIO 18)</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider block text-slate-400">Broker MQTT</span>
                    <span class="text-xs font-extrabold text-emerald-600">Subscribed (Active)</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider block text-slate-400">Arus Real-time</span>
                    <span id="tab2-ac1-current" class="text-sm font-black font-mono text-teal-600">0.0000 Ampere</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider block text-slate-400">Relay State</span>
                    <span id="tab2-ac1-status" class="text-xs font-bold font-mono text-slate-600">READY</span>
                </div>
            </div>

            <!-- Manual Control Buttons -->
            <div class="space-y-2 pt-2">
                <span class="text-xs font-extrabold uppercase tracking-wider block text-slate-700">
                    Kontrol Operasi Manual
                </span>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="sendAcControlViaSwitch(1, { checked: true })" 
                            class="w-full font-bold py-3 px-4 transition flex items-center justify-center space-x-2 cursor-pointer bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-xs shadow-md">
                        <span>⚡ Nyalakan AC (ON)</span>
                    </button>

                    <button type="button" onclick="sendAcControlViaSwitch(1, { checked: false })" 
                            class="w-full font-bold py-3 px-4 transition flex items-center justify-center space-x-2 cursor-pointer bg-rose-600 hover:bg-rose-500 text-white rounded-2xl text-xs shadow-md">
                        <span>🛑 Matikan AC (OFF)</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- PANASONIC 2 -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6 hover:shadow-md transition font-sans">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-600 font-black text-xl flex items-center justify-center">
                        ❄️
                    </div>
                    <div>
                        <h3 class="font-outfit font-black text-lg text-slate-800 uppercase tracking-wide">
                            PANASONIC 2
                        </h3>
                        <p class="text-xs text-slate-500 font-semibold">Lampu Panel Atas • ESP32 GPIO Pin 19</p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-700 border border-emerald-300 inline-flex items-center">
                    <span class="bg-emerald-500 w-2 h-2 rounded-full mr-1.5 animate-pulse"></span> ONLINE
                </span>
            </div>

            <!-- Operational Status Badges -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 my-4 grid grid-cols-2 gap-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider block text-slate-400">Status ESP32</span>
                    <span class="text-xs font-extrabold text-slate-700">Terhubung (GPIO 19)</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider block text-slate-400">Broker MQTT</span>
                    <span class="text-xs font-extrabold text-emerald-600">Subscribed (Active)</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider block text-slate-400">Arus Real-time</span>
                    <span id="tab2-ac2-current" class="text-sm font-black font-mono text-cyan-600">0.0000 Ampere</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider block text-slate-400">Relay State</span>
                    <span id="tab2-ac2-status" class="text-xs font-bold font-mono text-slate-600">READY</span>
                </div>
            </div>

            <!-- Manual Control Buttons -->
            <div class="space-y-2 pt-2">
                <span class="text-xs font-extrabold uppercase tracking-wider block text-slate-700">
                    Kontrol Operasi Manual
                </span>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="sendAcControlViaSwitch(2, { checked: true })" 
                            class="w-full font-bold py-3 px-4 transition flex items-center justify-center space-x-2 cursor-pointer bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-xs shadow-md">
                        <span>⚡ Nyalakan AC (ON)</span>
                    </button>

                    <button type="button" onclick="sendAcControlViaSwitch(2, { checked: false })" 
                            class="w-full font-bold py-3 px-4 transition flex items-center justify-center space-x-2 cursor-pointer bg-rose-600 hover:bg-rose-500 text-white rounded-2xl text-xs shadow-md">
                        <span>🛑 Matikan AC (OFF)</span>
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>
