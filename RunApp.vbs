Set objFSO = CreateObject("Scripting.FileSystemObject")
Set WshShell = CreateObject("WScript.Shell")

' Dapatkan direktori tempat script ini berada
strPath = objFSO.GetParentFolderName(WScript.ScriptFullName)
WshShell.CurrentDirectory = strPath

' Jalankan PHP Artisan Serve di background (tanpa CMD)
WshShell.Run "cmd /c C:\xampp\php\php.exe artisan serve --port=8001", 0, False

' Jalankan sistem Sinkronisasi Offline di background
WshShell.Run "cmd /c C:\xampp\php\php.exe artisan pos:sync", 0, False

' Tunggu 2 detik agar server siap
WScript.Sleep 2000

' Buka Google Chrome dalam mode Aplikasi Desktop (langsung ke halaman login)
WshShell.Run "cmd /c start chrome.exe --app=""http://localhost:8001/login""", 0, False
