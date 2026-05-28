import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

transport = paramiko.Transport((host, port))
transport.connect(username=user, password=password)
sftp = paramiko.SFTPClient.from_transport(transport)

print("Uploading fix_subscription4.php...")
sftp.put('fix_subscription4.php', remote_dir + 'fix_subscription4.php')
sftp.close()

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(host, port, user, password)

print("Running fix script on live server...")
stdin, stdout, stderr = ssh.exec_command(f'cd {remote_dir} && php artisan tinker --execute="require base_path(\'fix_subscription4.php\');"')
print(stdout.read().decode())
print(stderr.read().decode())
ssh.close()
print("Done!")
