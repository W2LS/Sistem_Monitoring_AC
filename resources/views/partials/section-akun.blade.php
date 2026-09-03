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

        <!-- ITEM 0: TUTORIAL & PANDUAN LENGKAP PENGGUNAAN PLATFORM (SOP END-TO-END) -->
        <div class="bg-white rounded-[32px] border border-[#8E1616]/20 shadow-xs overflow-hidden transition-all duration-300">
            <button @click="openItem = openItem === 'tutorial' ? null : 'tutorial'" 
                    type="button" 
                    class="w-full p-4 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-lg sm:text-xl shrink-0">
                        📖
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm sm:text-base font-black text-[#1D1616] leading-tight truncate">Panduan Setup Node IoT</h3>
                            <span class="bg-emerald-600 text-white text-[9px] font-black uppercase px-2 py-0.5 rounded-full whitespace-nowrap shrink-0 tracking-wider">SOP</span>
                        </div>
                        <p class="text-[11px] sm:text-xs font-semibold text-slate-500 mt-0.5 truncate sm:whitespace-normal">SOP template, pendaftaran node, dan skrip Raspberry Pi</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'tutorial' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT: COMPLETE STEP-BY-STEP SOP -->
            <div x-show="openItem === 'tutorial'" x-cloak x-transition class="px-4 sm:px-6 pb-6 pt-4 border-t border-[#8E1616]/10 space-y-4 bg-slate-50/70"
                 x-data="{
                     copyAccSuccess: false,
                     sampleCommand: `(crontab -l 2>/dev/null | grep -v 'pindad_node'; echo &quot;@reboot sleep 10 && cd /home/alex && python3 -u /home/alex/pindad_node_xxxx.py > /home/alex/node.log 2>&1 &&quot;) | crontab - && nohup python3 -u /home/alex/pindad_node_xxxx.py > /home/alex/node.log 2>&1 &`,
                     copyAccCmd() {
                         navigator.clipboard.writeText(this.sampleCommand);
                         this.copyAccSuccess = true;
                         setTimeout(() => { this.copyAccSuccess = false; }, 3000);
                     }
                 }">
                
                <!-- STEP 1: PILIH / BUAT TEMPLATE DEVZONE -->
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-xs space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-[#1D1616] text-white flex items-center justify-center text-[10px] font-black shrink-0">1</span>
                            <span>PILIH / BUAT TEMPLATE HARDWARE (MODUL DEVZONE)</span>
                        </span>
                        <span class="text-[9px] font-black uppercase bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md font-sans shrink-0 whitespace-nowrap">Modul 2</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Buka menu <b>DevZone</b> untuk memilih blueprint template yang sesuai (misal: <i>Module Relay 1, 2, 4, atau 8 Channel</i>) atau buat template kustom baru dengan susunan <b>Virtual Pin</b> relay dan sensor yang diinginkan.
                    </p>
                </div>

                <!-- STEP 2: DAFTARKAN RUANGAN BARU DI HOME -->
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-xs space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-[#1D1616] text-white flex items-center justify-center text-[10px] font-black shrink-0">2</span>
                            <span>DAFTARKAN RUANGAN / PERANGKAT (MODUL HOME)</span>
                        </span>
                        <span class="text-[9px] font-black uppercase bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md font-sans shrink-0 whitespace-nowrap">Modul 1</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Buka menu <b>Home</b> &rarr; klik <b>`+ Tambah Perangkat Baru`</b>. Masukkan Nama Ruangan (misal: <i>Server Telepon</i>), IP Address Raspberry Pi, dan pilih template yang telah ditentukan di langkah 1.
                    </p>
                </div>

                <!-- STEP 3: UNDUH FILE SKRIP -->
                <div class="bg-white rounded-2xl p-4 sm:p-5 border-2 border-emerald-400/80 shadow-xs space-y-2 bg-emerald-50/20">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] font-black shrink-0">3</span>
                            <span>UNDUH FILE SKRIP PYTHON (.PY)</span>
                        </span>
                        <span class="text-[9px] font-black uppercase bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-md font-sans shrink-0 whitespace-nowrap">Auto-Config</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Buka detail kartu perangkat di halaman <b>Home</b>, lalu klik tombol <b>`📥 Unduh Skrip (.py)`</b>. Simpan file skrip (contoh: <code class="font-bold text-[#1D1616] bg-white px-1.5 py-0.5 rounded border border-slate-200 font-mono text-[11px]">pindad_node_ruang_server.py</code>) ke folder <code class="font-mono text-slate-800 font-bold bg-white px-1.5 py-0.5 rounded text-[11px]">/home/alex/</code> (atau <code class="font-mono text-slate-800 font-bold bg-white px-1.5 py-0.5 rounded text-[11px]">/home/pi/</code>) di Raspberry Pi.
                    </p>
                </div>

                <!-- STEP 4: PERINTAH 1-KLIK AUTO-START ON BOOT -->
                <div class="bg-slate-900 rounded-2xl sm:rounded-3xl p-4 sm:p-5 border border-slate-800 space-y-3 text-white shadow-lg">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm font-black uppercase tracking-wider text-amber-400 flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-amber-400 text-slate-900 flex items-center justify-center text-[10px] font-black shrink-0">4</span>
                            <span>PERINTAH 1-KLIK AUTO-START ON BOOT & JALANKAN</span>
                        </span>
                        <button @click="copyAccCmd()" 
                                type="button" 
                                class="px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition flex items-center gap-1.5 cursor-pointer active:scale-95 shrink-0 shadow-xs whitespace-nowrap"
                                :class="copyAccSuccess ? 'bg-emerald-500 text-white' : 'bg-white/15 hover:bg-white/25 text-amber-300 border border-amber-400/30'">
                            <span x-text="copyAccSuccess ? '✓' : '📋'"></span>
                            <span x-text="copyAccSuccess ? 'Tersalin!' : 'Salin Perintah'"></span>
                        </button>
                    </div>
                    
                    <div class="bg-black/60 rounded-xl p-3 sm:p-3.5 border border-white/10 font-mono text-xs text-emerald-400 break-all select-all leading-relaxed" x-text="sampleCommand"></div>

                    <p class="text-xs text-slate-300 leading-relaxed">
                        💡 <strong>Cara Pakai:</strong> Buka SSH terminal Raspberry Pi, <em>paste</em> perintah di atas lalu tekan <strong>Enter</strong>. Skrip akan langsung aktif seketika di background & otomatis berjalan setiap kali listrik menyala (*auto-start saat boot*).
                    </p>
                </div>

                <!-- STEP 5: CEK LOG BERJALAN -->
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-xs space-y-2">
                    <span class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-slate-500 text-white flex items-center justify-center text-[10px] font-black shrink-0">5</span>
                        <span>PERIKSA LOG BERJALAN (OPSIONAL)</span>
                    </span>
                    <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <code class="text-xs sm:text-sm font-mono font-bold text-slate-800">tail -f /home/alex/node.log</code>
                        <span class="text-[11px] font-semibold text-slate-400 shrink-0 whitespace-nowrap">Tekan Ctrl+C keluar</span>
                    </div>
                    <p class="text-[11px] text-slate-500">
                        Gunakan perintah di atas di terminal untuk memantau pengiriman data telemetri suhu, arus ampere, dan status relay secara live.
                    </p>
                </div>

                <!-- HELPER: INSTALL DEPENDENCY (1X SETUP RPI BARU) -->
                <div class="bg-white p-4 sm:p-4.5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="text-base shrink-0">📦</span>
                            <h4 class="font-black text-xs uppercase tracking-wider text-[#1D1616] truncate">Install Library Python (Khusus Raspberry Pi Baru / 1x Setup)</h4>
                        </div>
                        <span class="text-[9px] font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-500 font-bold shrink-0 whitespace-nowrap">Python Pip</span>
                    </div>
                    <p class="text-[11.5px] text-slate-500">Jalankan sekali saja jika Raspberry Pi baru belum terinstall library MQTT & sensor:</p>
                    <code class="block font-mono text-[11px] bg-slate-900 text-slate-100 p-2.5 rounded-xl select-all overflow-x-auto leading-relaxed">
                        pip3 install paho-mqtt adafruit-circuitpython-ads1x15 adafruit-circuitpython-ds3231 RPi.GPIO
                    </code>
                </div>

            </div>
        </div>
        
        <!-- ITEM: NOTIFIKASI BOT TELEGRAM (PERINGATAN ANOMALI KEGAGALAN AC) -->
        <div class="bg-white rounded-[32px] border border-[#8E1616]/20 shadow-xs overflow-hidden transition-all duration-300">
            <button @click="openItem = openItem === 'telegram' ? null : 'telegram'" 
                    type="button" 
                    class="w-full p-4 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-xl sm:text-2xl shrink-0">
                        🤖
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm sm:text-base font-black text-[#1D1616] leading-tight truncate">Notifikasi Bot Telegram</h3>
                            <span class="bg-sky-600 text-white text-[9px] font-black uppercase px-2 py-0.5 rounded-full whitespace-nowrap shrink-0 tracking-wider">ALARM</span>
                        </div>
                        <p class="text-[11px] sm:text-xs font-semibold text-slate-500 mt-0.5 truncate sm:whitespace-normal">Alarm darurat otomatis teknisi saat anomali atau kegagalan AC</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'telegram' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
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
                    class="w-full p-4 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-lg sm:text-xl shrink-0">
                        👤
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm sm:text-base font-black text-[#1D1616] leading-snug">Profil & Akun Operator</h3>
                        <p class="text-[11px] sm:text-xs font-semibold text-slate-500 mt-0.5 truncate sm:whitespace-normal">Informasi pengguna aktif dan hak akses kontrol dashboard</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'akun' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT -->
            <div x-show="openItem === 'akun'" x-cloak x-transition class="px-4 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-4 bg-slate-50/60">
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
                    class="w-full p-4 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-lg sm:text-xl shrink-0">
                        🖥️
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm sm:text-base font-black text-[#1D1616] leading-snug">Informasi Web & Server Platform</h3>
                        <p class="text-[11px] sm:text-xs font-semibold text-slate-500 mt-0.5 truncate sm:whitespace-normal">Spesifikasi software engine, database MongoDB, dan broker MQTT</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#EEEEEE] flex items-center justify-center text-slate-600 font-bold text-sm transition-transform duration-300 shrink-0 ml-2"
                     :class="openItem === 'sistem' ? 'rotate-90 bg-[#8E1616] text-white' : ''">
                    ➔
                </div>
            </button>

            <!-- ACCORDION CONTENT -->
            <div x-show="openItem === 'sistem'" x-cloak x-transition class="px-4 sm:px-6 pb-6 pt-2 border-t border-[#8E1616]/10 space-y-4 bg-slate-50/60">
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
                    class="w-full p-4 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-rose-100 text-[#8E1616] flex items-center justify-center font-black text-lg sm:text-xl shrink-0">
                        📟
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm sm:text-base font-black text-[#1D1616] leading-snug">Spesifikasi Hardware & Pinout</h3>
                        <p class="text-[11px] sm:text-xs font-semibold text-slate-500 mt-0.5 truncate sm:whitespace-normal">Daftar komponen sensor arus ACS712, RTC DS3231, dan modul relay</p>
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
