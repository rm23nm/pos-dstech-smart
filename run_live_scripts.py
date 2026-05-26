import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    print("Running update_live_sps.php...")
    stdin, stdout, stderr = ssh.exec_command('cd /www/wwwroot/pos.dstechsmart.com && php update_live_sps.php')
    print(stdout.read().decode())
    
    print("Running update_all_features.php...")
    stdin, stdout, stderr = ssh.exec_command('cd /www/wwwroot/pos.dstechsmart.com && php update_all_features.php')
    print(stdout.read().decode())
    
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
