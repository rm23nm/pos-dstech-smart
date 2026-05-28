import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

transport = paramiko.Transport((host, port))
transport.connect(username=user, password=password)
sftp = paramiko.SFTPClient.from_transport(transport)

files = [
    'resources/views/parts/header.blade.php'
]

for f in files:
    print("Uploading " + f)
    sftp.put(f, remote_dir + f)

sftp.close()
print("Done!")
