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
    
    file = 'resources/views/auth/login.blade.php'
    remote_path = f'/www/wwwroot/pos.dstechsmart.com/{file}'
    print(f'Uploading {file} to {remote_path}...')
    sftp.put(file, remote_path)
    
    sftp.close()
    print("Done uploading login.blade.php to live.")
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
