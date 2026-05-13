@echo off
echo Mengatur limit server dan mengimpor ulang dari backup...
C:\xampp\mysql\bin\mysql.exe -u root -e "SET GLOBAL max_allowed_packet=1073741824; DROP DATABASE IF EXISTS xpos; CREATE DATABASE xpos;"
C:\xampp\mysql\bin\mysql.exe -u root --max_allowed_packet=1024M xpos < "Backup Database\xpos.sql"
if %errorlevel% equ 0 (
    echo.
    echo ===================================================
    echo   IMPORT DATABASE BERHASIL! (Data Live Berhasil Masuk)
    echo ===================================================
) else (
    echo.
    echo ===================================================
    echo   IMPORT GAGAL!
    echo ===================================================
)
pause
