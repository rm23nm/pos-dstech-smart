import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(hostname=host, port=port, username=user, password=password)

# Get ALL table names
cmd = f"cd {remote_dir} && php artisan tinker --execute=\"\$t = DB::select('SHOW TABLES'); foreach(\$t as \$r) {{ \$v = array_values((array)\$r)[0]; echo \$v . PHP_EOL; }}\""
stdin, stdout, stderr = ssh.exec_command(cmd)
out = stdout.read().decode()
print("All tables:\n", out)

ssh.close()
