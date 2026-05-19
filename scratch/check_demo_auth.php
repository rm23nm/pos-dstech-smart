<?php
use Illuminate\Support\Facades\DB;
use App\Models\User;

define('LARAVEL_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== AUTH DIAGNOSTIC FOR DEMO RETAIL (CL0014) ===\n";

// 1. Check Company
$company = DB::table('company')->where('KodePartner', 'CL0014')->first();
if ($company) {
    echo "Company CL0014 found:\n";
    echo "  NamaPartner: " . $company->NamaPartner . "\n";
    echo "  KodePaketLangganan: '" . $company->KodePaketLangganan . "'\n";
    echo "  isActive: " . $company->isActive . "\n";
    echo "  JenisUsaha: " . $company->JenisUsaha . "\n";
} else {
    echo "Company CL0014 NOT found!\n";
}

// 2. Check User
$user = DB::table('users')->where('email', 'demoretail@pos.dstechsmart.com')->first();
if ($user) {
    echo "User demoretail@pos.dstechsmart.com found:\n";
    echo "  ID: " . $user->id . "\n";
    echo "  name: " . $user->name . "\n";
    echo "  RecordOwnerID: " . $user->RecordOwnerID . "\n";
} else {
    echo "User demoretail NOT found!\n";
}

// 3. Check User Roles
if ($user) {
    $userRoles = DB::table('userrole')->where('userid', $user->id)->where('RecordOwnerID', 'CL0014')->get();
    echo "UserRoles for userid " . $user->id . " (CL0014): " . count($userRoles) . " found.\n";
    foreach ($userRoles as $ur) {
        echo "  RoleID: " . $ur->roleid . "\n";
    }
}

// 4. Check Roles in CL0014
$roles = DB::table('roles')->where('RecordOwnerID', 'CL0014')->get();
echo "Roles for CL0014: " . count($roles) . " found.\n";
foreach ($roles as $r) {
    echo "  ID: " . $r->id . ", RoleName: " . $r->RoleName . "\n";
}

// 5. Check PermissionRole in CL0014
$pr = DB::table('permissionrole')->where('RecordOwnerID', 'CL0014')->get();
echo "PermissionRole count for CL0014: " . count($pr) . "\n";

// 6. Check Subscription Package details
if ($company && $company->KodePaketLangganan) {
    $sd = DB::table('subscriptiondetail')->where('NoTransaksi', $company->KodePaketLangganan)->get();
    echo "SubscriptionDetail count for Package '" . $company->KodePaketLangganan . "': " . count($sd) . "\n";
} else {
    echo "No subscription package set for company!\n";
}
