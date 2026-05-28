import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

files_to_upload = [
    ('resources/views/auth/login.blade.php', '/www/wwwroot/pos.dstechsmart.com/resources/views/auth/login.blade.php')
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
except Exception as e:
    print(f"Connection failed: {e}")
