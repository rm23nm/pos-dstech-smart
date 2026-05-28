import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    cmd = 'mysql -u root -e "USE db_pos_main; UPDATE company SET JenisUsaha=\'TiketGate\' WHERE KodePartner=\'demogate\';"'
    print(f"Running: {cmd}")
    stdin, stdout, stderr = ssh.exec_command(cmd)
    print("STDOUT:")
    print(stdout.read().decode())
    print("STDERR:")
    print(stderr.read().decode())
    
    print("Update complete.")
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
