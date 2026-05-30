import paramiko
import json

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect('157.66.34.199', port=11587, username='root', password='M4m4cantik@dstechsmart10051987HdqHq345')

php_code = """
$titik = DB::table('titiklampu')->where('NamaTitikLampu', 'LIKE', '%Meja 3%')->first();
$order = DB::table('tableorderheader')->where('tableid', $titik->id)->orderBy('id', 'desc')->first();
echo json_encode(['titik' => $titik, 'order' => $order]);
"""
stdin, stdout, stderr = ssh.exec_command(f'php /wwwroot/pos.dstechsmart.com/artisan tinker --execute="{php_code}"')
print(stdout.read().decode())
