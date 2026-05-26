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
    
    # Upload FakturPenjualanController.php
    local_file = 'app/Http/Controllers/FakturPenjualanController.php'
    remote_file = '/www/wwwroot/pos.dstechsmart.com/app/Http/Controllers/FakturPenjualanController.php'
    print(f'Uploading {local_file} to {remote_file}...')
    sftp.put(local_file, remote_file)
    sftp.close()
    print("Upload complete.")
    
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
