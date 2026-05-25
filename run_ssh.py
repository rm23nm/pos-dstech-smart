import paramiko

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect('157.66.34.199', 11587, 'root', 'M4m4cantik@dstechsmart10051987HdqHq345')
    
    # Try reading the MySQL root password from aaPanel
    stdin, stdout, stderr = ssh.exec_command('cat /www/server/data/root.pl')
    print("MYSQL ROOT PASS:")
    print(stdout.read().decode())
    
    # Or try finding another app's .env that might have the POS db config?
    stdin, stdout, stderr = ssh.exec_command('grep -r "DB_" /www/wwwroot/pos.dstechsmart.com/storage/logs/ | tail -n 20')
    print("POS LOGS DB:")
    print(stdout.read().decode())

except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
