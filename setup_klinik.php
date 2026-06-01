<?php
use Illuminate\Support\Facades\DB;
try {
$company = DB::table('company')->where('KodePartner', 'demoklinik')->first();
$kodePaket = $company->KodePaketLangganan;

$allPerms = DB::table('permission')->get();
$noUrut = 1;
foreach ($allPerms as $p) {
    $exists = DB::table('subscriptiondetail')->where('NoTransaksi', $kodePaket)->where('PermissionID', $p->id)->exists();
    if (!$exists) {
        DB::table('subscriptiondetail')->insert([
            'NoTransaksi' => $kodePaket,
            'PermissionID' => $p->id,
            'NoUrut' => $noUrut
        ]);
    }
    $noUrut++;
}
echo "Assigned all permissions to subscription $kodePaket\n";
} catch (\Exception $e) {
    echo $e->getMessage();
}
