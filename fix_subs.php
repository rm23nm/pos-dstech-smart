<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$c = DB::table('company')->first();
$pkg = $c->KodePaketLangganan ?? '';
$roid = $c->KodePartner ?? $c->RecordOwnerID ?? 'CL0013';

// Permission IDs related to Controller and Lampu
$perms = [88, 89, 90, 91, 99];

foreach($perms as $p) {
    // Add to subscription so it appears in the Role form
    $exists1 = DB::table('subscriptiondetail')->where('NoTransaksi', $pkg)->where('PermissionID', $p)->first();
    if(!$exists1) {
        $maxNoUrut = DB::table('subscriptiondetail')->where('NoTransaksi', $pkg)->max('NoUrut');
        $nextNoUrut = $maxNoUrut ? $maxNoUrut + 1 : 1;
        
        DB::table('subscriptiondetail')->insert([
            'NoTransaksi' => $pkg, 
            'PermissionID' => $p,
            'NoUrut' => $nextNoUrut
        ]);
    }
    
    // Add directly to Role 68 (Admin) so it works immediately without manual save
    $exists2 = DB::table('permissionrole')->where('roleid', 68)->where('permissionid', $p)->where('RecordOwnerID', $roid)->first();
    if(!$exists2) {
        DB::table('permissionrole')->insert([
            'roleid' => 68,
            'permissionid' => $p,
            'RecordOwnerID' => $roid
        ]);
    }
}
echo 'Sukses membuka gembok Hak Akses IoT untuk Paket Langganan: ' . ($pkg === '' ? '(KOSONG)' : $pkg) . ' dan Role ID: 68';
