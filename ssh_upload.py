import paramiko
import sys
import os

if len(sys.argv) < 2:
    print("Usage: python ssh_upload.py <local_path> [remote_path]")
    sys.exit(1)

local_path = sys.argv[1]
remote_path = sys.argv[2] if len(sys.argv) > 2 else "/www/wwwroot/pos.dstechsmart.com/" + local_path.replace("\\", "/")

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

try:
    print("Connecting to SSH...")
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(host, port, user, password)
    
    print(f"Uploading {local_path} to {remote_path}...")
    sftp = client.open_sftp()
    
    # Create directories if they don't exist
    remote_dir = os.path.dirname(remote_path)
    try:
        sftp.stat(remote_dir)
    except IOError:
        client.exec_command(f"mkdir -p '{remote_dir}'")
        
    sftp.put(local_path, remote_path)
    sftp.close()
    client.close()
    print("Upload complete!")
except Exception as e:
    print("Exception:", str(e))
