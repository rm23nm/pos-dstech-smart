import paramiko
import json

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect('157.66.34.199', port=11587, username='root', password='M4m4cantik@dstechsmart10051987HdqHq345')

php_code = """
$results = DB::table('customer_memberships')->get();
echo json_encode($results);
"""

stdin, stdout, stderr = ssh.exec_command('cd /wwwroot/pos.dstechsmart.com && php artisan tinker --execute="' + php_code.replace('"', '\\"') + '"')
out = stdout.read().decode()
err = stderr.read().decode()
print("OUTPUT:")
print(out)
print("ERROR:")
print(err)
