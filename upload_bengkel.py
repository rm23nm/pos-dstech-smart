import paramiko
import os

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

files_to_upload = [
    ('app/Http/Controllers/PoSController.php', '/www/wwwroot/pos.dstechsmart.com/app/Http/Controllers/PoSController.php'),
    ('app/Http/Controllers/FakturPenjualanController.php', '/www/wwwroot/pos.dstechsmart.com/app/Http/Controllers/FakturPenjualanController.php'),
    ('routes/web.php', '/www/wwwroot/pos.dstechsmart.com/routes/web.php'),
    ('resources/views/Transaksi/Penjualan/PoS/BengkelPoS.blade.php', '/www/wwwroot/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BengkelPoS.blade.php'),
    ('app/Models/Kendaraan.php', '/www/wwwroot/pos.dstechsmart.com/app/Models/Kendaraan.php'),
    ('app/Models/Mekanik.php', '/www/wwwroot/pos.dstechsmart.com/app/Models/Mekanik.php'),
    ('create_tables.php', '/www/wwwroot/pos.dstechsmart.com/create_tables.php')
]

try:
    transport = paramiko.Transport((host, port))
    transport.connect(username=user, password=password)
    sftp = paramiko.SFTPClient.from_transport(transport)

    for local_path, remote_path in files_to_upload:
        try:
            print(f"Uploading {local_path} to {remote_path}...")
            sftp.put(local_path, remote_path)
            print(f"Successfully uploaded {local_path}")
        except Exception as e:
            print(f"Error uploading {local_path}: {e}")

    sftp.close()
    transport.close()
    print("Upload completed.")

    # Now run the database script on the server
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(host, port, user, password)
    
    print("Running database script on live server...")
    stdin, stdout, stderr = ssh.exec_command('cd /www/wwwroot/pos.dstechsmart.com && cat create_tables.php | php artisan tinker')
    print("Output:")
    print(stdout.read().decode())
    print("Errors:")
    print(stderr.read().decode())

    ssh.close()

except Exception as e:
    print(f"Connection failed: {e}")
