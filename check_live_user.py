import paramiko
import os

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

php_script = r"""<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$companies = DB::table('company')->get();
foreach ($companies as $c) {
    if (stripos($c->NamaPartner, 'Gor') !== false || stripos($c->NamaPartner, 'Demo') !== false) {
        echo "Company: {$c->NamaPartner} | KodePartner: {$c->KodePartner} | KodePaketLangganan: {$c->KodePaketLangganan}\n";
        
        // Find users for this partner
        $users = DB::table('users')->where('RecordOwnerID', $c->KodePartner)->get();
        foreach ($users as $u) {
            echo "   User: {$u->name} | Email: {$u->email}\n";
        }
    }
}
"""

try:
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(host, port, user, password)
    
    target_path = '/www/wwwroot/pos.dstechsmart.com/check_gor.php'
    sftp = client.open_sftp()
    with sftp.file(target_path, 'w') as f:
        f.write(php_script)
    sftp.close()
    
    stdin, stdout, stderr = client.exec_command(f'php {target_path}')
    print(stdout.read().decode())
    
    client.exec_command(f'rm {target_path}')
    client.close()
except Exception as e:
    print("Exception:", str(e))
