<!-- SECTION 6: PENGATURAN & KONFIGURASI SISTEM (DUAL THEME) -->
<div class="space-y-6">
    
    <!-- PAGE HEADER -->
    <div :class="currentTheme === 'cli' ? 'border-b border-[#1f521f] pb-3 text-[#33ff00] font-mono' : 'border-b border-slate-200 pb-4 font-sans text-slate-800'">
        <h2 :class="currentTheme === 'cli' ? 'text-xl font-mono font-bold cli-glow' : 'font-outfit font-black text-2xl'" class="uppercase tracking-wide flex items-center space-x-2">
            <span x-text="currentTheme === 'cli' ? '> SYSTEM_ENVIRONMENT :' : '⚙️'"></span>
            <span x-text="currentTheme === 'cli' ? 'HARDWARE_&_MQTT_CONFIGURATION' : 'Pengaturan & Konfigurasi Sistem'"></span>
        </h2>
        <p :class="currentTheme === 'cli' ? 'text-[10px] text-[#1f521f]' : 'text-xs font-semibold text-slate-500 mt-1'">
            Konfigurasi koneksi Hardware ESP32, Parameter MQTT Broker, dan ambang batas peringatan (*threshold alert*).
        </p>
    </div>

    <!-- GRID CONFIGURATION FORMS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- ESP32 & RELAY PIN SETTINGS -->
        <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] rounded-none text-[#33ff00] font-mono' : 'bg-white rounded-3xl border border-slate-200 shadow-sm font-sans'" class="p-6 space-y-4">
            <h3 :class="currentTheme === 'cli' ? 'border-b border-[#1f521f] pb-3 text-[#ffb000]' : 'border-b border-slate-100 pb-3 text-slate-800'" class="font-outfit font-black text-base uppercase tracking-wide flex items-center space-x-2">
                <span x-text="currentTheme === 'cli' ? '[PIN_MAP]' : '🔌'"></span>
                <span x-text="currentTheme === 'cli' ? 'ESP32_GPIO_HARDWARE_MAP' : 'Konfigurasi Pin GPIO ESP32'"></span>
            </h3>

            <div class="space-y-4 text-xs font-bold font-mono">
                <div>
                    <label :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="uppercase tracking-wider block mb-1">Pin Relay AC 1 (Panasonic 1)</label>
                    <input type="text" value="GPIO 18" readonly 
                           :class="currentTheme === 'cli' ? 'bg-[#0a0a0a] border border-[#1f521f] text-[#33ff00] rounded-none' : 'bg-slate-50 border border-slate-200 rounded-xl text-slate-800'"
                           class="w-full px-3.5 py-2.5">
                </div>

                <div>
                    <label :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="uppercase tracking-wider block mb-1">Pin Relay AC 2 (Panasonic 2)</label>
                    <input type="text" value="GPIO 19" readonly 
                           :class="currentTheme === 'cli' ? 'bg-[#0a0a0a] border border-[#1f521f] text-[#33ff00] rounded-none' : 'bg-slate-50 border border-slate-200 rounded-xl text-slate-800'"
                           class="w-full px-3.5 py-2.5">
                </div>

                <div>
                    <label :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="uppercase tracking-wider block mb-1">Pin Sensor Arus ACS712</label>
                    <input type="text" value="ADC GPIO 34" readonly 
                           :class="currentTheme === 'cli' ? 'bg-[#0a0a0a] border border-[#1f521f] text-[#33ff00] rounded-none' : 'bg-slate-50 border border-slate-200 rounded-xl text-slate-800'"
                           class="w-full px-3.5 py-2.5">
                </div>
            </div>
        </div>

        <!-- MQTT BROKER & ALERT THRESHOLDS -->
        <div :class="currentTheme === 'cli' ? 'bg-[#050505] border border-[#1f521f] rounded-none text-[#33ff00] font-mono' : 'bg-white rounded-3xl border border-slate-200 shadow-sm font-sans'" class="p-6 space-y-4">
            <h3 :class="currentTheme === 'cli' ? 'border-b border-[#1f521f] pb-3 text-[#ffb000]' : 'border-b border-slate-100 pb-3 text-slate-800'" class="font-outfit font-black text-base uppercase tracking-wide flex items-center space-x-2">
                <span x-text="currentTheme === 'cli' ? '[NET_CONF]' : '🌐'"></span>
                <span x-text="currentTheme === 'cli' ? 'MQTT_BROKER_PROTOCOL_SPEC' : 'Konfigurasi Broker MQTT & Alert'"></span>
            </h3>

            <div class="space-y-4 text-xs font-bold font-mono">
                <div>
                    <label :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="uppercase tracking-wider block mb-1">Broker Host & Port</label>
                    <input type="text" value="broker.emqx.io:1883" readonly 
                           :class="currentTheme === 'cli' ? 'bg-[#0a0a0a] border border-[#1f521f] text-[#33ff00] rounded-none' : 'bg-slate-50 border border-slate-200 rounded-xl text-slate-800'"
                           class="w-full px-3.5 py-2.5">
                </div>

                <div>
                    <label :class="currentTheme === 'cli' ? 'text-[#33ff00]' : 'text-slate-600'" class="uppercase tracking-wider block mb-1">Topic Subscribed</label>
                    <input type="text" value="pindad/ac/logs" readonly 
                           :class="currentTheme === 'cli' ? 'bg-[#0a0a0a] border border-[#1f521f] text-[#33ff00] rounded-none' : 'bg-slate-50 border border-slate-200 rounded-xl text-teal-600'"
                           class="w-full px-3.5 py-2.5">
                </div>

                <div>
                    <label :class="currentTheme === 'cli' ? 'text-[#ffb000]' : 'text-slate-600'" class="uppercase tracking-wider block mb-1">Overcurrent Protection Threshold</label>
                    <input type="text" value="2.5000 Ampere (Max 2200 Watt)" readonly 
                           :class="currentTheme === 'cli' ? 'bg-[#0a0a0a] border border-[#ffb000] text-[#ffb000] rounded-none cli-amber-glow' : 'bg-slate-50 border border-slate-200 rounded-xl text-amber-600'"
                           class="w-full px-3.5 py-2.5">
                </div>
            </div>
        </div>

    </div>

</div>
