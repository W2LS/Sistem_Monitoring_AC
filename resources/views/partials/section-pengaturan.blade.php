<!-- SECTION 6: PENGATURAN & KONFIGURASI SISTEM -->
<div class="space-y-6">
    
    <!-- PAGE HEADER -->
    <div class="border-b border-slate-200 pb-4">
        <h2 class="font-outfit font-black text-2xl text-slate-800 uppercase tracking-wide flex items-center space-x-2">
            <span class="text-teal-600">⚙️</span>
            <span>Pengaturan & Konfigurasi Sistem</span>
        </h2>
        <p class="text-xs font-semibold text-slate-500 mt-1">
            Konfigurasi koneksi Hardware ESP32, Parameter MQTT Broker, dan ambang batas peringatan (*threshold alert*).
        </p>
    </div>

    <!-- GRID CONFIGURATION FORMS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- ESP32 & RELAY PIN SETTINGS -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
            <h3 class="font-outfit font-black text-base text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-3 flex items-center space-x-2">
                <span>🔌</span>
                <span>Konfigurasi Pin GPIO ESP32</span>
            </h3>

            <div class="space-y-4 text-xs font-bold">
                <div>
                    <label class="text-slate-600 uppercase tracking-wider block mb-1">Pin Relay AC 1 (Panasonic 1)</label>
                    <input type="text" value="GPIO 18" readonly class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl font-mono text-slate-800">
                </div>

                <div>
                    <label class="text-slate-600 uppercase tracking-wider block mb-1">Pin Relay AC 2 (Panasonic 2)</label>
                    <input type="text" value="GPIO 19" readonly class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl font-mono text-slate-800">
                </div>

                <div>
                    <label class="text-slate-600 uppercase tracking-wider block mb-1">Pin Sensor Arus ACS712</label>
                    <input type="text" value="ADC GPIO 34" readonly class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl font-mono text-slate-800">
                </div>
            </div>
        </div>

        <!-- MQTT BROKER & ALERT THRESHOLDS -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
            <h3 class="font-outfit font-black text-base text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-3 flex items-center space-x-2">
                <span>🌐</span>
                <span>Konfigurasi Broker MQTT & Alert</span>
            </h3>

            <div class="space-y-4 text-xs font-bold">
                <div>
                    <label class="text-slate-600 uppercase tracking-wider block mb-1">Broker Address</label>
                    <input type="text" value="broker.hivemq.com (Port 1883)" readonly class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl font-mono text-slate-800">
                </div>

                <div>
                    <label class="text-slate-600 uppercase tracking-wider block mb-1">Topic Subscribed</label>
                    <input type="text" value="pindad/ac/telemetry" readonly class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl font-mono text-teal-600">
                </div>

                <div>
                    <label class="text-slate-600 uppercase tracking-wider block mb-1">Batas Maksimum Arus Alert (Ampere)</label>
                    <input type="text" value="2.50 Ampere" readonly class="w-full bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl font-mono text-amber-600">
                </div>
            </div>
        </div>

    </div>

</div>
