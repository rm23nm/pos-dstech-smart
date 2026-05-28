import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'
remote_dir = '/www/wwwroot/pos.dstechsmart.com/'

# PHP script to check the company data directly
php_check = r"""<?php
use Illuminate\Support\Facades\DB;

$kode = 'DEMO-BENGKEL-001';

// Check company record
$company = DB::table('company')->where('KodePartner', $kode)->first();
if ($company) {
    echo "Company FOUND:" . PHP_EOL;
    echo "  KodePartner: " . $company->KodePartner . PHP_EOL;
    echo "  NamaPartner: " . $company->NamaPartner . PHP_EOL;
    echo "  JenisUsaha:  " . $company->JenisUsaha . PHP_EOL;
    echo "  isActive:    " . $company->isActive . PHP_EOL;
} else {
    echo "Company NOT FOUND for KodePartner: " . $kode . PHP_EOL;
}

// Check user
$user = DB::table('users')->where('email', 'demobengkel@pos.dstechsmart.com')->first();
if ($user) {
    echo PHP_EOL . "User FOUND:" . PHP_EOL;
    echo "  email:         " . $user->email . PHP_EOL;
    echo "  RecordOwnerID: " . $user->RecordOwnerID . PHP_EOL;
    echo "  Active:        " . $user->Active . PHP_EOL;
    echo "  isConfirmed:   " . $user->isConfirmed . PHP_EOL;
} else {
    echo "User NOT FOUND!" . PHP_EOL;
}
"""

transport = paramiko.Transport((host, port))
transport.connect(username=user, password=password)
sftp = paramiko.SFTPClient.from_transport(transport)
with sftp.open(remote_dir + 'check_demo_bengkel.php', 'w') as f:
    f.write(php_check)
sftp.close()
transport.close()

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(hostname=host, port=port, username=user, password=password)
cmd = f"cd {remote_dir} && php artisan tinker --execute=\"require 'check_demo_bengkel.php';\""
stdin, stdout, stderr = ssh.exec_command(cmd)
out = stdout.read().decode()
print(out)
err = stderr.read().decode()
if err:
    print("Errors:", err[:500])
ssh.close()
