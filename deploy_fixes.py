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
    ('fix_item_active.php', remote_dir + 'fix_item_active.php'),
    ('resources/views/Transaksi/RiwayatServis/index.blade.php', remote_dir + 'resources/views/Transaksi/RiwayatServis/index.blade.php'),
]

for local, remote in files_to_upload:
    print(f"Uploading {local}...")
    sftp.put(local, remote)

sftp.close()
transport.close()

# Run fix on live
ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(hostname=host, port=port, username=user, password=password)

cmd = f"cd {remote_dir} && php artisan tinker --execute=\"require base_path('fix_item_active.php');\""
stdin, stdout, stderr = ssh.exec_command(cmd)
out = stdout.read().decode()
print("Output:", out)
err = stderr.read().decode()
if err:
    print("Errors:", err[:300])

ssh.close()
print("All done!")
