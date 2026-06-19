Add-Type -AssemblyName System.Drawing

$pngPath = "d:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\public\images\misc\logo-dashboard.png"
$icoPath = "d:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\public\images\misc\dsms_dashboard.ico"

try {
    $png = [System.Drawing.Bitmap]::FromFile($pngPath)
    $ico = [System.Drawing.Icon]::FromHandle($png.GetHicon())
    
    $fs = New-Object System.IO.FileStream($icoPath, [System.IO.FileMode]::Create)
    $ico.Save($fs)
    $fs.Close()
    
    $png.Dispose()
    $ico.Dispose()
    Write-Host "Berhasil membuat dsms.ico"
} catch {
    Write-Host "Gagal: $_"
}
