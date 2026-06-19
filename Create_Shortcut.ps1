$WshShell = New-Object -ComObject WScript.Shell
$DesktopPath = [Environment]::GetFolderPath('Desktop')
$Shortcut = $WshShell.CreateShortcut("$DesktopPath\Kasir POS.lnk")

# Targetnya langsung ke Google Chrome (mencegah bug ikon Windows)
$Shortcut.TargetPath = "C:\Program Files\Google\Chrome\Application\chrome.exe"

# Argumennya adalah alamat URL dalam mode App
$Shortcut.Arguments = "--app=`"http://localhost:8001/login`""

# Direktori kerja
$Shortcut.WorkingDirectory = "C:\Program Files\Google\Chrome\Application"

# Icon, menggunakan logo POS yang valid
$Shortcut.IconLocation = "d:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\public\images\misc\dsms_final.ico,0"

$Shortcut.Save()
Write-Host "Shortcut berhasil dibuat di Desktop!"
