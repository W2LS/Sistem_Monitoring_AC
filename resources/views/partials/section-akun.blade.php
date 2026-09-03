<!-- ================= MODUL 4: PUSAT INFORMASI AKUN & SISTEM IOT ================= -->
<div class="space-y-6 pb-20" x-data="{ 
    openItem: null,
    modalPassword: false,
}">
    
    <!-- 1. PAGE HEADER -->
    <div class="border-b border-[#8E1616]/20 pb-4">
        <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#8E1616] flex items-center gap-1.5">
            <span>⚙️</span>
            <span>PUSAT PENGATURAN & INFORMASI SISTEM</span>
        </span>
        <h2 class="text-2xl sm:text-3xl font-black text-[#1D1616] tracking-tight mt-0.5">
            Akun Operator & Informasi Sistem
        </h2>
        <p class="text-xs font-semibold text-slate-500 mt-1">
            Panduan lengkap penggunaan sistem, manajemen kredensial operator, dan spesifikasi arsitektur IoT PT PINDAD.
        </p>
    </div>

    <!-- 2. ACCORDIONS SECTION -->
    <div class="space-y-4">

        <!-- ITEM 0: TUTORIAL & PANDUAN CEPAT SETUP NODE IOT (SUPER SIMPLE 3-STEPS) -->
        <div class="bg-white rounded-[32px] border-2 border-[#D84040]/40 shadow-md overflow-hidden transition-all duration-300">
            <button @click="openItem = openItem === 'tutorial' ? null : 'tutorial'" 
                    type="button" 
                    class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer bg-gradient-to-r from-rose-50/50 to-white">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#D84040] text-white flex items-center justify-center font-black text-xl shrink-0 shadow-md shadow-[#D84040]/30">
                        📖
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-black text-[#1D1616]">Panduan Praktis Setup Perangkat IoT</h3>
                            <span class="bg-emerald-600 text-white text-[9px] font-black uppercase px-2 py-0.5 rounded-full">3 LANGKAH MUDAH</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-500">Panduan praktis mendaftarkan dan menghubungkan Raspberry Pi ke sistem SIKOMAT</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'tutorial' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT: 3 SIMPLE STEPS -->
            <div x-show="openItem === 'tutorial'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-4 border-t border-[#8E1616]/10 space-y-4 bg-slate-50/70">
                
                <!-- 3 Steps Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5 text-xs">
                    
                    <!-- Langkah 1 -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2.5 flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-xl bg-[#8E1616] text-white flex items-center justify-center font-black text-xs shrink-0">1</span>
                                <h4 class="font-black text-sm text-[#1D1616]">Daftarkan di Web</h4>
                            </div>
                            <p class="text-slate-600 leading-relaxed text-[11.5px]">
                                Buka menu <b>Home</b> &rarr; klik <b>`+ Tambah Perangkat Baru`</b>. Masukkan Nama Ruangan, IP Raspberry Pi, dan pilih template Modul Relay.
                            </p>
                        </div>
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-[11px] text-slate-500 font-medium">
                            💡 Contoh: <i>Server Telepon (192.168.196.18)</i>
                        </div>
                    </div>

                    <!-- Langkah 2 -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border-2 border-emerald-400/80 shadow-xs space-y-2.5 flex flex-col justify-between bg-emerald-50/20">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-xs shrink-0">2</span>
                                    <h4 class="font-black text-sm text-[#1D1616]">Unduh Skrip (.py)</h4>
                                </div>
                                <span class="text-[8.5px] font-black uppercase bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-bold">Auto-Config</span>
                            </div>
                            <p class="text-slate-600 leading-relaxed text-[11.5px]">
                                Pada kartu perangkat di Home, klik tombol <b>`📥 Unduh Skrip (.py)`</b>. Web otomatis membuat file Python mandiri yang sudah lengkap terkonfigurasi.
                            </p>
                        </div>
                        <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-[11px] font-mono text-emerald-900 font-bold truncate">
                            📄 pindad_node_rpi3b_....py
                        </div>
                    </div>

                    <!-- Langkah 3 -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2.5 flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-xl bg-[#8E1616] text-white flex items-center justify-center font-black text-xs shrink-0">3</span>
                                <h4 class="font-black text-sm text-[#1D1616]">Jalankan di Raspberry Pi</h4>
                            </div>
                            <p class="text-slate-600 leading-relaxed text-[11.5px]">
                                Salin file <code class="bg-slate-100 px-1 py-0.5 rounded font-mono text-slate-800">.py</code> ke Raspberry Pi (SSH / WinSCP), lalu jalankan perintah:
                            </p>
                        </div>
                        <code class="block font-mono text-[11px] bg-slate-900 text-emerald-400 p-2.5 rounded-xl select-all">
                            python3 nama_skrip_anda.py
                        </code>
                    </div>

                </div>

                <!-- 2 Compact Helper Cards: Library & Auto-Start -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 pt-1 text-xs">
                    
                    <!-- Helper 1: Library Dependency -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-base">📦</span>
                                <h4 class="font-black text-xs uppercase tracking-wider text-[#1D1616]">Install Library (Khusus RPi Baru / 1x Setup)</h4>
                            </div>
                            <span class="text-[9px] font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-500 font-bold">Python Pip</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Jalankan sekali saja jika Raspberry Pi baru belum memiliki library pendukung:</p>
                        <code class="block font-mono text-[10.5px] bg-slate-900 text-slate-100 p-2.5 rounded-xl select-all overflow-x-auto leading-relaxed">
                            pip3 install paho-mqtt adafruit-circuitpython-ads1x15 adafruit-circuitpython-ds3231 RPi.GPIO
                        </code>
                    </div>

                    <!-- Helper 2: Auto-Start On Boot -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-base">⚡</span>
                                <h4 class="font-black text-xs uppercase tracking-wider text-[#1D1616]">Auto-Start Saat Listrik Menyala (Opsional)</h4>
                            </div>
                            <span class="text-[9px] font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-500 font-bold">Crontab / Service</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Agar skrip otomatis aktif saat listrik menyala, tambahkan di <code class="bg-slate-100 px-1 py-0.5 rounded font-mono text-slate-700">crontab -e</code>:</p>
                        <code class="block font-mono text-[10.5px] bg-slate-900 text-amber-300 p-2.5 rounded-xl select-all overflow-x-auto leading-relaxed">
                            @reboot sleep 10 && python3 /home/pi/pindad_node_xxxx.py &
                        </code>
                    </div>

                </div>

            </div>
        </div>
        
        <!-- ITEM: NOTIFIKASI BOT TELEGRAM (PERINGATAN ANOMALI KEGAGALAN AC) -->
        <div class="bg-white rounded-[32px] border-2 border-sky-400/50 shadow-md overflow-hidden transition-all duration-300">
            <button @click="openItem = openItem === 'telegram' ? null : 'telegram'" 
                    type="button" 
                    class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer bg-gradient-to-r from-sky-50/50 to-white">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-sky-500 text-white flex items-center justify-center font-black text-2xl shrink-0 shadow-md shadow-sky-500/30">
                        🤖
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-black text-[#1D1616]">Notifikasi Bot Telegram (Pengingat & Alarm Darurat)</h3>
                            <span class="bg-sky-500 text-white text-[9px] font-black uppercase px-2 py-0.5 rounded-full">REAL-TIME</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-500">Kirim pesan darurat otomatis ke teknisi saat AC gagal hidup, kompresor mati, atau anomali arus</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'telegram' ? 'rotate-90 bg-sky-600 text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT: TELEGRAM CONFIGURATION & TESTING -->
            <div x-show="openItem === 'telegram'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-4 border-t border-sky-100 space-y-6 bg-slate-50/70"
                 x-data="{
                     testToken: '{{ $telegramSettings['bot_token'] ?? '' }}',
                     testChatId: '{{ $telegramSettings['chat_id'] ?? '' }}',
                     isTesting: false,
                     testMessage: '',
                     testSuccess: null,
                     async runTest() {
                         if (!this.testToken || !this.testChatId) {
                             this.testMessage = 'Harap isi Bot Token dan Chat ID terlebih dahulu!';
                             this.testSuccess = false;
                             return;
                         }
                         this.isTesting = true;
                         this.testMessage = '';
                         this.testSuccess = null;
                         try {
                             const res = await fetch('{{ route('settings.telegram.test') }}', {
                                 method: 'POST',
                                 headers: {
                                     'Content-Type': 'application/json',
                                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                 },
                                 body: JSON.stringify({
                                     telegram_bot_token: this.testToken,
                                     telegram_chat_id: this.testChatId
                                 })
                             });
                             const data = await res.json();
                             this.testSuccess = data.success;
                             this.testMessage = data.message;
                         } catch (e) {
                             this.testSuccess = false;
                             this.testMessage = 'Koneksi gagal: ' + e.message;
                         } finally {
                             this.isTesting = false;
                         }
                     }
                 }">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    <!-- FORM CONFIGURATION -->
                    <div class="lg:col-span-7 bg-white p-5 sm:p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h4 class="text-sm font-black text-[#1D1616] uppercase tracking-wider flex items-center gap-2">
                                <span>⚙️</span>
                                <span>Konfigurasi Akun Bot Telegram</span>
                            </h4>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full" :class="'{{ ($telegramSettings['is_enabled'] ?? true) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}'">
                                {{ ($telegramSettings['is_enabled'] ?? true) ? '🟢 Aktif' : '⚪ Nonaktif' }}
                            </span>
                        </div>

                        <form action="{{ route('settings.telegram') }}" method="POST" class="space-y-4 text-xs">
                            @csrf
                            
                            <div>
                                <label class="block font-black uppercase text-slate-700 tracking-wider mb-1.5">
                                    Telegram Bot Token (dari @BotFather) *
                                </label>
                                <input type="text" 
                                       name="telegram_bot_token" 
                                       x-model="testToken" 
                                       required 
                                       placeholder="Contoh: 7891234567:AAHdef123456xyz..." 
                                       class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 font-mono text-xs sm:text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                                <p class="text-[10.5px] text-slate-400 mt-1">Dibuat melalui bot resmi <b>@BotFather</b> di aplikasi Telegram.</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block font-black uppercase text-slate-700 tracking-wider mb-1.5">
                                        Chat ID / ID Grup Teknisi *
                                    </label>
                                    <input type="text" 
                                           name="telegram_chat_id" 
                                           x-model="testChatId" 
                                           required 
                                           placeholder="Contoh: 123456789 atau -100..." 
                                           class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 font-mono text-xs sm:text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                                    <p class="text-[10.5px] text-slate-400 mt-1">Gunakan <b>@userinfobot</b> atau <b>@getidsbot</b> untuk mengetahui ID.</p>
                                </div>

                                <div>
                                    <label class="block font-black uppercase text-slate-700 tracking-wider mb-1.5">
                                        Jeda Waktu Cooldown (Menit)
                                    </label>
                                    <input type="number" 
                                           name="telegram_cooldown_minutes" 
                                           value="{{ $telegramSettings['cooldown_minutes'] ?? 15 }}" 
                                           min="1" 
                                           max="120" 
                                           class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 font-mono text-xs sm:text-sm focus:ring-2 focus:ring-sky-500 outline-none">
                                    <p class="text-[10.5px] text-slate-400 mt-1">Mencegah spam notifikasi berulang untuk anomali yang sama.</p>
                                </div>
                            </div>

                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between">
                                <div>
                                    <span class="font-black text-slate-800 block text-xs">Aktifkan Notifikasi Darurat Otomatis</span>
                                    <span class="text-[10.5px] text-slate-500">Kirim pesan seketika saat AC gagal hidup / terdeteksi 0 Ampere</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="telegram_alert_enabled" value="1" {{ ($telegramSettings['is_enabled'] ?? true) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-500"></div>
                                </label>
                            </div>

                            <!-- ACTION BUTTONS -->
                            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-slate-100">
                                <button @click="runTest()" 
                                        type="button" 
                                        :disabled="isTesting"
                                        class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-1.5 cursor-pointer active:scale-95 disabled:opacity-50">
                                    <span x-show="!isTesting">🧪 Uji Coba Kirim Pesan</span>
                                    <span x-show="isTesting" class="animate-spin">⏳</span>
                                    <span x-show="isTesting">Mengirim...</span>
                                </button>

                                <button type="submit" 
                                        class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#1D1616] hover:bg-slate-900 text-white font-black text-xs uppercase tracking-wider shadow-md transition cursor-pointer active:scale-95">
                                    💾 Simpan Pengaturan
                                </button>
                            </div>

                            <!-- LIVE TEST FEEDBACK ALERT -->
                            <div x-show="testMessage" x-cloak class="p-3.5 rounded-2xl text-xs font-bold transition flex items-center gap-2"
                                 :class="testSuccess ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200'">
                                <span x-text="testSuccess ? '✅' : '❌'"></span>
                                <span x-text="testMessage"></span>
                            </div>
                        </form>
                    </div>

                    <!-- STEP BY STEP SETUP GUIDE & MESSAGE PREVIEW -->
                    <div class="lg:col-span-5 space-y-4">
                        
                        <!-- 3 Step Tutorial -->
                        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs space-y-3">
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                <span>📋</span>
                                <span>Panduan 3 Langkah Buat Bot Telegram</span>
                            </h4>

                            <div class="space-y-2.5 text-[11px] text-slate-600">
                                <div class="flex items-start gap-2">
                                    <span class="w-4 h-4 rounded-full bg-sky-500 text-white flex items-center justify-center text-[9px] font-black shrink-0 mt-0.5">1</span>
                                    <p>Buka Telegram, cari <b>@BotFather</b> lalu ketik <code>/newbot</code>. Beri nama bot dan salin <b>HTTP API Token</b> yang diberikan.</p>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-4 h-4 rounded-full bg-sky-500 text-white flex items-center justify-center text-[9px] font-black shrink-0 mt-0.5">2</span>
                                    <p>Buka bot baru Anda lalu tekan <b>Start</b>. Untuk grup, masukkan bot ke grup teknisi lalu cari Chat ID via <b>@userinfobot</b> / <b>@getidsbot</b>.</p>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="w-4 h-4 rounded-full bg-sky-500 text-white flex items-center justify-center text-[9px] font-black shrink-0 mt-0.5">3</span>
                                    <p>Paste Token & Chat ID ke form di samping, lalu klik tombol <b>🧪 Uji Coba Kirim Pesan</b>.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Message Box -->
                        <div class="bg-slate-900 text-slate-200 p-4 rounded-3xl border border-slate-800 space-y-2 shadow-md">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase tracking-widest text-sky-400 flex items-center gap-1.5">
                                    <span>📱</span>
                                    <span>Contoh Pesan Darurat di Telegram:</span>
                                </span>
                                <span class="text-[9px] font-mono bg-white/10 px-2 py-0.5 rounded text-amber-300">Live Preview</span>
                            </div>
                            <div class="bg-black/50 p-3 rounded-2xl border border-white/10 font-mono text-[10.5px] leading-relaxed text-slate-200 space-y-1">
                                <p class="text-rose-400 font-bold">🚨 [PERINGATAN KRITIS • PT PINDAD]</p>
                                <p class="text-amber-300 font-bold">⚠️ GANGGUAN: AC GAGAL MENYALA / MATI!</p>
                                <p class="pt-1 text-slate-300">📍 <b>Ruangan:</b> Server Telepon (Gedung Koperasi)</p>
                                <p class="text-slate-300">❄️ <b>Unit AC:</b> Unit 1 (Panasonic 1)</p>
                                <p class="text-rose-400">⚡ <b>Arus:</b> 0.0000 A (Kompresor Mati / 0 W)</p>
                                <p class="text-slate-400">⚙️ <b>Status:</b> DIPERINTAHKAN ON (WAKTU NYALA)</p>
                                <p class="text-sky-300 pt-1">👨‍🔧 <b>Tindakan:</b> Mohon teknisi segera cek MCB & unit AC di Server Telepon!</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <!-- ITEM 1: INFORMASI AKUN & PROFIL OPERATOR -->
        <div class="bg-white rounded-[32px] border border-[#8E1616]/20 shadow-xs overflow-hidden transition-all duration-300">
            <button @click="openItem = openItem === 'akun' ? null : 'akun'" 
                    type="button" 
                    class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-xl shrink-0">
                        👤
                    </div>
                    <div>
                        <h3 class="text-base font-black text-[#1D1616]">Profil & Akun Operator</h3>
                        <p class="text-xs font-semibold text-slate-500">Informasi pengguna aktif dan hak akses kontrol dashboard</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'akun' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT -->
            <div x-show="openItem === 'akun'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-4 bg-slate-50/60">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs pt-2">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Nama Lengkap</span>
                        <span class="font-black text-[#1D1616] text-sm block mt-0.5">{{ $user->name ?? 'Dicky Akbar Syahputra' }}</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Email Operator</span>
                        <span class="font-mono font-bold text-slate-700 text-xs block mt-0.5">{{ $user->email ?? 'dicky.akbar@pindad.com' }}</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Divisi</span>
                        <span class="font-bold text-[#1D1616] block mt-0.5">Mutu & TI / Fasilitas Gedung</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Peran Sistem</span>
                        <span class="font-bold text-emerald-600 block mt-0.5">● Super Administrator</span>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-slate-200 text-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <button @click="modalPassword = true" 
                                type="button"
                                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition cursor-pointer">
                            🔒 Ubah Kata Sandi
                        </button>
                    </div>

                    <!-- FORM LOGOUT -->
                    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sesi operator?')">
                        @csrf
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-[#D84040] hover:bg-[#8E1616] text-white text-xs font-black uppercase tracking-wider shadow-md transition cursor-pointer flex items-center space-x-2">
                            <span>🚪</span>
                            <span>Keluar / Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ITEM 2: INFORMASI SISTEM & SPESIFIKASI WEB ENGINE -->
        <div class="bg-white rounded-[32px] border border-[#8E1616]/20 shadow-xs overflow-hidden transition-all duration-300">
            <button @click="openItem = openItem === 'sistem' ? null : 'sistem'" 
                    type="button" 
                    class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-xl shrink-0">
                        🖥️
                    </div>
                    <div>
                        <h3 class="text-base font-black text-[#1D1616]">Informasi Web & Server Platform</h3>
                        <p class="text-xs font-semibold text-slate-500">Spesifikasi software engine, database MongoDB, dan broker MQTT</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'sistem' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT -->
            <div x-show="openItem === 'sistem'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-4 bg-slate-50/60">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-xs pt-2">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Versi Dashboard</span>
                        <span class="font-black text-[#1D1616] text-sm block mt-0.5">v2.5.0 (Blynk IoT Edition)</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Database Mesin</span>
                        <span class="font-bold text-emerald-600 block mt-0.5">🟢 MongoDB Atlas / Local</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">MQTT Broker</span>
                        <span class="font-bold text-emerald-600 block mt-0.5">🟢 Mosquitto TCP 1883</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Cloud IoT Protocol</span>
                        <span class="font-bold text-[#1D1616] block mt-0.5">Blynk REST & MQTT Bridge</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Firewall Integration</span>
                        <span class="font-bold text-[#1D1616] block mt-0.5">Sophos Captive Portal Auto-Auth</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Instansi Pemilik</span>
                        <span class="font-bold text-[#1D1616] block mt-0.5">PT PINDAD (Persero) Bandung</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ITEM 3: SPESIFIKASI PERANGKAT KERAS IOT -->
        <div class="bg-white rounded-[32px] border border-[#8E1616]/20 shadow-xs overflow-hidden transition-all duration-300">
            <button @click="openItem = openItem === 'hardware' ? null : 'hardware'" 
                    type="button" 
                    class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-xl shrink-0">
                        📟
                    </div>
                    <div>
                        <h3 class="text-base font-black text-[#1D1616]">Spesifikasi Hardware & Pinout</h3>
                        <p class="text-xs font-semibold text-slate-500">Daftar komponen sensor arus ACS712, RTC DS3231, dan modul relay</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'hardware' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT -->
            <div x-show="openItem === 'hardware'" x-cloak x-transition class="px-5 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-4 bg-slate-50/60">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-2">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Kontroler Utama</span>
                        <span class="font-black text-[#1D1616] text-sm block mt-0.5">Raspberry Pi 3 Model B+</span>
                        <p class="text-[11px] text-slate-500 mt-1">Quad Core 1.4GHz Broadcom BCM2837B0, 1GB LPDDR2 SDRAM.</p>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Sensor Arus Listrik</span>
                        <span class="font-black text-[#1D1616] text-sm block mt-0.5">Allegro ACS712 30A Hall-Effect</span>
                        <p class="text-[11px] text-slate-500 mt-1">Sensitivitas 66 mV/A, pembacaan ADC ADS1115 I2C 16-Bit presisi tinggi.</p>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Hardware Clock (RTC)</span>
                        <span class="font-black text-[#1D1616] text-sm block mt-0.5">Maxim DS3231 High-Precision RTC</span>
                        <p class="text-[11px] text-slate-500 mt-1">Baterai CR2032 terintegrasi untuk menjamin akurasi jadwal saat offline.</p>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E1616] block">Modul Saklar Relai</span>
                        <span class="font-black text-[#1D1616] text-sm block mt-0.5">Dual-Channel 5V Relay Optocoupler</span>
                        <p class="text-[11px] text-slate-500 mt-1">GPIO 17 (Relay AC 1 / Lampu Bawah), GPIO 27 (Relay AC 2 / Lampu Atas).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ================= MODAL UBAH PASSWORD ================= -->
    <div x-show="modalPassword" 
         x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-3 sm:p-6 pb-28 sm:pb-6 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div @click.away="modalPassword = false" 
             class="bg-white rounded-[28px] sm:rounded-[36px] p-5 sm:p-7 max-w-md w-full shadow-2xl border border-slate-200 space-y-3.5 sm:space-y-4 relative max-h-[82vh] sm:max-h-[88vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 sm:pb-3.5">
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-[20px] bg-[#8E1616]/10 text-[#8E1616] flex items-center justify-center font-black text-lg sm:text-xl shrink-0">
                        🔒
                    </div>
                    <div>
                        <h4 class="text-base sm:text-lg font-black text-[#1D1616]">Ubah Kata Sandi Akun</h4>
                        <p class="text-[11px] sm:text-xs text-slate-500">Perbarui kata sandi login operator</p>
                    </div>
                </div>
                <button @click="modalPassword = false" class="text-slate-400 hover:text-[#8E1616] text-2xl font-bold cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-3 sm:space-y-3.5">
                @csrf
                <div>
                    <label class="block text-[11px] sm:text-xs font-black uppercase text-slate-700 tracking-wider mb-1 sm:mb-1.5">Kata Sandi Saat Ini *</label>
                    <input type="password" name="current_password" required class="w-full px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div>
                    <label class="block text-[11px] sm:text-xs font-black uppercase text-slate-700 tracking-wider mb-1 sm:mb-1.5">Kata Sandi Baru *</label>
                    <input type="password" name="new_password" required minlength="6" class="w-full px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div>
                    <label class="block text-[11px] sm:text-xs font-black uppercase text-slate-700 tracking-wider mb-1 sm:mb-1.5">Konfirmasi Kata Sandi Baru *</label>
                    <input type="password" name="new_password_confirmation" required minlength="6" class="w-full px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-[#8E1616] outline-none">
                </div>

                <div class="pt-2.5 sm:pt-3 flex items-center justify-end gap-2.5 sm:gap-3 border-t border-slate-100">
                    <button @click="modalPassword = false" type="button" class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 sm:px-6 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl bg-gradient-to-r from-[#8E1616] to-[#1D1616] text-white font-bold text-xs uppercase shadow-md hover:opacity-95 cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
