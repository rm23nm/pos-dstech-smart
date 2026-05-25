import paramiko
import os

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

try:
    print("Connecting to SSH...")
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(host, port, user, password)
    
    print("Running migration...")
    stdin, stdout, stderr = client.exec_command('cd /www/wwwroot/pos.dstechsmart.com && php artisan migrate --force')
    print("Output:", stdout.read().decode())
    err = stderr.read().decode()
    if err:
        print("Error:", err)
        
    client.close()
    print("Done!")
except Exception as e:
    print("Exception:", str(e))
