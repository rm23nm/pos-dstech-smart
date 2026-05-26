import paramiko

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
        lines = f.readlines()
    
    for i, line in enumerate(lines):
        if "$oPartner = Company::where('KodePartner','=',$RecordOwnerID)->first();" in line.decode('utf-8'):
            # insert before this line
            lines.insert(i, "            \\Log::debug('LOGIN_DEBUG - Email: ' . $request->input('email') . ' - RecordOwnerID: ' . $RecordOwnerID);\n".encode('utf-8'))
            break
            
    with sftp.file(remote_path, 'w') as f:
        f.writelines(lines)
        
    print("Debug log added safely.")
    sftp.close()
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
