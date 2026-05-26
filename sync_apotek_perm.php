<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$company = DB::table('company')->where('KodePartner', 'demoapotek')->first();
$role = DB::table('roles')->where('RecordOwnerID', 'demoapotek')->first();

if($company && $role) {
    $subsDetails = DB::table('subscriptiondetail')
        ->where('NoTransaksi', $company->KodePaketLangganan)
        ->get();
        
    $count = 0;
    foreach($subsDetails as $sd) {
        // check if exists
        $exists = DB::table('permissionrole')
            ->where('roleid', $role->id)
            ->where('permissionid', $sd->PermissionID)
            ->where('RecordOwnerID', 'demoapotek')
            ->exists();
            
        if(!$exists) {
            DB::table('permissionrole')->insert([
                'roleid' => $role->id,
                'permissionid' => $sd->PermissionID,
                'RecordOwnerID' => 'demoapotek'
            ]);
            $count++;
        }
    }
    echo "Inserted $count permissions for demoapotek.\n";
} else {
    echo "Company or role not found.\n";
}
