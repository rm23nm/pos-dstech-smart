import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(hostname=host, port=port, username=user, password=password)

# Check columns in company table
cmd = f"cd {remote_dir} && php artisan tinker --execute=\"print_r(array_column(DB::select('DESCRIBE company'), 'Field'));\""
stdin, stdout, stderr = ssh.exec_command(cmd)
print("Company columns:", stdout.read().decode())
err = stderr.read().decode()
if err:
    print("Errors:", err[:300])

ssh.close()
