import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
files_to_upload = [
    'resources/views/Transaksi/Penjualan/PoS/FnBPoS.blade.php',
    'resources/views/Transaksi/Penjualan/PoS/TicketingPoS.blade.php',
    'resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php',
    'resources/views/Transaksi/Penjualan/PoS/NormalPoS_Premium.blade.php',
    'resources/views/Transaksi/Penjualan/PoS/NormalPoS.blade.php'
]
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    sftp = ssh.open_sftp()
    for local_file in files_to_upload:
        remote_file = remote_dir + local_file
        print(f"Uploading {local_file} to {remote_file}...")
        sftp.put(local_file, remote_file)
    sftp.close()
    print("Upload complete.")
except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
