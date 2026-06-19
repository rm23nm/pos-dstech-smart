@echo off
echo ==============================================================
echo [DSTech Smart] Instalasi Cron / Task Scheduler POS Offline
echo ==============================================================
echo.
echo Pastikan PHP sudah berada di Environment Variables (Path),
echo atau sesuaikan path PHP di script ini secara manual.
echo (Contoh: C:\xampp\php\php.exe)
echo.

set PHP_PATH=C:\xampp\php\php.exe
set ARTISAN_PATH=%~dp0artisan

if not exist "%PHP_PATH%" (
    echo [Peringatan] PHP tidak ditemukan di %PHP_PATH%.
    echo Tolong pastikan path PHP benar sebelum melanjutkan.
    pause
    exit /b
)

echo Mendaftarkan Task Scheduler Windows untuk menjalankan sinkronisasi POS tiap 1 menit...
echo (Catatan: Artisan schedule:run akan menjalankan pos:sync tiap 5 menit sesuai konfigurasi Kernel)

schtasks /create /tn "DSTech_POS_Sync" /tr "%PHP_PATH% %ARTISAN_PATH% schedule:run" /sc minute /mo 1 /f /rl highest

if %ERRORLEVEL% equ 0 (
    echo.
    echo [SUKSES] Task Scheduler berhasil dibuat!
    echo Sinkronisasi Offline POS ke Live Server sekarang berjalan secara otomatis di latar belakang.
) else (
    echo.
    echo [GAGAL] Terjadi kesalahan. Tolong jalankan script ini sebagai Administrator (Run as Administrator).
)

echo.
pause
