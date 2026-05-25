import paramiko

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect('157.66.34.199', 11587, 'root', 'M4m4cantik@dstechsmart10051987HdqHq345', timeout=10)
    
    script = """
import sqlite3
conn = sqlite3.connect('/www/server/panel/data/default.db')
c = conn.cursor()
c.execute("SELECT username, password FROM databases WHERE name='xpos'")
row = c.fetchone()
if row:
    print(f"DB_USERNAME={row[0]}")
    print(f"DB_PASSWORD={row[1]}")
else:
    print("xpos not found")
"""
    sftp = ssh.open_sftp()
    with sftp.file('/tmp/get_sqlite.py', 'w') as f:
        f.write(script)
    sftp.close()

    stdin, stdout, stderr = ssh.exec_command('python3 /tmp/get_sqlite.py', timeout=10)
    print("SQLITE OUTPUT:")
    print(stdout.read().decode())
    print(stderr.read().decode())

except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
