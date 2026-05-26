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
    
    sftp = ssh.open_sftp()
    remote_path = '/www/wwwroot/pos.dstechsmart.com/app/Http/Middleware/CheckUserSession.php'
    with sftp.file(remote_path, 'r') as f:
        content = f.read().decode('utf-8')
    
    target = r"(if \(\s*empty\(\$user->current_session_id\) \|\|\s*\$user->current_session_id !== session\(\)->getId\(\)\s*\) \{\s*Auth::logout\(\);\s*session\(\)->invalidate\(\);\s*session\(\)->regenerateToken\(\);\s*return redirect\('/'\)\s*->withErrors\(\['message' => 'Akun ini telah login di perangkat lain\.'\]\);\s*\})"
    replacement = r"/*\n                \1\n                */"
    
    new_content = re.sub(target, replacement, content)
    
    if content != new_content:
        with sftp.file(remote_path, 'w') as f:
            f.write(new_content.encode('utf-8'))
        print("Live server bypassed.")
    else:
        print("Regex didn't match.")
        
    sftp.close()
except Exception as e:
    print(e)
finally:
    ssh.close()
