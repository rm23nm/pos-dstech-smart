<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Auth::loginUsingId(81);
$user = Auth::user();

// [Copying most logic from previous script to test level 3 output]
$oCompany = DB::table('company')->where('KodePartner', Auth::user()->RecordOwnerID)->get();
$cData = json_decode(json_encode($oCompany), true);
$oMenu = [];
// I'll just use the exact logic from AppServiceProvider instead of writing it all again
// Since I know AppServiceProvider works, I'll just load the menus using it.
$oObject = App\Models\UserRole::selectRaw("permission.*")
            ->Join("permissionrole", function ($value) {
                $value->on("userrole.roleid","=","permissionrole.roleid")
                        ->on("userrole.RecordOwnerID",'=', "permissionrole.RecordOwnerID");
            })
            ->Join("permission","permission.id","=","permissionrole.permissionid")
            ->Join("users",function($value){
                $value->on("userrole.userid","=","users.id")
                        ->on("userrole.RecordOwnerID","=","users.RecordOwnerID");
            })
            ->leftJoin('company','permissionrole.RecordOwnerID', 'company.KodePartner')
            ->Join('subscriptiondetail', function ($value){
                $value->on('permission.id','=','subscriptiondetail.PermissionID')
                ->on('subscriptiondetail.NoTransaksi','=','company.KodePaketLangganan');
            })
            ->where("users.id","=",81)
            ->where("permission.Status","=","1")
            ->where("permission.Level","=","1")
            ->where("permission.isSuperAdmin","=","0")
            ->orderBy("permission.Order","asc")->get();

foreach ($oObject as $item) {
    $temp = array();
    $temp['PermissionName'] = $item->PermissionName;
    $temp['Link'] = $item->Link;
    $temp['Icon'] = $item->Icon;
    $temp['ParentType'] = $item->SubMenu;
    $dt2 = App\Models\UserRole::selectRaw("permission.*")
            ->Join("permissionrole", function ($value) {
                $value->on("userrole.roleid","=","permissionrole.roleid")
                        ->on("userrole.RecordOwnerID",'=', "permissionrole.RecordOwnerID");
            })
            ->Join("permission","permission.id","=","permissionrole.permissionid")
            ->Join("users",function($value){
                $value->on("userrole.userid","=","users.id")
                        ->on("userrole.RecordOwnerID","=","users.RecordOwnerID");
            })
            ->leftJoin('company','permissionrole.RecordOwnerID', 'company.KodePartner')
            ->Join('subscriptiondetail', function ($value){
                $value->on('permission.id','=','subscriptiondetail.PermissionID')
                ->on('subscriptiondetail.NoTransaksi','=','company.KodePaketLangganan');
            })
            ->where("users.id","=",81)
            ->where("permission.Status","=","1")
            ->where("permission.Level","=","2")
            ->where("permission.MenuInduk","=",$item->id)
            ->where("permission.isSuperAdmin","=","0")
            ->orderBy("permission.Order","asc")->get();
    $array2 = array();
    foreach ($dt2 as $key2) {
        $temp2 = array();
        $temp2['PermissionName'] = $key2->PermissionName;
        $temp2['Link'] = $key2->Link;
        $temp2['Icon'] = $key2->Icon;
        $temp2['ParentType'] = $key2->SubMenu;
        $dt3 = App\Models\UserRole::selectRaw("permission.*")
                ->Join("permissionrole", function ($value) {
                    $value->on("userrole.roleid","=","permissionrole.roleid")
                            ->on("userrole.RecordOwnerID",'=', "permissionrole.RecordOwnerID");
                })
                ->Join("permission","permission.id","=","permissionrole.permissionid")
                ->Join("users",function($value){
                    $value->on("userrole.userid","=","users.id")
                            ->on("userrole.RecordOwnerID","=","users.RecordOwnerID");
                })
                ->leftJoin('company','permissionrole.RecordOwnerID', 'company.KodePartner')
                ->Join('subscriptiondetail', function ($value){
                    $value->on('permission.id','=','subscriptiondetail.PermissionID')
                    ->on('subscriptiondetail.NoTransaksi','=','company.KodePaketLangganan');
                })
                ->where("users.id","=",81)
                ->where("permission.Status","=","1")
                ->where("permission.Level","=","3")
                ->where("permission.MenuInduk","=",$key2->id)
                ->where("permission.isSuperAdmin","=","0")
                ->orderBy("permission.Order","asc")->get();
        $array3 = array();
        foreach ($dt3 as $key3) {
            $temp3 = array();
            $temp3['PermissionName'] = $key3->PermissionName;
            $temp3['Link'] = $key3->Link;
            $temp3['Icon'] = $key3->Icon;
            $temp3['ParentType'] = $key3->SubMenu;
            array_push($array3, $temp3);
        }
        $temp2['submenu'] = $array3;
        array_push($array2, $temp2);
    }
    $temp['submenu'] = $array2;
    array_push($oMenu, $temp);
}

$navbars = $oMenu;

$premiumCategories = [
    'pos' => ['PermissionName' => 'Operasional POS Kasir', 'Icon' => 'fas fa-cash-register', 'submenu' => [], 'ParentType' => 1],
    'billiard' => ['PermissionName' => 'Sewa Billing & IoT', 'Icon' => 'fas fa-lightbulb', 'submenu' => [], 'ParentType' => 1],
    'display' => ['PermissionName' => 'Layar Antrean & KDS', 'Icon' => 'fas fa-desktop', 'submenu' => [], 'ParentType' => 1],
    'system' => ['PermissionName' => 'Sistem & Pengaturan', 'Icon' => 'fas fa-cogs', 'submenu' => [], 'ParentType' => 1],
];

foreach ($navbars as $lv1) {
    if (!empty($lv1['submenu'])) {
        foreach ($lv1['submenu'] as $lv2) {
            $targetCat = null;
            $l2Name = strtolower(trim($lv2['PermissionName']));

            if ($l2Name === 'manajemen gate') {
                $targetCat = 'billiard';
            } elseif ($l2Name === 'controller') {
                $isSystemController = false;
                if (!empty($lv2['submenu'])) {
                    foreach ($lv2['submenu'] as $keyLv3 => $lv3) {
                        $l3Name = strtolower(trim($lv3['PermissionName']));
                        if (str_contains($l3Name, 'serial number') || str_contains($l3Name, 'generator')) {
                            $isSystemController = true;
                        }
                    }
                }
                if ($isSystemController) {
                    $targetCat = 'system';
                } else {
                    $targetCat = 'billiard';
                }
            }

            if ($targetCat && isset($premiumCategories[$targetCat])) {
                $premiumCategories[$targetCat]['submenu'][] = $lv2;
            }
        }
    }
}

if (isset($premiumCategories['billiard'])) {
    echo "\nBilliard Submenus:\n";
    foreach ($premiumCategories['billiard']['submenu'] as $sub) {
        echo "- " . $sub['PermissionName'] . "\n";
        if (isset($sub['submenu'])) {
            foreach ($sub['submenu'] as $s) echo "  -- " . $s['PermissionName'] . "\n";
        }
    }
}

