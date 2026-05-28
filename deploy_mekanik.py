import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

transport = paramiko.Transport((host, port))
transport.connect(username=user, password=password)
sftp = paramiko.SFTPClient.from_transport(transport)

files = [
    'app/Http/Controllers/MekanikController.php',
    'routes/web.php',
    'insert_permissions.php'
]

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(host, port=port, username=user, password=password)

ssh.exec_command('mkdir -p ' + remote_dir + 'resources/views/MasterData/Mekanik')

for f in files:
    print("Uploading " + f)
    sftp.put(f, remote_dir + f)

sftp.put('resources/views/MasterData/Mekanik/index.blade.php', remote_dir + 'resources/views/MasterData/Mekanik/index.blade.php')

sftp.close()

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(host, port=port, username=user, password=password)

commands = [
    'cd /www/wwwroot/pos.dstechsmart.com && php artisan tinker --execute="require base_path(\'insert_permissions.php\');"'
]

for cmd in commands:
    print(f"Executing: {cmd}")
    stdin, stdout, stderr = ssh.exec_command(cmd)
    print("STDOUT:", stdout.read().decode())
    print("STDERR:", stderr.read().decode())

ssh.close()
print("Done!")
