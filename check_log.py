import paramiko

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect('157.66.34.199', port=11587, username='root', password='M4m4cantik@dstechsmart10051987HdqHq345')
stdin, stdout, stderr = ssh.exec_command("tail -n 50 /www/wwwroot/pos.dstechsmart.com/storage/logs/laravel.log")
print(stdout.read().decode())
