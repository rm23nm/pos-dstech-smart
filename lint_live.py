import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    stdin, stdout, stderr = ssh.exec_command('php -l /www/wwwroot/pos.dstechsmart.com/app/Http/Controllers/LoginController.php')
    print("STDOUT:", stdout.read().decode('utf-8'))
    print("STDERR:", stderr.read().decode('utf-8'))
    
    ssh.close()
except Exception as e:
    print(e)
