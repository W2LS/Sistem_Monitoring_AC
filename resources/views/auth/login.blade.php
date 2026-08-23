<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Operator — Sistem Monitoring AC IoT PT PINDAD</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <style>
        :root {
            --pindad-dark: #1D1616;
            --pindad-maroon: #8E1616;
            --pindad-red: #D84040;
            --pindad-light: #EEEEEE;
        }

        html, body {
            height: 100vh;
            max-height: 100vh;
            overflow: hidden;
            background: linear-gradient(135deg, #1D1616 0%, #8E1616 50%, #D84040 100%);
            color: #ffffff;
            font-family: 'Inter', sans-serif;
        }

        .login-wrapper {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-login {
            background: #ffffff;
            color: #1D1616;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: none;
        }

        .btn-pindad {
            background: linear-gradient(90deg, #1D1616 0%, #8E1616 50%, #D84040 100%);
            color: #ffffff;
            border: none;
            font-weight: 800;
            letter-spacing: 0.5px;
            padding: 12px 20px;
            border-radius: 14px;
            transition: all 0.3s ease;
        }

        .btn-pindad:hover {
            opacity: 0.92;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(216, 64, 64, 0.4);
        }

        .badge-pindad {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .form-control:focus {
            border-color: #8E1616;
            box-shadow: 0 0 0 0.2rem rgba(142, 22, 22, 0.25);
        }

        .fs-8 {
            font-size: 11px;
        }
    </style>
</head>
<body class="h-100 overflow-hidden">

<!-- SINGLE SCREEN NON-SCROLLABLE LOGIN WRAPPER (100VH EXACT MATCH) -->
<div class="login-wrapper container-fluid px-3 px-md-5">
    <div class="row w-100 justify-content-center align-items-center g-4 max-w-6xl">
        
        <!-- LEFT SIDE: BRAND HERO -->
        <div class="col-12 col-md-6 text-white px-md-4">
            <div class="d-inline-flex items-center gap-2 badge-pindad mb-3">
                🛡️ PT PINDAD (PERSERO)
            </div>

            <h1 class="display-5 fw-black text-white mb-3 tracking-tight">
                Sistem Kontrol & Monitoring AC IoT
            </h1>

            <p class="lead text-white-50 fs-6 fw-normal mb-4">
                Platform pemantauan telemetri arus listrik dan saklar kontrol pendingin udara (AC) otomatis berbasis ESP32 & MQTT di Ruang Server 1.
            </p>

            <div class="text-white-50">
              <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
              </svg>
            </div>
        </div>

        <!-- RIGHT SIDE: FORM CARD -->
        <div class="col-12 col-md-6 col-lg-5 col-xl-4">
            <div class="card card-login p-3 p-sm-4">
                <div class="card-body p-2">
                    
                    <div class="mb-3">
                        <h2 class="h4 fw-black text-dark mb-1">Login Operator</h2>
                        <p class="fs-7 text-secondary m-0">Masukkan NIP & Password untuk masuk</p>
                    </div>

                    <!-- ERROR / SUCCESS NOTIFICATIONS -->
                    @if($errors->has('login_error'))
                        <div class="alert alert-danger p-2 fs-7 font-semibold rounded-3 mb-3 border-0" role="alert">
                            ⚠️ {{ $errors->first('login_error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success p-2 fs-7 font-semibold rounded-3 mb-3 border-0" role="alert">
                            ✓ {{ session('success') }}
                        </div>
                    @endif

                    <!-- FORM -->
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nip" class="form-label fs-7 font-semibold text-secondary mb-1">NIP / Username Operator</label>
                            <input type="text" class="form-control py-2 fs-7 rounded-3" name="nip" id="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP Operator" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fs-7 font-semibold text-secondary mb-1">Kata Sandi (Password)</label>
                            <input type="password" class="form-control py-2 fs-7 rounded-3" name="password" id="password" value="" placeholder="Masukkan Kata Sandi" required>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember" checked>
                            <label class="form-check-label text-secondary fs-7" for="remember">
                                Ingat Sesi Operator
                            </label>
                        </div>

                        <div class="d-grid mb-2">
                            <button class="btn btn-pindad fs-7" type="submit">
                                Masuk ke Dashboard ➔
                            </button>
                        </div>

                    </form>

                    <div class="text-center mt-3">
                        <p class="m-0 text-secondary fs-8">
                            © 2026 PT PINDAD (Persero)
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
