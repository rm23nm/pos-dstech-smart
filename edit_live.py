import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    cmd = '''sed -i "s/throw new \\\\Exception('Partner tidak ditemukan, Silahkan Hubungi Operator');/throw new \\\\Exception('Partner tidak ditemukan. Email: ' . \$request->input('email') . ' - RecordOwnerID: ' . \$RecordOwnerID);/g" /www/wwwroot/pos.dstechsmart.com/app/Http/Controllers/LoginController.php'''
    stdin, stdout, stderr = ssh.exec_command(cmd)
    
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
