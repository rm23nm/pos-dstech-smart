@echo off
set "PHP_EXE=php"

:: Cek apakah 'php' tersedia secara global
where php >nul 2>nul
if %errorlevel% neq 0 (
    :: Jika tidak ada secara global, coba cari di lokasi XAMPP (C: atau D:)
    if exist "C:\xampp\php\php.exe" (
        set "PHP_EXE=C:\xampp\php\php.exe"
    ) else if exist "D:\xampp\php\php.exe" (
        set "PHP_EXE=D:\xampp\php\php.exe"
    ) else (
        echo ===================================================
        echo   ERROR: PHP TIDAK DITEMUKAN
        echo ===================================================
        echo Perintah 'php' tidak dikenali di komputer ini.
        echo.
        echo Solusi:
        echo 1. Pastikan XAMPP sudah terinstal.
        echo 2. Jika XAMPP di lokasi selain C:\xampp atau D:\xampp,
        echo    Bapak perlu menambahkan folder 'php' ke System PATH Windows.
        echo 3. Atau buka 'XAMPP Control Panel' - klik 'Shell',
        echo    lalu tarik file .bat ini ke dalam layar hitam tersebut.
        echo ===================================================
        pause
        exit /b
    )
)

echo ===================================================
echo   MEMULAI SERVER LOKAL DSTECH POS (LARAVEL)
echo ===================================================
echo.
echo Menggunakan PHP dari: %PHP_EXE%
echo.
echo Pastikan Apache dan MySQL di XAMPP sudah START!
echo.

cd /d "%~dp0"

echo Membersihkan cache...
"%PHP_EXE%" artisan config:clear
"%PHP_EXE%" artisan cache:clear

echo.
echo Memulai server di http://127.0.0.1:8000
echo (Tekan CTRL + C untuk mematikan server)
echo.
"%PHP_EXE%" artisan serve --host=127.0.0.1 --port=8000

pause
