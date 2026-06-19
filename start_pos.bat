@echo off
title Memulai Aplikasi POS...
echo Sedang menyiapkan database dan sistem kasir...

:: Pindah ke direktori project
cd /d "%~dp0"

:: Jalankan MySQL di background (jika menggunakan XAMPP standar)
:: Kita asumsikan XAMPP ada di C:\xampp. Sesuaikan jika perlu.
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="1" (
    echo Menjalankan MySQL...
    start /B "" "c:\xampp\mysql\bin\mysqld.exe"
)

:: Jalankan PHP Artisan Serve di background (Cek Port 8000)
netstat -ano | find ":8000 " >NUL
if "%ERRORLEVEL%"=="1" (
    echo Menjalankan Web Server...
    start /B "" "c:\xampp\php\php.exe" artisan serve --port=8000
)

:: Tunggu 4 detik agar server siap
timeout /t 4 /nobreak > NUL

:: Buka Chrome dalam mode Kiosk/App
echo Membuka Aplikasi...
start chrome --app="http://localhost:8000"

exit
