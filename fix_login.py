import paramiko
import re

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    # Download the file to memory
    sftp = ssh.open_sftp()
    remote_path = '/www/wwwroot/pos.dstechsmart.com/app/Http/Controllers/LoginController.php'
    with sftp.file(remote_path, 'r') as f:
        content = f.read().decode('utf-8')
    
    # Fix the syntax error
    content = re.sub(r"throw new \\Exception\('Partner tidak ditemukan\. Email: ' \. ->input\('email'\) \. ' - RecordOwnerID: ' \. \);", 
                     r"throw new \\Exception('Partner tidak ditemukan, Silahkan Hubungi Operator');", 
                     content)
    
    # Also fix any other broken variations just in case
    content = re.sub(r"throw new \\Exception\('Partner tidak ditemukan.*?\);", 
                     r"throw new \\Exception('Partner tidak ditemukan, Silahkan Hubungi Operator');", 
                     content)
    
    with sftp.file(remote_path, 'w') as f:
        f.write(content.encode('utf-8'))
        
    sftp.close()
    
    # Write the fixed content locally as well
    with open(r'd:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\app\Http\Controllers\LoginController.php', 'w', encoding='utf-8') as f:
        f.write(content)
        
    print("Fixed locally and on live server.")
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
