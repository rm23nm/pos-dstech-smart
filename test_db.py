import paramiko

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect('157.66.34.199', 11587, 'root', 'M4m4cantik@dstechsmart10051987HdqHq345', timeout=10)
    
    # Try reading the BT panel mysql config
    stdin, stdout, stderr = ssh.exec_command('cat /www/server/panel/config/config.json', timeout=10)
    print("BT PANEL CONFIG:")
    print(stdout.read().decode())
    
    # Try just mysql directly without pass
    stdin, stdout, stderr = ssh.exec_command('mysql -e "SHOW DATABASES;"', timeout=10)
    out = stdout.read().decode()
    err = stderr.read().decode()
    print("SHOW DATABASES:")
    print("OUT:", out)
    print("ERR:", err)

except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
