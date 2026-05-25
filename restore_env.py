import paramiko
import time

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect('157.66.34.199', 11587, 'root', 'M4m4cantik@dstechsmart10051987HdqHq345', timeout=5)
    
    # Run a command to extract DB credentials from the POS log files
    stdin, stdout, stderr = ssh.exec_command('grep -r "DB_" /www/wwwroot/pos.dstechsmart.com/storage/logs/ | tail -n 20', timeout=5)
    print("POS LOGS:")
    print(stdout.read().decode())
    
    # Let's check smartpro DB config again
    stdin, stdout, stderr = ssh.exec_command('cat /www/wwwroot/smartpro.dstechsmart.com/.env | grep DB_', timeout=5)
    print("SMARTPRO ENV:")
    print(stdout.read().decode())

except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
