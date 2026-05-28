import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

transport = paramiko.Transport((host, port))
transport.connect(username=user, password=password)
sftp = paramiko.SFTPClient.from_transport(transport)

files_to_upload = [
    ('resources/views/parts/header.blade.php', remote_dir + 'resources/views/parts/header.blade.php'),
]

for local, remote in files_to_upload:
    print(f"Uploading {local}...")
    sftp.put(local, remote)

sftp.close()
transport.close()
print("Done!")
