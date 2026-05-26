import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    print("=== Uptime and Load Average ===")
    stdin, stdout, stderr = ssh.exec_command('uptime')
    print(stdout.read().decode())
    
    print("=== Free Memory ===")
    stdin, stdout, stderr = ssh.exec_command('free -m')
    print(stdout.read().decode())
    
    print("=== Top Processes ===")
    stdin, stdout, stderr = ssh.exec_command('top -b -n 1 | head -n 15')
    print(stdout.read().decode())
    
    print("=== Rebuild Laravel Cache ===")
    cmd = 'cd /www/wwwroot/pos.dstechsmart.com && php artisan config:cache && php artisan route:cache && php artisan view:cache'
    stdin, stdout, stderr = ssh.exec_command(cmd)
    print(stdout.read().decode())
    print(stderr.read().decode())

except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
