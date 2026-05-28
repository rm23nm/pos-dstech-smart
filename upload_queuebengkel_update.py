import paramiko
import os

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

files_to_upload = [
    'resources/views/Transaksi/Penjualan/QueueManagement/QueueBengkel.blade.php',
]

try:
    transport = paramiko.Transport((host, port))
    transport.connect(username=user, password=password)
    sftp = paramiko.SFTPClient.from_transport(transport)
    
    for f in files_to_upload:
        local_path = f
        remote_path = remote_dir + f
        print(f"Uploading {local_path}...")
        sftp.put(local_path, remote_path)
    
    print("Files uploaded successfully.")
    sftp.close()
    transport.close()
    print("Deployment completed.")
except Exception as e:
    print(f"Failed: {e}")
