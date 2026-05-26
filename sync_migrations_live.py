import paramiko
import os
import glob

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/database/migrations/'
local_dir = 'd:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/database/migrations/'

print("Connecting...")
ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    sftp = ssh.open_sftp()
    
    # Ensure remote directory exists
    try:
        sftp.stat(remote_dir)
    except IOError:
        sftp.mkdir(remote_dir)
        
    print("Uploading migration files...")
    # Get local files
    local_files = glob.glob(os.path.join(local_dir, "*.php"))
    for file_path in local_files:
        filename = os.path.basename(file_path)
        remote_path = remote_dir + filename
        try:
            # Check if file exists remotely
            remote_stat = sftp.stat(remote_path)
            local_stat = os.stat(file_path)
            # if remote file size is different or we just overwrite
            if remote_stat.st_size != local_stat.st_size:
                print(f"Updating {filename}...")
                sftp.put(file_path, remote_path)
        except IOError:
            # File doesn't exist remotely
            print(f"Uploading new {filename}...")
            sftp.put(file_path, remote_path)
            
    print("Migrations uploaded.")
    sftp.close()
    
    # Run artisan migrate
    print("Running artisan migrate...")
    stdin, stdout, stderr = ssh.exec_command('cd /www/wwwroot/pos.dstechsmart.com && php artisan migrate --force')
    print("STDOUT:")
    print(stdout.read().decode())
    print("STDERR:")
    print(stderr.read().decode())
    
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
