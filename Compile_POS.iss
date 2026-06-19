[Setup]
AppName=DSMS POS Offline
AppVersion=1.0
DefaultDirName={sd}\DSMS_POS_System
DefaultGroupName=DSMS POS
OutputDir=d:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\public\downloads
OutputBaseFilename=Setup_DSMS_POS
SetupIconFile=d:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\public\images\misc\dsms_final.ico
Compression=lzma
SolidCompression=yes
PrivilegesRequired=admin

[Files]
; Copying Laravel Application
Source: "d:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\*"; DestDir: "{app}\htdocs\pos"; Flags: ignoreversion recursesubdirs createallsubdirs

; Note: To make this a true standalone offline bundle, you would also need to uncomment and copy a portable PHP and MySQL server here.
; Since XAMPP is very large, this script currently sets up the Application Directory.
; Example for portable server:
; Source: "C:\xampp\php\*"; DestDir: "{app}\php"; Flags: ignoreversion recursesubdirs createallsubdirs
; Source: "C:\xampp\mysql\*"; DestDir: "{app}\mysql"; Flags: ignoreversion recursesubdirs createallsubdirs

[Icons]
; Desktop Shortcut pointing to Chrome App Mode
Name: "{commondesktop}\Kasir POS"; Filename: "C:\Program Files\Google\Chrome\Application\chrome.exe"; Parameters: "--app=""http://localhost:8001/login"""; IconFilename: "{app}\htdocs\pos\public\images\misc\dsms_final.ico"

[Run]
; Commands to run after installation (e.g., starting services)
Filename: "cmd.exe"; Parameters: "/c echo Instalasi Selesai. Pastikan Server Berjalan."; Description: "Selesaikan Instalasi"; Flags: postinstall nowait
