@echo off
set PHP_PATH=C:\xampp\php\php.exe
echo Menghentikan proses di port 8000 (jika ada)...
for /f "tokens=5" %%a in ('netstat -aon ^| findstr :8000') do taskkill /f /pid %%a 2>nul
echo.
echo Menjalankan DStech POS di Port 8001...
echo Silahkan buka: http://localhost:8001/billing-new
echo.
%PHP_PATH% artisan serve --port=8001
pause
