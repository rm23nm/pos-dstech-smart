<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$user = DB::select("SELECT * FROM users WHERE email='admin@apotek.com'");
$company = DB::select("SELECT * FROM company WHERE KodePartner='APO-01'");
$subs = DB::select("SELECT * FROM subscriptionheader WHERE NoTransaksi='PKT-APOTEK-1'");
$subsDetail = DB::select("SELECT count(*) as count FROM subscriptiondetail WHERE NoTransaksi='PKT-APOTEK-1'");
$roles = DB::select("SELECT * FROM roles WHERE RecordOwnerID='APO-01'");
$roleDetail = [];
if(count($roles) > 0) {
    $roleDetail = DB::select("SELECT count(*) as count FROM permissionrole WHERE RecordOwnerID='APO-01' AND roleid=".$roles[0]->id);
}

echo "USER:\n"; print_r($user);
echo "\nCOMPANY:\n"; print_r($company);
echo "\nSUBSCRIPTION:\n"; print_r($subs);
echo "\nSUBSCRIPTION DETAIL COUNT:\n"; print_r($subsDetail);
echo "\nROLES:\n"; print_r($roles);
echo "\nROLE DETAIL COUNT:\n"; print_r($roleDetail);
