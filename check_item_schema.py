import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

# Write PHP script to server
php_script = r"""<?php
$res = DB::select('DESCRIBE itemmaster');
foreach ($res as $r) {
    if ($r->Null === 'NO' && $r->Default === null && $r->Key !== 'PRI') {
        echo $r->Field . '|';
    }
}
"""

transport = paramiko.Transport((host, port))
transport.connect(username=user, password=password)
sftp = paramiko.SFTPClient.from_transport(transport)
with sftp.open(remote_dir + 'check_required.php', 'w') as f:
    f.write(php_script)
sftp.close()
transport.close()

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(hostname=host, port=port, username=user, password=password)
cmd = f"cd {remote_dir} && php artisan tinker --execute=\"require 'check_required.php';\""
stdin, stdout, stderr = ssh.exec_command(cmd)
out = stdout.read().decode()
print("Required itemmaster fields:", out)
err = stderr.read().decode()
if err:
    print("Errors:", err[:500])
ssh.close()
