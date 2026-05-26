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
    
    # The bypass we did earlier was wrapping the `if` block with `/*` and `*/`
    # Let's just remove the block comments.
    
    new_content = content.replace("/*\n                if (\n                    empty($user->current_session_id) ||\n                    $user->current_session_id !== session()->getId()\n                ) {\n                    Auth::logout();\n                    session()->invalidate();\n                    session()->regenerateToken();\n\n                    return redirect('/')\n                        ->withErrors(['message' => 'Akun ini telah login di perangkat lain.']);\n                }\n                */", "if (\n                    empty($user->current_session_id) ||\n                    $user->current_session_id !== session()->getId()\n                ) {\n                    Auth::logout();\n                    session()->invalidate();\n                    session()->regenerateToken();\n\n                    return redirect('/')\n                        ->withErrors(['message' => 'Akun ini telah login di perangkat lain.']);\n                }")
    
    if content != new_content:
        with sftp.file(remote_path, 'w') as f:
            f.write(new_content.encode('utf-8'))
        print("Live server restored.")
    else:
        print("String replace didn't match.")
        
    sftp.close()
except Exception as e:
    print(e)
finally:
    ssh.close()
