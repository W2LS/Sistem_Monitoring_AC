@echo off
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
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /c:"IPv4 Address" /c:"Alamat IPv4"') do (
    echo   👉 IP LAN Terdeteksi : %%a
)
echo -------------------------------------------------------------------------------
echo.
echo [2/3] Alamat Akses Web Dashboard SIKOMAT:
echo   - Komputer Ini (Localhost) : http://127.0.0.1:8000
echo   - Komputer / Laptop Lain   : http://[IP_LAN_DI_ATAS]:8000
echo   - Node Raspberry Pi        : http://[IP_LAN_DI_ATAS]:8000
echo.
echo [3/3] Menjalankan Server Laravel (Host: 0.0.0.0, Port: 8000)...
echo Tekan CTRL + C untuk menghentikan server.
echo ===============================================================================
echo.

php artisan serve --host=0.0.0.0 --port=8000
pause
