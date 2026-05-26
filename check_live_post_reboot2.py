import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    print("=== HTTP Test ===")
    stdin, stdout, stderr = ssh.exec_command('curl -s -I http://localhost')
    print(stdout.read().decode())
    
    print("=== Git Status ===")
    stdin, stdout, stderr = ssh.exec_command('cd /www/wwwroot/pos.dstechsmart.com && git status')
    print(stdout.read().decode())
    
    print("=== Laravel Log ===")
    stdin, stdout, stderr = ssh.exec_command('tail -n 20 /www/wwwroot/pos.dstechsmart.com/storage/logs/laravel.log')
    print(stdout.read().decode('ascii', errors='ignore'))

    print("=== Nginx Error Log ===")
    stdin, stdout, stderr = ssh.exec_command('tail -n 20 /www/wwwlogs/pos.dstechsmart.com.error.log')
    print(stdout.read().decode('ascii', errors='ignore'))

except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
