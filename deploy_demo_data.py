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
    
    files = ['demo_data.json', 'import_demo_products.php']
    for f in files:
        remote_path = f'/www/wwwroot/pos.dstechsmart.com/{f}'
        print(f'Uploading {f} to {remote_path}...')
        sftp.put(f, remote_path)
    sftp.close()
    
    print("Executing import script on live...")
    cmd = 'cd /www/wwwroot/pos.dstechsmart.com && php import_demo_products.php'
    stdin, stdout, stderr = ssh.exec_command(cmd)
    print(stdout.read().decode())
    print(stderr.read().decode())
    
    print("Done importing demo data to live.")
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
