import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

files_to_upload = [
    'app/Http/Controllers/RiwayatServisController.php',
    'resources/views/Transaksi/RiwayatServis/index.blade.php',
    'resources/views/parts/header.blade.php',
    # routes/web.php sudah diupload via patch_webphp.py
]

try:
    transport = paramiko.Transport((host, port))
    transport.connect(username=user, password=password)
    sftp = paramiko.SFTPClient.from_transport(transport)

    # Ensure remote folder exists
    try:
        sftp.stat(remote_dir + 'resources/views/Transaksi/RiwayatServis')
    except IOError:
        sftp.mkdir(remote_dir + 'resources/views/Transaksi/RiwayatServis')
        print("Created remote directory: RiwayatServis")

    for f in files_to_upload:
        print(f"Uploading {f}...")
        sftp.put(f, remote_dir + f)

    print("All files uploaded successfully.")
    sftp.close()
    transport.close()
except Exception as e:
    print(f"Failed: {e}")
