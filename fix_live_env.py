import paramiko
import re

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect('157.66.34.199', 11587, 'root', 'M4m4cantik@dstechsmart10051987HdqHq345', timeout=10)
    
    sftp = ssh.open_sftp()
    
    # Read the current .env
    remote_path = '/www/wwwroot/pos.dstechsmart.com/.env'
    with sftp.file(remote_path, 'r') as f:
        env_content = f.read().decode('utf-8')
        
    # Replace DB config
    env_content = re.sub(r'DB_DATABASE=.*', 'DB_DATABASE=xpos', env_content)
    env_content = re.sub(r'DB_USERNAME=.*', 'DB_USERNAME=XPOS_Database', env_content)
    env_content = re.sub(r'DB_PASSWORD=.*', 'DB_PASSWORD=xpos123XPOS', env_content)
    env_content = re.sub(r'APP_DEBUG=.*', 'APP_DEBUG=false', env_content)
    env_content = re.sub(r'APP_ENV=.*', 'APP_ENV=production', env_content)

    # Write back
    with sftp.file(remote_path, 'w') as f:
        f.write(env_content.encode('utf-8'))
        
    sftp.close()
    print("Successfully fixed live .env")

except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
