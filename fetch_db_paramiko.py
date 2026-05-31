import paramiko

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect('157.66.34.199', port=11587, username='root', password='M4m4cantik@dstechsmart10051987HdqHq345')

php_code = """
<?php
$titik = DB::table('titiklampu')->where('NamaTitikLampu', 'LIKE', '%Meja 3%')->first();
$orders = DB::table('tableorderheader')->where('tableid', $titik->id)->orderBy('id', 'desc')->limit(3)->get();
echo "Titik Status: " . $titik->Status . "\\n";
foreach($orders as $o) {
    echo "Order: " . $o->NoTransaksi . " | Status: " . $o->Status . " | DocStatus: " . $o->DocumentStatus . " | JamMulai: " . $o->JamMulai . "\\n";
}
"""

with open('temp_db.php', 'w') as f:
    f.write(php_code)

import os
os.system('scp temp_db.php root@157.66.34.199:/www/wwwroot/pos.dstechsmart.com/temp_db.php')
stdin, stdout, stderr = ssh.exec_command("php /www/wwwroot/pos.dstechsmart.com/artisan tinker /www/wwwroot/pos.dstechsmart.com/temp_db.php")
output = stdout.read().decode('utf-8', errors='ignore')
print(output)
