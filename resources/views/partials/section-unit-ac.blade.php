<!-- SECTION 2: UNIT AC (DETAIL & KONTROL MANUAL) -->
<div class="space-y-6">
    
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h2 class="font-outfit font-black text-2xl text-slate-800 uppercase tracking-wide flex items-center space-x-2">
                <span class="text-teal-600">🖥️</span>
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
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6 hover:shadow-md transition">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-600 flex items-center justify-center font-black text-xl">
                        ❄️
                    </div>
                    <div>
                        <h3 class="font-outfit font-black text-lg text-slate-800 uppercase tracking-wide">PANASONIC 1</h3>
                        <p class="text-xs text-slate-500 font-semibold">Lampu Panel Bawah • ESP32 Pin 18</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-700 border border-emerald-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> ONLINE
                </span>
            </div>

            <!-- Operational Status Badges -->
            <div class="grid grid-cols-2 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status Koneksi ESP32</span>
                    <span class="text-xs font-extrabold text-slate-700">Terhubung (GPIO 18)</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status Broker MQTT</span>
                    <span class="text-xs font-extrabold text-emerald-600">Subscribed (Active)</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Arus Real-time</span>
                    <span class="text-sm font-black font-mono text-teal-600">0.0000 Ampere</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Terakhir Dikontrol</span>
                    <span class="text-xs font-bold text-slate-600">Hari ini, 08:00 WIB</span>
                </div>
            </div>

            <!-- Manual Control Switches -->
            <div class="space-y-2 pt-2">
                <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider block">Kontrol Operasi Manual</span>
                <div class="grid grid-cols-2 gap-3">
                    <form action="{{ route('ac.control') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="1">
                        <input type="hidden" name="state" value="1">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 px-4 rounded-2xl shadow-md transition flex items-center justify-center space-x-2">
                            <span>⚡</span>
                            <span>Nyalakan AC (ON)</span>
                        </button>
                    </form>

                    <form action="{{ route('ac.control') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="1">
                        <input type="hidden" name="state" value="0">
                        <button type="submit" class="w-full bg-rose-600 hover:bg-rose-500 text-white font-bold py-3 px-4 rounded-2xl shadow-md transition flex items-center justify-center space-x-2">
                            <span>🛑</span>
                            <span>Matikan AC (OFF)</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- PANASONIC 2 -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6 hover:shadow-md transition">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-600 flex items-center justify-center font-black text-xl">
                        ❄️
                    </div>
                    <div>
                        <h3 class="font-outfit font-black text-lg text-slate-800 uppercase tracking-wide">PANASONIC 2</h3>
                        <p class="text-xs text-slate-500 font-semibold">Lampu Panel Atas • ESP32 Pin 19</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-700 border border-emerald-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> ONLINE
                </span>
            </div>

            <!-- Operational Status Badges -->
            <div class="grid grid-cols-2 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status Koneksi ESP32</span>
                    <span class="text-xs font-extrabold text-slate-700">Terhubung (GPIO 19)</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status Broker MQTT</span>
                    <span class="text-xs font-extrabold text-emerald-600">Subscribed (Active)</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Arus Real-time</span>
                    <span class="text-sm font-black font-mono text-cyan-600">0.0000 Ampere</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Terakhir Dikontrol</span>
                    <span class="text-xs font-bold text-slate-600">Hari ini, 18:00 WIB</span>
                </div>
            </div>

            <!-- Manual Control Switches -->
            <div class="space-y-2 pt-2">
                <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider block">Kontrol Operasi Manual</span>
                <div class="grid grid-cols-2 gap-3">
                    <form action="{{ route('ac.control') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="2">
                        <input type="hidden" name="state" value="1">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 px-4 rounded-2xl shadow-md transition flex items-center justify-center space-x-2">
                            <span>⚡</span>
                            <span>Nyalakan AC (ON)</span>
                        </button>
                    </form>

                    <form action="{{ route('ac.control') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="2">
                        <input type="hidden" name="state" value="0">
                        <button type="submit" class="w-full bg-rose-600 hover:bg-rose-500 text-white font-bold py-3 px-4 rounded-2xl shadow-md transition flex items-center justify-center space-x-2">
                            <span>🛑</span>
                            <span>Matikan AC (OFF)</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
