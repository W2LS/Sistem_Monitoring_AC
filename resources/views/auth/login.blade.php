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
    
    <!-- Bootstrap 5.3 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <style>
        :root {
            --pindad-dark: #1D1616;
            --pindad-maroon: #8E1616;
            --pindad-red: #D84040;
            --pindad-light: #EEEEEE;
        }

        body {
            background-color: var(--pindad-dark);
            color: var(--pindad-light);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .login-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .card-login {
            background: #ffffff;
            color: #1D1616;
            border-radius: 28px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(142, 22, 22, 0.2);
        }

        .btn-pindad {
            background: linear-gradient(90deg, #1D1616 0%, #8E1616 50%, #D84040 100%);
            color: #ffffff;
            border: none;
            font-weight: 800;
            letter-spacing: 0.5px;
            padding: 14px 24px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .btn-pindad:hover {
            opacity: 0.92;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(216, 64, 64, 0.35);
        }

        .badge-pindad {
            background-color: rgba(142, 22, 22, 0.2);
            color: #D84040;
            border: 1px solid rgba(216, 64, 64, 0.4);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #8E1616;
            font-weight: 700;
        }

        .form-control:focus {
            border-color: #8E1616;
            box-shadow: 0 0 0 0.25rem rgba(142, 22, 22, 0.25);
        }

        .feature-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 12px 16px;
        }
    </style>
</head>
<body>

<!-- Login Section Layout adapted from Registration 9 (Bootstrap Brain) -->
<section class="login-section py-4 py-md-5 py-xl-8">
  <div class="container">
    <div class="row gy-4 align-items-center justify-content-center">
      
      <!-- HERO BRANDING COLUMN (LEFT SIDE) -->
      <div class="col-12 col-md-6 col-xl-7">
        <div class="d-flex justify-content-center text-bg-dark bg-transparent">
          <div class="col-12 col-xl-10 space-y-4">
            
            <div class="d-flex items-center gap-3 mb-3">
              <div class="badge-pindad">
                🛡️ PT PINDAD (PERSERO) • INTERNAL IOT GATEWAY
              </div>
            </div>

            <h1 class="display-5 fw-black mb-3 text-white">
              Sistem Kontrol & Monitoring AC IoT
            </h1>
            
            <p class="lead mb-4 text-white-50 fs-6 fw-semibold">
              Platform pemantauan telemetri arus listrik dan saklar kontrol pendingin udara (AC) otomatis berbasis ESP32 & MQTT di Ruang Server 1.
            </p>

            <hr class="border-secondary opacity-25 mb-4">

            <!-- IoT HIGHLIGHT FEATURES -->
            <div class="row g-3 mb-4">
              <div class="col-6">
                <div class="feature-box d-flex align-items-center gap-2">
                  <span class="fs-4">📡</span>
                  <div>
                    <span class="d-block fw-bold fs-7 text-white">Broker MQTT</span>
                    <span class="d-block text-white-50 text-xs">EMQX Real-Time</span>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="feature-box d-flex align-items-center gap-2">
                  <span class="fs-4">❄️</span>
                  <div>
                    <span class="d-block fw-bold fs-7 text-white">Dual AC Control</span>
                    <span class="d-block text-white-50 text-xs">Panasonic 1 & 2</span>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="feature-box d-flex align-items-center gap-2">
                  <span class="fs-4">⚡</span>
                  <div>
                    <span class="d-block fw-bold fs-7 text-white">Sensor ACS712</span>
                    <span class="d-block text-white-50 text-xs">Arus Ampere Precision</span>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="feature-box d-flex align-items-center gap-2">
                  <span class="fs-4">🕒</span>
                  <div>
                    <span class="d-block fw-bold fs-7 text-white">RTC DS3231</span>
                    <span class="d-block text-white-50 text-xs">Rotasi Shift 12 Jam</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="text-white-50 fs-7">
              <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#D84040" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
              </svg>
            </div>

          </div>
        </div>
      </div>

      <!-- LOGIN FORM CARD COLUMN (RIGHT SIDE) -->
      <div class="col-12 col-md-6 col-xl-5">
        <div class="card card-login border-0">
          <div class="card-body p-4 p-md-5">
            
            <div class="row">
              <div class="col-12">
                <div class="mb-4">
                  <h2 class="h3 fw-black text-dark mb-1">Login Operator</h2>
                  <p class="fs-7 text-secondary m-0">Masukkan NIP & Password untuk mengakses kontrol dashboard</p>
                </div>
              </div>
            </div>

            <!-- ERROR & SUCCESS MESSAGES -->
            @if($errors->has('login_error'))
                <div class="alert alert-danger rounded-4 text-xs font-bold mb-4 border-0 shadow-sm" role="alert">
                    ⚠️ <strong>Akses Ditolak:</strong> {{ $errors->first('login_error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success rounded-4 text-xs font-bold mb-4 border-0 shadow-sm" role="alert">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <!-- LOGIN FORM -->
            <form action="{{ route('login.post') }}" method="POST">
              @csrf

              <div class="row gy-3">
                
                <!-- FIELD 1: NIP / USERNAME -->
                <div class="col-12">
                  <div class="form-floating mb-1">
                    <input type="text" 
                           class="form-control rounded-3" 
                           name="nip" 
                           id="nip" 
                           value="{{ old('nip', 'PINDAD-IOT-2026') }}" 
                           placeholder="PINDAD-IOT-2026" 
                           required>
                    <label for="nip" class="form-label">NIP / Username Operator</label>
                  </div>
                  @error('nip')
                      <span class="text-danger fs-8 font-semibold">{{ $message }}</span>
                  @enderror
                </div>

                <!-- FIELD 2: PASSWORD -->
                <div class="col-12">
                  <div class="form-floating mb-1">
                    <input type="password" 
                           class="form-control rounded-3" 
                           name="password" 
                           id="password" 
                           value="pindad123" 
                           placeholder="Password" 
                           required>
                    <label for="password" class="form-label">Kata Sandi (Password)</label>
                  </div>
                  @error('password')
                      <span class="text-danger fs-8 font-semibold">{{ $message }}</span>
                  @enderror
                </div>

                <!-- REMEMBER ME CHECKBOX -->
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember" checked>
                    <label class="form-check-label text-secondary fs-7" for="remember">
                      Ingat Sesi Operator di Perangkat Ini
                    </label>
                  </div>
                </div>

                <!-- DEMO CREDENTIAL HELPER BOX -->
                <div class="col-12">
                  <div class="bg-light p-3 rounded-4 border text-xs">
                    <span class="fw-bold text-danger d-block mb-1">💡 Kredensial Default Operator:</span>
                    <div class="font-monospace text-dark fw-bold">
                      NIP: <span class="text-danger">PINDAD-IOT-2026</span> | Pass: <span class="text-danger">pindad123</span>
                    </div>
                  </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="col-12 mt-4">
                  <div class="d-grid">
                    <button class="btn btn-pindad btn-lg fs-6" type="submit">
                      Masuk ke Dashboard ➔
                    </button>
                  </div>
                </div>

              </div>
            </form>

            <div class="row">
              <div class="col-12">
                <div class="text-center mt-4">
                  <p class="m-0 text-secondary fs-7">
                    © 2026 PT PINDAD (Persero) • Smart IoT Server Room Control
                  </p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Bootstrap 5.3 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
