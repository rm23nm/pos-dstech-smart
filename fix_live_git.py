import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    commands = [
        "cd /www/wwwroot/pos.dstechsmart.com",
        "rm -f check_demo_account.php check_printer.php check_setting_account.php check_termin.php demo_data.json fix_item_accounts.php import_demo_products.php",
        "git stash",
        "git pull origin main"
    ]
    
    cmd = " && ".join(commands)
    print(f"Running: {cmd}")
    stdin, stdout, stderr = ssh.exec_command(cmd)
    
    print("STDOUT:")
    print(stdout.read().decode('utf-8', errors='replace'))
    
    print("STDERR:")
    print(stderr.read().decode('utf-8', errors='replace'))
    
    print("Done fixing live git state.")
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
