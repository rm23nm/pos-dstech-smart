import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

files_to_upload = [
    ('resources/views/auth/login.blade.php', '/www/wwwroot/pos.dstechsmart.com/resources/views/auth/login.blade.php'),
    ('database/seeders/DemoAccountSeeder.php', '/www/wwwroot/pos.dstechsmart.com/database/seeders/DemoAccountSeeder.php')
]

try:
    transport = paramiko.Transport((host, port))
    transport.connect(username=user, password=password)
    sftp = paramiko.SFTPClient.from_transport(transport)

    for local_path, remote_path in files_to_upload:
        try:
            print(f"Uploading {local_path} to {remote_path}...")
            sftp.put(local_path, remote_path)
            print(f"Successfully uploaded {local_path}")
        except Exception as e:
            print(f"Error uploading {local_path}: {e}")

    sftp.close()
    transport.close()
    
    # run seeder on live server
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(host, port, user, password)
    print("Running seeder on live server...")
    stdin, stdout, stderr = ssh.exec_command('cd /www/wwwroot/pos.dstechsmart.com && php artisan db:seed --class=DemoAccountSeeder --force')
    print(stdout.read().decode())
    print(stderr.read().decode())
    ssh.close()
    print("Upload completed.")
except Exception as e:
    print(f"Connection failed: {e}")
