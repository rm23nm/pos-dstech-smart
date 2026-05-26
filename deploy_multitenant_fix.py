import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    sftp = ssh.open_sftp()
    
    # Upload DomainDetectionMiddleware.php
    local_file = 'app/Http/Middleware/DomainDetectionMiddleware.php'
    remote_file = '/www/wwwroot/pos.dstechsmart.com/app/Http/Middleware/DomainDetectionMiddleware.php'
    print(f'Uploading {local_file} to {remote_file}...')
    sftp.put(local_file, remote_file)
    sftp.close()
    print("Upload complete.")
    
    print("Updating .env SESSION_DOMAIN on live...")
    cmd = 'cd /www/wwwroot/pos.dstechsmart.com && sed -i "s/SESSION_DOMAIN=.*/SESSION_DOMAIN=.pos.dstechsmart.com/g" .env && php artisan config:clear && php artisan cache:clear'
    print(f"Running: {cmd}")
    stdin, stdout, stderr = ssh.exec_command(cmd)
    print(stdout.read().decode())
    print(stderr.read().decode())
    
    print("Deployment complete.")
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
