import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    queries = [
        "SELECT email, RecordOwnerID FROM users WHERE email='fulladmin@gmail.com';",
        "SELECT email, RecordOwnerID FROM users WHERE RecordOwnerID='CL0014';",
        "SELECT KodePartner, NamaPartner, CustomDomain FROM company WHERE KodePartner IN ('999999', 'CL0014', 'demoapotek', 'DEMOGATE');",
        "SELECT COUNT(*) FROM company;"
    ]
    
    for q in queries:
        print(f"=== {q} ===")
        cmd = f'mysql -u XPOS_Database -p"xpos123XPOS" xpos -e "{q}"'
        stdin, stdout, stderr = ssh.exec_command(cmd)
        print(stdout.read().decode())
        print(stderr.read().decode())

except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
