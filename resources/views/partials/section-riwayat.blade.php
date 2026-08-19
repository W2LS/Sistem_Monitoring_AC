<!-- SECTION 5: RIWAYAT & AKTIVITAS SISTEM -->
<div class="space-y-6">
    
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h2 class="font-outfit font-black text-2xl text-slate-800 uppercase tracking-wide flex items-center space-x-2">
                <span class="text-teal-600">📜</span>
                <span>Riwayat Aktivitas System (Industrial Log)</span>
            </h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Catatan kronologis perubahan status ON/OFF, fluktuasi arus, dan aksi kontrol otomatis.
            </p>
        </div>
        <button onclick="alert('Log berhasil diekspor ke format CSV.')" class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold py-2 px-3.5 rounded-xl shadow-sm transition">
            📥 Ekspor Log CSV
        </button>
    </div>

    <!-- MAIN LOG TABLE -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <span class="text-xs font-black text-slate-700 uppercase tracking-wider">Live System Logs</span>
            <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                ● Auto-Recording Active
            </span>
        </div>

        <div class="divide-y divide-slate-100 font-mono text-xs">
            
            <!-- Log Entry 1 -->
            <div class="p-4 hover:bg-slate-50/80 transition flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-slate-400 font-bold">17:00:03</span>
                    <span class="bg-rose-50 text-rose-700 font-sans border border-rose-200 px-2 py-0.5 rounded text-[10px] font-extrabold uppercase">OFF EVENT</span>
                    <span class="text-slate-700 font-bold">Panasonic 1</span>
                    <span class="text-slate-400">→</span>
                    <span class="text-slate-600">Arus turun menjadi <strong class="text-slate-900">0.00 A</strong></span>
                </div>
                <span class="text-[10px] text-slate-400 font-sans font-semibold">Hari ini</span>
            </div>

            <!-- Log Entry 2 -->
            <div class="p-4 hover:bg-slate-50/80 transition flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-slate-400 font-bold">17:00:00</span>
                    <span class="bg-amber-50 text-amber-700 font-sans border border-amber-200 px-2 py-0.5 rounded text-[10px] font-extrabold uppercase">SCHEDULED CONTROL</span>
                    <span class="text-slate-700 font-bold">Panasonic 1</span>
                    <span class="text-slate-400">→</span>
                    <span class="text-rose-600 font-extrabold">STATUS CHANGED TO OFF</span>
                </div>
                <span class="text-[10px] text-slate-400 font-sans font-semibold">Hari ini</span>
            </div>

            <!-- Log Entry 3 -->
            <div class="p-4 hover:bg-slate-50/80 transition flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-slate-400 font-bold">10:05:24</span>
                    <span class="bg-teal-50 text-teal-700 font-sans border border-teal-200 px-2 py-0.5 rounded text-[10px] font-extrabold uppercase">SENSOR DETECT</span>
                    <span class="text-slate-700 font-bold">Panasonic 1</span>
                    <span class="text-slate-400">→</span>
                    <span class="text-slate-600">Arus terdeteksi <strong class="text-teal-600">0.48 A</strong></span>
                </div>
                <span class="text-[10px] text-slate-400 font-sans font-semibold">Hari ini</span>
            </div>

            <!-- Log Entry 4 -->
            <div class="p-4 hover:bg-slate-50/80 transition flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-slate-400 font-bold">10:05:21</span>
                    <span class="bg-emerald-50 text-emerald-700 font-sans border border-emerald-200 px-2 py-0.5 rounded text-[10px] font-extrabold uppercase">MANUAL CONTROL</span>
                    <span class="text-slate-700 font-bold">Panasonic 1</span>
                    <span class="text-slate-400">→</span>
                    <span class="text-emerald-600 font-extrabold">STATUS CHANGED TO ON</span>
                </div>
                <span class="text-[10px] text-slate-400 font-sans font-semibold">Hari ini</span>
            </div>

        </div>
    </div>

</div>
