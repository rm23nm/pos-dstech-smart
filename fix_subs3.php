<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$c = DB::table('company')->where('KodePartner', 'CL0013')->first();
$pkg = $c->KodePaketLangganan;
$roid = 'CL0013';

// Permission IDs related to Displays (Parent 114 + children 113, 116, 117, 118, 119)
$perms = [113, 114, 116, 117, 118, 119];

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
echo 'Sukses menyuntikkan Hak Akses Display (Antrean, Kitchen, Counter) untuk Paket Langganan ' . $pkg . ' (CL0013) dan Role ID: 68';
