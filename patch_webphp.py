import paramiko
import re

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_file = '/www/wwwroot/pos.dstechsmart.com/routes/web.php'
local_backup = 'routes/web.php.live_backup'
local_file = 'routes/web.php'

transport = paramiko.Transport((host, port))
transport.connect(username=user, password=password)
sftp = paramiko.SFTPClient.from_transport(transport)

# Download live version as backup
print("Downloading live web.php as backup...")
sftp.get(remote_file, local_backup)

with open(local_backup, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Add use statement after QueueBengkelController
old_use = "use App\\Http\\Controllers\\QueueBengkelController;"
new_use = "use App\\Http\\Controllers\\QueueBengkelController;\nuse App\\Http\\Controllers\\RiwayatServisController;"

if 'RiwayatServisController' not in content:
    content = content.replace(old_use, new_use)
    print("Added use RiwayatServisController")
else:
    print("use RiwayatServisController already exists")

# 2. Add routes after queue-bengkel routes
old_route = "Route::post('/queue-bengkel/updateStatus', [QueueBengkelController::class, 'updateStatus'])->name('queue-bengkel-updateStatus');"
new_route = """Route::post('/queue-bengkel/updateStatus', [QueueBengkelController::class, 'updateStatus'])->name('queue-bengkel-updateStatus');

// Riwayat Servis Kendaraan
Route::get('/riwayat-servis', [RiwayatServisController::class, 'index'])->name('riwayat-servis')->middleware(['auth', 'check.session']);
Route::post('/riwayat-servis/getData', [RiwayatServisController::class, 'getData'])->name('riwayat-servis-getData')->middleware(['auth', 'check.session']);"""

if 'riwayat-servis' not in content:
    content = content.replace(old_route, new_route)
    print("Added riwayat-servis routes")
else:
    print("riwayat-servis routes already exist")

# Save modified file locally
with open(local_file, 'w', encoding='utf-8') as f:
    f.write(content)

# Upload back
print("Uploading modified web.php to live...")
sftp.put(local_file, remote_file)
print("Done!")

sftp.close()
transport.close()
