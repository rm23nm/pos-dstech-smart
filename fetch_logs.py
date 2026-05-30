import paramiko

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect('157.66.34.199', port=11587, username='root', password='M4m4cantik@dstechsmart10051987HdqHq345')

sftp = ssh.open_sftp()
try:
    with sftp.open('/www/wwwroot/pos.dstechsmart.com/storage/logs/laravel.log', 'r') as f:
        # Read the last 1MB
        f.seek(0, 2)
        size = f.tell()
        f.seek(max(0, size - 1000000))
        content = f.read().decode('utf-8', errors='ignore')
        lines = content.splitlines()
        
        # print the last 50 lines matching storePaket or error
        matched = []
        for line in lines:
            if 'storePaket' in line or 'ERROR' in line.upper() or 'EXCEPTION' in line.upper():
                matched.append(line)
        
        for line in matched[-50:]:
            print(line)
except Exception as e:
    print("Error:", e)
finally:
    sftp.close()
    ssh.close()
