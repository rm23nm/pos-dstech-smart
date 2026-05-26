import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    print("=== Nginx Test ===")
    stdin, stdout, stderr = ssh.exec_command('nginx -t')
    print(stdout.read().decode())
    print(stderr.read().decode())
    
    print("=== Nginx Status ===")
    stdin, stdout, stderr = ssh.exec_command('systemctl status nginx --no-pager')
    print(stdout.read().decode()[:500])
    
    print("=== Laravel Log ===")
    stdin, stdout, stderr = ssh.exec_command('tail -n 20 /www/wwwroot/pos.dstechsmart.com/storage/logs/laravel.log')
    print(stdout.read().decode())
    print(stderr.read().decode())
    
    print("=== HTTP Status ===")
    stdin, stdout, stderr = ssh.exec_command('curl -I http://localhost')
    print(stdout.read().decode())
    
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
