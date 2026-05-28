import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    cmd = 'cd /www/wwwroot/pos.dstechsmart.com && git stash -u && git pull origin main && git stash pop'
    print(f"Running: {cmd}")
    stdin, stdout, stderr = ssh.exec_command(cmd)
    print("STDOUT:")
    print(stdout.read().decode())
    print("STDERR:")
    print(stderr.read().decode())
    
    print("Git pull complete.")
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
