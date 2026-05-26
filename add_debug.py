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
    
    # Add Log::debug
    target = r"// Validasi Kode Partner Exist\s*\$oPartner = Company::where\('KodePartner','=',\$RecordOwnerID\)->first\(\);"
    replacement = r"// Validasi Kode Partner Exist\n            \Log::debug('LOGIN_DEBUG', ['email' => $request->input('email'), 'RecordOwnerID' => $RecordOwnerID]);\n            $oPartner = Company::where('KodePartner','=',$RecordOwnerID)->first();"
    
    new_content = re.sub(target, replacement, content)
    
    if content == new_content:
        print("Regex didn't match!")
    else:
        with sftp.file(remote_path, 'w') as f:
            f.write(new_content.encode('utf-8'))
        print("Debug log added to live server.")
        
    sftp.close()
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
