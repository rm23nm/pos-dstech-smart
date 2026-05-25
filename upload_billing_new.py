import paramiko
import os

hostname = '157.66.34.199'
port = 11587
username = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
local_path = 'resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php'
remote_path = '/www/wwwroot/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php'

try:
    print('Connecting to SSH...')
    transport = paramiko.Transport((hostname, port))
    transport.connect(username=username, password=password)
    sftp = paramiko.SFTPClient.from_transport(transport)
    
    print(f'Uploading {local_path} to {remote_path}...')
    sftp.put(local_path, remote_path)
    print('Upload complete!')
    
    sftp.close()
    transport.close()
except Exception as e:
    print(f'Error: {e}')
