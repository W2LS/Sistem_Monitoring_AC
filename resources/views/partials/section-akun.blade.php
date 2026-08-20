<!-- SECTION 4: PROFIL AKUN & INFORMASI OPERATOR (PALETTE: #1D1616, #8E1616, #D84040, #EEEEEE) -->
<div class="space-y-6 max-w-3xl mx-auto pb-24">
    
    <!-- HEADER -->
    <div class="border-b border-[#8E1616]/20 pb-4">
        <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Manajemen Pengguna</span>
        <h2 class="text-3xl font-black text-[#1D1616] tracking-tight">
            Akun Administrator IoT
        </h2>
    </div>

    <!-- MAIN PROFILE CARD (40px Radius) -->
    <div class="bg-white rounded-[40px] p-8 shadow-[0_20px_50px_-12px_rgba(29,22,22,0.08)] border border-[#8E1616]/20 relative overflow-hidden space-y-6">
        
        <!-- Decorative subtle blob -->
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#8E1616]/10 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Top Profile Info -->
        <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6 relative z-10">
            <div class="relative w-24 h-24 rounded-full bg-gradient-to-tr from-[#1D1616] via-[#8E1616] to-[#D84040] text-white flex items-center justify-center font-black text-3xl shadow-lg border-4 border-white shrink-0">
                <span>D</span>
                <span class="absolute bottom-1 right-1 w-5 h-5 bg-[#D84040] border-2 border-white rounded-full flex items-center justify-center text-[10px] text-white">✓</span>
            </div>
            
            <div class="space-y-1">
                <div class="inline-flex items-center space-x-2 bg-[#8E1616]/10 border border-[#8E1616]/20 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider text-[#8E1616]">
                    <span>🔒 Super Administrator</span>
                </div>
                <h3 class="text-2xl font-black text-[#1D1616]">Dicky Akbar Syahputra</h3>
                <p class="text-xs font-semibold text-slate-500">
                    Divisi Sistem Informasi & Fasilitas • PT PINDAD (Persero)
                </p>
                <p class="text-xs font-mono font-bold text-[#D84040]">
                    NIP/ID: PINDAD-IOT-2026
                </p>
            </div>
        </div>

        <!-- BENTO DETAILS GRID (24px & 16px Radius) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
            
            <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[24px] p-5 border border-[#8E1616]/15 space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#8E1616] block">Email Resmi</span>
                <span class="text-sm font-extrabold text-[#1D1616]">dicky.akbar@pindad.com</span>
                <span class="text-[10px] text-emerald-700 font-bold block">● Terverifikasi SSO Internal</span>
            </div>

            <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[24px] p-5 border border-[#8E1616]/15 space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#8E1616] block">Hak Akses Kontrol</span>
                <span class="text-sm font-extrabold text-[#1D1616]">Full Relay & Cron Override</span>
                <span class="text-[10px] text-[#D84040] font-bold block">● Akses Penuh Ruang Server 1</span>
            </div>

            <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[24px] p-5 border border-[#8E1616]/15 space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#8E1616] block">Broker Koneksi</span>
                <span class="text-sm font-extrabold text-[#1D1616] font-mono">broker.emqx.io:1883</span>
                <span class="text-[10px] text-slate-500 font-medium block">QoS Level 1 (Retained Telemetry)</span>
            </div>

            <div class="bg-[#EEEEEE]/80 backdrop-blur-md rounded-[24px] p-5 border border-[#8E1616]/15 space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#8E1616] block">Hardware Client</span>
                <span class="text-sm font-extrabold text-[#1D1616] font-mono">ESP32 + ACS712 + RTC DS3231</span>
                <span class="text-[10px] text-emerald-700 font-bold block">● Telemetry Cycle 30 Detik</span>
            </div>

        </div>

        <!-- ACTION BUTTONS -->
        <div class="pt-4 border-t border-[#8E1616]/20 flex flex-col sm:flex-row items-center justify-between gap-3">
            <button onclick="alert('Pengaturan preferensi akun telah disimpan.')" 
                    type="button"
                    class="w-full sm:w-auto px-6 py-3.5 rounded-[24px] border border-[#8E1616]/30 text-[#1D1616] hover:bg-[#EEEEEE] text-xs font-black uppercase tracking-wider transition cursor-pointer">
                ⚙️ Pengaturan Notifikasi
            </button>

            <button onclick="if(confirm('Apakah Anda yakin ingin keluar dari sesi dashboard?')) { window.location.reload(); }" 
                    type="button"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-[24px] bg-[#D84040] hover:bg-[#8E1616] text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-[#D84040]/30 transition cursor-pointer">
                🚪 Keluar / Logout
            </button>
        </div>

    </div>

</div>
