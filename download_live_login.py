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
    sftp.get('/www/wwwroot/pos.dstechsmart.com/app/Http/Controllers/LoginController.php', 'd:\\OneDrive\\My Project Aplikasi\\pos.dstechsmart.com\\app\\Http\\Controllers\\LoginController.php')
    sftp.close()
    print("Downloaded successfully")
    
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
