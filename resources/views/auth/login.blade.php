<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Operator — Sistem Monitoring AC IoT PT PINDAD</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            background-color: #1D1616;
            color: #EEEEEE;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden bg-[#1D1616]">

    <!-- Decorative Gradient Background Glows -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#8E1616]/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-[#D84040]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#8E1616]/10 rounded-full blur-[120px] pointer-events-none"></div>

    <!-- MAIN LOGIN CARD CONTAINER -->
    <div class="w-full max-w-md relative z-10 space-y-6">

        <!-- LOGO & BRANDING HEADER -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-[28px] bg-gradient-to-tr from-[#1D1616] via-[#8E1616] to-[#D84040] text-white font-black text-3xl shadow-[0_15px_35px_rgba(216,64,64,0.3)] border-2 border-white/20 mb-2">
                🛡️
            </div>
            <span class="inline-block px-3 py-1 bg-[#8E1616]/30 border border-[#8E1616]/50 rounded-full text-[10px] font-extrabold uppercase tracking-widest text-[#D84040]">
                PT PINDAD (PERSERO) • INTERNAL IOT GATEWAY
            </span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                Sistem Kontrol AC IoT
            </h1>
            <p class="text-xs font-semibold text-[#EEEEEE]/70">
                Ruang Server 1 — Divisi Sistem Informasi & Fasilitas
            </p>
        </div>

        <!-- FORM CARD -->
        <div class="bg-white/95 backdrop-blur-xl rounded-[40px] p-8 text-[#1D1616] shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6)] border border-[#8E1616]/30 space-y-6">
            
            <div class="border-b border-[#8E1616]/15 pb-4 text-center">
                <h2 class="text-xl font-black text-[#1D1616]">Login Operator</h2>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">Masukkan NIP & Kata Sandi untuk mengakses kontrol dashboard</p>
            </div>

            <!-- ERROR NOTIFICATION -->
            @if($errors->has('login_error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 text-xs font-bold flex items-start space-x-3">
                    <span class="text-base shrink-0">⚠️</span>
                    <div>
                        <span class="block font-black">Akses Ditolak</span>
                        <span>{{ $errors->first('login_error') }}</span>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 text-xs font-bold flex items-center space-x-3">
                    <span class="text-base shrink-0">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- LOGIN FORM -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- FIELD 1: NIP / USERNAME -->
                <div class="space-y-1.5">
                    <label for="nip" class="text-[11px] font-extrabold uppercase tracking-wider text-[#8E1616] block">
                        NIP / Username Operator
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm">
                            👤
                        </span>
                        <input type="text" 
                               id="nip" 
                               name="nip" 
                               value="{{ old('nip', 'PINDAD-IOT-2026') }}" 
                               required 
                               placeholder="Contoh: PINDAD-IOT-2026"
                               class="w-full pl-11 pr-4 py-3.5 bg-[#EEEEEE]/80 border border-[#8E1616]/20 rounded-2xl text-xs font-bold text-[#1D1616] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8E1616] focus:bg-white transition">
                    </div>
                    @error('nip')
                        <span class="text-[10px] font-bold text-rose-600 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- FIELD 2: PASSWORD -->
                <div class="space-y-1.5">
                    <label for="password" class="text-[11px] font-extrabold uppercase tracking-wider text-[#8E1616] block">
                        Kata Sandi (Password)
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm">
                            🔒
                        </span>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               value="pindad123"
                               required 
                               placeholder="Masukkan kata sandi"
                               class="w-full pl-11 pr-4 py-3.5 bg-[#EEEEEE]/80 border border-[#8E1616]/20 rounded-2xl text-xs font-bold text-[#1D1616] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8E1616] focus:bg-white transition">
                    </div>
                    @error('password')
                        <span class="text-[10px] font-bold text-rose-600 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- QUICK DEMO HELPER BOX -->
                <div class="bg-[#EEEEEE]/90 p-3.5 rounded-2xl border border-[#8E1616]/15 text-[11px] font-medium text-slate-600 space-y-1">
                    <div class="flex items-center space-x-1.5 text-[#8E1616] font-bold">
                        <span>💡</span>
                        <span class="uppercase tracking-wider text-[10px]">Kredensial Default Operator</span>
                    </div>
                    <div class="font-mono text-[10px] text-[#1D1616] font-bold space-y-0.5 pt-0.5">
                        <p>NIP: <span class="text-[#8E1616]">PINDAD-IOT-2026</span></p>
                        <p>Pass: <span class="text-[#8E1616]">pindad123</span></p>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" 
                        class="w-full py-4 rounded-2xl bg-gradient-to-r from-[#1D1616] via-[#8E1616] to-[#D84040] hover:opacity-95 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-[#8E1616]/30 transition cursor-pointer flex items-center justify-center space-x-2">
                    <span>Masuk ke Dashboard</span>
                    <span class="text-sm">➔</span>
                </button>
            </form>

        </div>

        <!-- FOOTER COPY -->
        <p class="text-center text-[11px] font-medium text-slate-500">
            © 2026 PT PINDAD (Persero) • Smart IoT Server Room AC Control
        </p>

    </div>

</body>
</html>
