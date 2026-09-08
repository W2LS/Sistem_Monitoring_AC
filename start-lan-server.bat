@echo off
chcp 65001 >nul
title SIKOMAT AC - Server Laravel LAN Launcher (PT PINDAD)
color 0A
cls
echo ===============================================================================
echo     SISTEM KONTROL DAN MONITORING SUHU AC OTOMATIS (SIKOMAT)
echo     PT PINDAD (PERSERO) - DIVISI MUTU ^& TI
echo ===============================================================================
echo.
echo [1/3] Mendeteksi Alamat IP Komputer pada Jaringan Lokal (LAN / Wi-Fi)...
echo -------------------------------------------------------------------------------
set "PRIMARY_IP="
for /f "usebackq tokens=1,2 delims=#" %%a in (`powershell -NoProfile -Command "$ips = [System.Net.Dns]::GetHostAddresses([System.Net.Dns]::GetHostName()) | Where-Object AddressFamily -eq 'InterNetwork' | ForEach-Object IPAddressToString; $p = $ips | Where-Object { $_ -like '192.168.*' -or $_ -like '10.*' } | Select-Object -First 1; if (-not $p) { $p = $ips | Where-Object { $_ -notlike '127.*' } | Select-Object -First 1 }; if (-not $p) { $p = $ips | Select-Object -First 1 }; foreach ($ip in $ips) { if ($ip -eq $p) { Write-Output ('PRIMARY#' + $ip) } else { Write-Output ('OTHER#' + $ip) } }"`) do (
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
