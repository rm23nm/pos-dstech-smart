import os
import paramiko
import subprocess
import difflib

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

db_user = 'XPOS_Database'
db_pass = 'xpos123XPOS'

print("Dumping live schema...")
ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    dump_cmd = f"mysqldump -u{db_user} -p'{db_pass}' --no-data --skip-comments --skip-dump-date xpos > /tmp/live_schema.sql"
    ssh.exec_command(dump_cmd)
    
    sftp = ssh.open_sftp()
    sftp.get('/tmp/live_schema.sql', 'live_schema.sql')
    sftp.close()
    print("Live schema downloaded.")
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()

# Compare
with open('local_schema.sql', 'r') as f1, open('live_schema.sql', 'r', encoding='utf-8', errors='ignore') as f2:
    lines1 = f1.readlines()
    lines2 = f2.readlines()

diff = difflib.unified_diff(lines1, lines2, fromfile='local', tofile='live')
with open('schema_diff.txt', 'w') as f:
    f.writelines(diff)

print("Done. Saved differences to schema_diff.txt")
