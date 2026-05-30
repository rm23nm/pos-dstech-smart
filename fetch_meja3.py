import paramiko
import json

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect('157.66.34.199', port=11587, username='root', password='M4m4cantik@dstechsmart10051987HdqHq345')

php_code = """
$titik = DB::table('titiklampu')->where('NamaTitikLampu', 'LIKE', '%Meja 3%')->first();
$orders = DB::table('tableorderheader')->where('tableid', $titik->id)->orderBy('id', 'desc')->limit(3)->get();
echo json_encode(['titik_status' => $titik->Status, 'orders' => $orders]);
"""

with open('temp.php', 'w') as f:
    f.write(php_code)

import os
os.system('scp temp.php root@157.66.34.199:/wwwroot/pos.dstechsmart.com/temp.php')
stdin, stdout, stderr = ssh.exec_command("php /wwwroot/pos.dstechsmart.com/artisan tinker --execute=\"require '/wwwroot/pos.dstechsmart.com/temp.php';\"")
print(stdout.read().decode())
