import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    print("=== Killing stuck chrome and node processes ===")
    stdin, stdout, stderr = ssh.exec_command('pkill -f chrome && pkill -f node')
    print(stdout.read().decode())
    
    print("=== Dropping Caches ===")
    stdin, stdout, stderr = ssh.exec_command('sync; echo 3 > /proc/sys/vm/drop_caches')
    print(stdout.read().decode())
    
    print("=== New Load Average ===")
    stdin, stdout, stderr = ssh.exec_command('uptime')
    print(stdout.read().decode())

except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
