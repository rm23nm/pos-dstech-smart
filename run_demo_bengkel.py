import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

transport = paramiko.Transport((host, port))
transport.connect(username=user, password=password)
sftp = paramiko.SFTPClient.from_transport(transport)

# Upload script
print("Uploading create_demo_bengkel.php...")
sftp.put('create_demo_bengkel.php', remote_dir + 'create_demo_bengkel.php')
sftp.close()

# Run via SSH - gunakan php langsung bukan tinker
ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(hostname=host, port=port, username=user, password=password)

cmd = f"cd {remote_dir} && php artisan tinker --execute=\"require 'create_demo_bengkel.php';\""
print(f"Running: {cmd}")
stdin, stdout, stderr = ssh.exec_command(cmd)
print("Output:", stdout.read().decode())
err = stderr.read().decode()
if err:
    print("Errors:", err)

ssh.close()
transport.close()
print("Done.")
