import paramiko
import json
import subprocess

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
db_user = 'XPOS_Database'
db_pass = 'xpos123XPOS'

# 1. Get local columns
local_cmd = 'C:\\xampp\\mysql\\bin\\mysql.exe -uroot -e "SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=\'xpos\'" -s'
local_output = subprocess.check_output(local_cmd, shell=True).decode('utf-8', errors='ignore')
local_cols = set()
for line in local_output.strip().split('\n'):
    if '\t' in line:
        t, c = line.split('\t')
        local_cols.add(f"{t.lower()}.{c.lower()}")

# 2. Get live columns
ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    live_cmd = f"mysql -u{db_user} -p'{db_pass}' -e \"SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='xpos'\" -s"
    stdin, stdout, stderr = ssh.exec_command(live_cmd)
    live_output = stdout.read().decode('utf-8', errors='ignore')
    
    live_cols = set()
    for line in live_output.strip().split('\n'):
        if '\t' in line:
            t, c = line.split('\t')
            live_cols.add(f"{t.lower()}.{c.lower()}")
            
    # Compare
    missing_in_live = local_cols - live_cols
    missing_in_local = live_cols - local_cols
    
    print("Columns in LOCAL but missing in LIVE:")
    if missing_in_live:
        for c in sorted(missing_in_live):
            print(f" - {c}")
    else:
        print(" -> None! Live has everything local has.")
        
    print("\nColumns in LIVE but missing in LOCAL:")
    if missing_in_local:
        for c in sorted(missing_in_local):
            print(f" - {c}")
    else:
        print(" -> None!")
        
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
