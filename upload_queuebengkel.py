import paramiko
import os

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

files_to_upload = [
    'routes/web.php',
    'app/Http/Controllers/QueueBengkelController.php',
    'resources/views/Transaksi/Penjualan/QueueManagement/QueueBengkel.blade.php',
    'add_statusservis.php'
]

try:
    transport = paramiko.Transport((host, port))
    transport.connect(username=user, password=password)
    sftp = paramiko.SFTPClient.from_transport(transport)
    
    # Ensure remote directory structure exists
    try:
        sftp.stat(remote_dir + 'resources/views/Transaksi/Penjualan/QueueManagement')
    except IOError:
        pass # Assuming it exists already based on other files

    for f in files_to_upload:
        local_path = f
        remote_path = remote_dir + f
        print(f"Uploading {local_path}...")
        sftp.put(local_path, remote_path)
    
    print("Files uploaded successfully.")
    sftp.close()

    # Create SSH client to run commands
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(hostname=host, port=port, username=user, password=password)

    # Run the DB migration script via tinker
    cmd = f"cd {remote_dir} && php artisan tinker < add_statusservis.php"
    print(f"Running command: {cmd}")
    stdin, stdout, stderr = ssh.exec_command(cmd)
    
    print("Command Output:")
    print(stdout.read().decode('utf-8'))
    print("Command Error (if any):")
    print(stderr.read().decode('utf-8'))
    
    ssh.close()
    transport.close()
    print("Deployment completed.")
except Exception as e:
    print(f"Failed: {e}")
