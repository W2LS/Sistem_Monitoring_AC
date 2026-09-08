@echo off
chcp 65001 >nul
title SIKOMAT AC - Server Laravel LAN Launcher (PT PINDAD)
color 0A

:: Pastikan direktori kerja selalu berada di folder lokasi script ini
cd /d "%~dp0"

cls
echo ===============================================================================
echo     SISTEM KONTROL DAN MONITORING SUHU AC OTOMATIS (SIKOMAT)
echo     PT PINDAD (PERSERO) - DIVISI MUTU ^& TI
echo ===============================================================================
echo.

:: 1. Cek ketersediaan PHP di komputer/device ini
where php >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] PHP tidak terdeteksi di sistem PATH komputer ini!
    echo Silakan install PHP terlebih dahulu atau tambahkan direktori PHP ke Environment Variables.
    echo.
    pause
    exit /b 1
)

:: 2. Pastikan file database SQLite ada (untuk perangkat baru/fresh install)
if not exist "database\database.sqlite" (
    if exist "database" (
        type nul > "database\database.sqlite"
    )
)

:: 3. Pastikan file konfigurasi .env ada
if not exist ".env" (
    if exist ".env.example" (
        echo [*] Menyiapkan file konfigurasi .env dari .env.example...
        copy ".env.example" ".env" >nul
        php artisan key:generate
    )
)

echo [1/3] Mendeteksi Alamat IP Komputer pada Jaringan Lokal (LAN / Wi-Fi)...
echo -------------------------------------------------------------------------------
set "PRIMARY_IP="
for /f "usebackq tokens=1,2 delims=#" %%a in (`powershell -NoProfile -Command "$ips = [System.Net.Dns]::GetHostAddresses([System.Net.Dns]::GetHostName()) | Where-Object AddressFamily -eq 'InterNetwork' | ForEach-Object IPAddressToString; $p = $ips | Where-Object { $_ -like '192.168.*' -or $_ -like '10.*' } | Select-Object -First 1; if (-not $p) { $p = $ips | Where-Object { $_ -notlike '127.*' } | Select-Object -First 1 }; if (-not $p) { $p = $ips | Select-Object -First 1 }; foreach ($ip in $ips) { if ($ip -eq $p) { Write-Output ('PRIMARY#' + $ip) } else { Write-Output ('OTHER#' + $ip) } }" 2^>nul`) do (
    if "%%a"=="PRIMARY" (
        set "PRIMARY_IP=%%b"
        echo   [+] IP LAN Terdeteksi : %%b ^(Utama / Wi-Fi / LAN^)
    ) else (
        echo   [+] IP LAN Terdeteksi : %%b
    )
)
if not defined PRIMARY_IP set "PRIMARY_IP=127.0.0.1"
echo -------------------------------------------------------------------------------
echo.
echo [2/3] Alamat Akses Web Dashboard SIKOMAT:
echo   - Komputer Ini (Localhost) : http://127.0.0.1:8000
echo   - Komputer / Laptop Lain   : http://%PRIMARY_IP%:8000
echo   - Node Raspberry Pi        : http://%PRIMARY_IP%:8000
echo.
echo [3/3] Menjalankan Server Laravel (Host: 0.0.0.0, Port: 8000)...
echo Tekan CTRL + C untuk menghentikan server.
echo ===============================================================================
echo.

php artisan serve --host=0.0.0.0 --port=8000
pause
