<!-- SECTION 2: UNIT AC (DETAIL & KONTROL MANUAL - DUAL THEME) -->
<div class="space-y-6">
    
    <!-- PAGE HEADER -->
    <div :class="currentTheme === 'cli' ? 'border-b border-[#1f521f] pb-3 text-[#33ff00] font-mono' : 'border-b border-slate-200 pb-4 font-sans text-slate-800'" class="flex items-center justify-between">
        <div>
            <h2 :class="currentTheme === 'cli' ? 'text-xl font-mono font-bold cli-glow' : 'font-outfit font-black text-2xl'" class="uppercase tracking-wide flex items-center space-x-2">
                <span x-text="currentTheme === 'cli' ? '> SYS_SUBSYSTEM :' : '🖥️'"></span>
                <span x-text="currentTheme === 'cli' ? 'DUAL_AC_UNIT_CONTROLLER' : 'Detail & Kontrol Unit AC'"></span>
            </h2>
            <p :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-xs font-semibold text-slate-500 mt-1'">
                Kelola status, mode operasi manual, dan informasi telemetri masing-masing unit pendingin.
            </p>
        </div>
        <span :class="currentTheme === 'cli' ? 'border border-[#33ff00] bg-[#0a0a0a] text-[#33ff00] rounded-none text-[10px] font-mono cli-glow' : 'bg-teal-50 border border-teal-200 text-teal-700 text-xs font-bold px-3 py-1.5 rounded-full'">
            <span x-text="currentTheme === 'cli' ? '[ 2_NODES_CONFIGURED : ACTIVE ]' : '2 Unit Konfigurasi Active'"></span>
        </span>
    </div>

    <!-- GRID 2 UNIT AC PANASONIC -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- PANASONIC 1 -->
        <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] rounded-none p-5 text-[#33ff00] font-mono' : 'bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6 hover:shadow-md transition font-sans'">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div :class="currentTheme === 'cli' ? 'border border-[#33ff00] text-[#33ff00] bg-transparent rounded-none text-xs p-2' : 'w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-600 font-black text-xl flex items-center justify-center'">
                        <span x-text="currentTheme === 'cli' ? 'AC1' : '❄️'"></span>
                    </div>
                    <div>
                        <h3 :class="currentTheme === 'cli' ? 'font-mono text-[#33ff00] text-base cli-glow' : 'font-outfit font-black text-lg text-slate-800'" class="uppercase tracking-wide">
                            PANASONIC 1
                        </h3>
                        <p :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-xs text-slate-500 font-semibold'">Lampu Panel Bawah • ESP32 GPIO Pin 18</p>
                    </div>
                </div>
                <span :class="currentTheme === 'cli' ? 'border border-[#33ff00] bg-[#0a0a0a] text-[#33ff00] rounded-none text-[10px] cli-glow' : 'px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-700 border border-emerald-300'" class="inline-flex items-center">
                    <span :class="currentTheme === 'cli' ? 'bg-[#33ff00]' : 'bg-emerald-500'" class="w-2 h-2 rounded-full mr-1.5 animate-pulse"></span> ONLINE
                </span>
            </div>

            <!-- Operational Status Badges -->
            <div :class="currentTheme === 'cli' ? 'border border-[#1f521f] bg-[#0a0a0a] rounded-none p-3.5 my-4' : 'bg-slate-50 p-4 rounded-2xl border border-slate-100 my-4'" class="grid grid-cols-2 gap-3">
                <div>
                    <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[10px] font-bold uppercase tracking-wider block">Status ESP32</span>
                    <span :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-700'" class="text-xs font-extrabold">Terhubung (GPIO 18)</span>
                </div>
                <div>
                    <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[10px] font-bold uppercase tracking-wider block">Broker MQTT</span>
                    <span :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-emerald-600'" class="text-xs font-extrabold">Subscribed (Active)</span>
                </div>
                <div>
                    <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[10px] font-bold uppercase tracking-wider block">Arus Real-time</span>
                    <span id="tab2-ac1-current" :class="currentTheme === 'cli' ? 'text-[#33ff00] cli-glow' : 'text-teal-600'" class="text-sm font-black font-mono">0.0000 Ampere</span>
                </div>
                <div>
                    <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[10px] font-bold uppercase tracking-wider block">Relay State</span>
                    <span id="tab2-ac1-status" :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="text-xs font-bold font-mono">READY</span>
                </div>
            </div>

            <!-- Manual Control Buttons -->
            <div class="space-y-2 pt-2">
                <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-700'" class="text-xs font-extrabold uppercase tracking-wider block">
                    <span x-text="currentTheme === 'cli' ? '> MANUAL_COMMAND_EXECUTION :' : 'Kontrol Operasi Manual'"></span>
                </span>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="sendAcControlViaSwitch(1, { checked: true })" 
                            :class="currentTheme === 'cli' ? 'border border-[#33ff00] bg-[#0a0a0a] text-[#33ff00] rounded-none cli-btn-invert text-xs' : 'bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-xs shadow-md'"
                            class="w-full font-bold py-3 px-4 transition flex items-center justify-center space-x-2 cursor-pointer">
                        <span x-text="currentTheme === 'cli' ? '[ EXEC_ON ]' : '⚡ Nyalakan AC (ON)'"></span>
                    </button>

                    <button type="button" onclick="sendAcControlViaSwitch(1, { checked: false })" 
                            :class="currentTheme === 'cli' ? 'border border-[#ff3333] bg-[#0a0a0a] text-[#ff3333] rounded-none hover:bg-[#ff3333] hover:text-[#0a0a0a] text-xs' : 'bg-rose-600 hover:bg-rose-500 text-white rounded-2xl text-xs shadow-md'"
                            class="w-full font-bold py-3 px-4 transition flex items-center justify-center space-x-2 cursor-pointer">
                        <span x-text="currentTheme === 'cli' ? '[ EXEC_OFF ]' : '🛑 Matikan AC (OFF)'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- PANASONIC 2 -->
        <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] rounded-none p-5 text-[#33ff00] font-mono' : 'bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6 hover:shadow-md transition font-sans'">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div :class="currentTheme === 'cli' ? 'border border-[#ffb000] text-[#ffb000] bg-transparent rounded-none text-xs p-2' : 'w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-600 font-black text-xl flex items-center justify-center'">
                        <span x-text="currentTheme === 'cli' ? 'AC2' : '❄️'"></span>
                    </div>
                    <div>
                        <h3 :class="currentTheme === 'cli' ? 'font-mono text-[#ffb000] text-base cli-amber-glow' : 'font-outfit font-black text-lg text-slate-800'" class="uppercase tracking-wide">
                            PANASONIC 2
                        </h3>
                        <p :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-xs text-slate-500 font-semibold'">Lampu Panel Atas • ESP32 GPIO Pin 19</p>
                    </div>
                </div>
                <span :class="currentTheme === 'cli' ? 'border border-[#33ff00] bg-[#0a0a0a] text-[#33ff00] rounded-none text-[10px] cli-glow' : 'px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-700 border border-emerald-300'" class="inline-flex items-center">
                    <span :class="currentTheme === 'cli' ? 'bg-[#33ff00]' : 'bg-emerald-500'" class="w-2 h-2 rounded-full mr-1.5 animate-pulse"></span> ONLINE
                </span>
            </div>

            <!-- Operational Status Badges -->
            <div :class="currentTheme === 'cli' ? 'border border-[#1f521f] bg-[#0a0a0a] rounded-none p-3.5 my-4' : 'bg-slate-50 p-4 rounded-2xl border border-slate-100 my-4'" class="grid grid-cols-2 gap-3">
                <div>
                    <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[10px] font-bold uppercase tracking-wider block">Status ESP32</span>
                    <span :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-700'" class="text-xs font-extrabold">Terhubung (GPIO 19)</span>
                </div>
                <div>
                    <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[10px] font-bold uppercase tracking-wider block">Broker MQTT</span>
                    <span :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-emerald-600'" class="text-xs font-extrabold">Subscribed (Active)</span>
                </div>
                <div>
                    <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[10px] font-bold uppercase tracking-wider block">Arus Real-time</span>
                    <span id="tab2-ac2-current" :class="currentTheme === 'cli' ? 'text-[#ffb000] cli-amber-glow' : 'text-cyan-600'" class="text-sm font-black font-mono">0.0000 Ampere</span>
                </div>
                <div>
                    <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-400'" class="text-[10px] font-bold uppercase tracking-wider block">Relay State</span>
                    <span id="tab2-ac2-status" :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="text-xs font-bold font-mono">READY</span>
                </div>
            </div>

            <!-- Manual Control Buttons -->
            <div class="space-y-2 pt-2">
                <span :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-700'" class="text-xs font-extrabold uppercase tracking-wider block">
                    <span x-text="currentTheme === 'cli' ? '> MANUAL_COMMAND_EXECUTION :' : 'Kontrol Operasi Manual'"></span>
                </span>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="sendAcControlViaSwitch(2, { checked: true })" 
                            :class="currentTheme === 'cli' ? 'border border-[#ffb000] bg-[#0a0a0a] text-[#ffb000] rounded-none cli-btn-invert-amber text-xs' : 'bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-xs shadow-md'"
                            class="w-full font-bold py-3 px-4 transition flex items-center justify-center space-x-2 cursor-pointer">
                        <span x-text="currentTheme === 'cli' ? '[ EXEC_ON ]' : '⚡ Nyalakan AC (ON)'"></span>
                    </button>

                    <button type="button" onclick="sendAcControlViaSwitch(2, { checked: false })" 
                            :class="currentTheme === 'cli' ? 'border border-[#ff3333] bg-[#0a0a0a] text-[#ff3333] rounded-none hover:bg-[#ff3333] hover:text-[#0a0a0a] text-xs' : 'bg-rose-600 hover:bg-rose-500 text-white rounded-2xl text-xs shadow-md'"
                            class="w-full font-bold py-3 px-4 transition flex items-center justify-center space-x-2 cursor-pointer">
                        <span x-text="currentTheme === 'cli' ? '[ EXEC_OFF ]' : '🛑 Matikan AC (OFF)'"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>
