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
    remote_path = '/www/wwwroot/pos.dstechsmart.com/app/Http/Controllers/LoginController.php'
    with sftp.file(remote_path, 'r') as f:
        content = f.read().decode('utf-8')
    
    target = r"if \(\$user->current_session_id && Session::getHandler\(\)->read\(\$user->current_session_id\)\) \{\s*// Hapus session lama dari file/redis agar device lama langsung ter-logout\s*Session::getHandler\(\)->destroy\(\$user->current_session_id\);\s*\}"
    replacement = r"if ($user->current_session_id && $user->current_session_id !== Session::getId()) {\n                            if (Session::getHandler()->read($user->current_session_id)) {\n                                // Hapus session lama dari file/redis agar device lama langsung ter-logout\n                                Session::getHandler()->destroy($user->current_session_id);\n                            }\n                        }"
    
    new_content = re.sub(target, replacement, content)
    
    if content != new_content:
        with sftp.file(remote_path, 'w') as f:
            f.write(new_content.encode('utf-8'))
        print("Live server fixed.")
    else:
        print("Regex didn't match.")
        
    sftp.close()
except Exception as e:
    print(e)
finally:
    ssh.close()
