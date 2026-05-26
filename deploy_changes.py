import paramiko
import os
import glob

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    sftp = ssh.open_sftp()
    
    # 1. Upload blade files
    blade_files = glob.glob('resources/views/Transaksi/Penjualan/PoS/*.blade.php')
    for f in blade_files:
        remote_path = f'/www/wwwroot/pos.dstechsmart.com/{f}'.replace('\\', '/')
        print(f'Uploading {f} to {remote_path}...')
        sftp.put(f, remote_path)
    
    # 2. Upload scripts
    scripts = [
        'check_termin.php',
        'check_setting_account.php',
        'fix_item_accounts.php',
        'check_printer.php',
        'check_demo_account.php'
    ]
    
    for s in scripts:
        remote_path = f'/www/wwwroot/pos.dstechsmart.com/{s}'
        print(f'Uploading {s} to {remote_path}...')
        sftp.put(s, remote_path)
        
    sftp.close()
    
    # 3. Execute scripts on live
    print("Executing scripts on live...")
    for s in scripts:
        cmd = f'cd /www/wwwroot/pos.dstechsmart.com && php {s}'
        print(f"Running: {cmd}")
        stdin, stdout, stderr = ssh.exec_command(cmd)
        print(stdout.read().decode())
        print(stderr.read().decode())
        
    print("Deployment and live database update complete.")
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
