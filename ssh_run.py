import paramiko
import os
import sys

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

php_script = """<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);
$kernel->bootstrap();

use Illuminate\\Support\\Facades\\DB;

try {
    $companies = DB::table('company')->where('JenisUsaha', 'FnB')->get();
    foreach ($companies as $c) {
        $KodePartner = $c->KodePartner;
        
        // Tipe Order
        $countTipe = DB::table('tipeorderresto')->where('RecordOwnerID', $KodePartner)->count();
        if ($countTipe == 0) {
            DB::table('tipeorderresto')->insert([
                ['NamaJenisOrder' => 'Dine In', 'Icon' => '', 'DineIn' => 1, 'RecordOwnerID' => $KodePartner],
                ['NamaJenisOrder' => 'Take Away', 'Icon' => '', 'DineIn' => 0, 'RecordOwnerID' => $KodePartner],
                ['NamaJenisOrder' => 'Gojek', 'Icon' => '', 'DineIn' => 0, 'RecordOwnerID' => $KodePartner],
                ['NamaJenisOrder' => 'Grab', 'Icon' => '', 'DineIn' => 0, 'RecordOwnerID' => $KodePartner]
            ]);
            echo "Inserted Tipe Order for $KodePartner\\n";
        }

        // Kelompok Meja & Meja
        $countMeja = DB::table('meja')->where('RecordOwnerID', $KodePartner)->count();
        if ($countMeja == 0) {
            DB::table('kelompokmeja')->insertOrIgnore([
                'KodeKelompokMeja' => 'KM01',
                'NamaKelompokMeja' => 'Lantai 1',
                'RecordOwnerID' => $KodePartner
            ]);

            DB::table('meja')->insertOrIgnore([
                ['KodeMeja' => 'M01', 'NamaMeja' => 'Meja 1', 'KelompokMeja' => 'KM01', 'RecordOwnerID' => $KodePartner],
                ['KodeMeja' => 'M02', 'NamaMeja' => 'Meja 2', 'KelompokMeja' => 'KM01', 'RecordOwnerID' => $KodePartner],
                ['KodeMeja' => 'M03', 'NamaMeja' => 'Meja 3', 'KelompokMeja' => 'KM01', 'RecordOwnerID' => $KodePartner],
                ['KodeMeja' => 'M04', 'NamaMeja' => 'Meja 4', 'KelompokMeja' => 'KM01', 'RecordOwnerID' => $KodePartner],
                ['KodeMeja' => 'M05', 'NamaMeja' => 'Meja 5', 'KelompokMeja' => 'KM01', 'RecordOwnerID' => $KodePartner],
                ['KodeMeja' => 'M06', 'NamaMeja' => 'Meja 6', 'KelompokMeja' => 'KM01', 'RecordOwnerID' => $KodePartner],
                ['KodeMeja' => 'M07', 'NamaMeja' => 'Meja 7', 'KelompokMeja' => 'KM01', 'RecordOwnerID' => $KodePartner],
                ['KodeMeja' => 'M08', 'NamaMeja' => 'Meja 8', 'KelompokMeja' => 'KM01', 'RecordOwnerID' => $KodePartner],
            ]);
            echo "Inserted Meja for $KodePartner\\n";
        }
    }
    echo "Done updating all FnB companies.\\n";

    // --- ASSIGN PERMISSION 122 (PAKET MEMBER) TO ROLES ---
    $roles = DB::table('roles')->get();
    foreach ($roles as $role) {
        $exists = DB::table('permissionrole')
            ->where('roleid', $role->id)
            ->where('permissionid', 122)
            ->exists();
        if (!$exists) {
            DB::table('permissionrole')->insert([
                'roleid' => $role->id,
                'permissionid' => 122,
                'RecordOwnerID' => isset($role->RecordOwnerID) ? $role->RecordOwnerID : ''
            ]);
        }
    }
    echo "Done assigning permission 122 to roles.\\n";

    // --- ASSIGN PERMISSION 122 (PAKET MEMBER) TO SUBSCRIPTIONS ---
    $subs = DB::table('subscriptionheader')->get();
    foreach ($subs as $sub) {
        $exists = DB::table('subscriptiondetail')
            ->where('NoTransaksi', $sub->NoTransaksi)
            ->where('PermissionID', 122)
            ->exists();
        if (!$exists) {
            $maxUrut = DB::table('subscriptiondetail')
                ->where('NoTransaksi', $sub->NoTransaksi)
                ->max('NoUrut');
            DB::table('subscriptiondetail')->insert([
                'NoTransaksi' => $sub->NoTransaksi,
                'PermissionID' => 122,
                'NoUrut' => ($maxUrut ? $maxUrut + 1 : 1)
            ]);
        }
    }
    echo "Done assigning permission 122 to subscriptions.\\n";

} catch (\\Exception $e) {
    echo "Error: " . $e->getMessage() . "\\n";
}
"""

try:
    print("Connecting to SSH...")
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(host, port, user, password)
    print("Connected!")
    
    # Let's find the project directory. It's usually in /www/wwwroot/pos.dstechsmart.com
    stdin, stdout, stderr = client.exec_command('ls /www/wwwroot/')
    print("wwwroot contents:", stdout.read().decode())
    
    # create the php script in pos.dstechsmart.com
    target_path = '/www/wwwroot/pos.dstechsmart.com/seed_fnb.php'
    sftp = client.open_sftp()
    with sftp.file(target_path, 'w') as f:
        f.write(php_script)
    sftp.close()
    
    print("Executing script...")
    stdin, stdout, stderr = client.exec_command(f'php {target_path}')
    out = stdout.read().decode()
    err = stderr.read().decode()
    print("Output:", out)
    if err:
        print("Error:", err)
    
    # remove script
    client.exec_command(f'rm {target_path}')
    
    client.close()
except Exception as e:
    print("Exception:", str(e))
