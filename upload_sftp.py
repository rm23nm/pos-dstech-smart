import paramiko
import sys

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
local_path = sys.argv[1]
remote_path = sys.argv[2]

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    sftp = ssh.open_sftp()
    sftp.put(local_path, remote_path)
    sftp.close()
    print("File uploaded successfully.")
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
