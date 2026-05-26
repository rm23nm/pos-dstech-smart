import paramiko
import os
import sys

# Set stdout to handle utf-8 to avoid charmap errors on Windows
import codecs
sys.stdout = codecs.getwriter('utf-8')(sys.stdout.buffer, 'strict')

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'
local_dir = 'd:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/'

scripts_to_run = [
    'fix_live_database.php',
    'update_20_features.php',
    'update_all_features.php',
    'update_features.php',
    'update_hiburan.php',
    'db_multicabang_tahap1.php',
    'setup_demo_apotek.php',
    'setup_demo_gate.php',
    'update_live_sps.php'
]

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    print("Executing scripts on live server...")
    for script in scripts_to_run:
        print(f"--- Running {script} ---")
        stdin, stdout, stderr = ssh.exec_command(f'cd {remote_dir} && php {script}')
        print(stdout.read().decode('utf-8', errors='replace'))
        err = stderr.read().decode('utf-8', errors='replace')
        if err:
            print("ERROR:", err)
            
except Exception as e:
    print(f"Connection Error: {e}")
finally:
    ssh.close()
