@echo off
title Mematikan Sistem POS...
echo Sedang mematikan sistem kasir dan database...

:: Matikan server PHP bawaan Laravel
taskkill /F /IM php.exe /T 2>NUL

:: Matikan MySQL (hanya jika memang dijalankan dari batch tadi)
taskkill /F /IM mysqld.exe /T 2>NUL

echo Sistem berhasil dimatikan.
timeout /t 2 > NUL
exit
