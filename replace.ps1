$content = Get-Content 'd:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views\Gate\DaftarGateSerialNumber.blade.php'
$content = $content -replace "url\('serialnumber", "url('gate-serialnumber"
$content = $content -replace "route\('serialnumber", "route('gate-serialnumber"
$content = $content -replace ">Master Controller Lampu<", ">Gate Controller<"
$content = $content -replace "Master Controller", "Gate Controller"
Set-Content -Path 'd:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views\Gate\DaftarGateSerialNumber.blade.php' -Value $content

$content2 = Get-Content 'd:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views\Gate\GateSerialNumber-Input.blade.php'
$content2 = $content2 -replace "url\('serialnumber", "url('gate-serialnumber"
$content2 = $content2 -replace "route\('serialnumber", "route('gate-serialnumber"
$content2 = $content2 -replace "Master Controller Lampu", "Gate Controller"
Set-Content -Path 'd:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views\Gate\GateSerialNumber-Input.blade.php' -Value $content2
