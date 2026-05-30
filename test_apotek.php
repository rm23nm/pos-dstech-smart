<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Auth::loginUsingId(83);
$user = Auth::user();

$oCompany = DB::table('company')->where('KodePartner', Auth::user()->RecordOwnerID)->get();
$cData = json_decode(json_encode($oCompany), true);
$oMenu = [];

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
            ->where("users.id","=",83)
            ->where("permission.Status","=","1")
            ->where("permission.Level","=","1")
            ->where("permission.isSuperAdmin","=","0")
            ->orderBy("permission.Order","asc")->get()->unique('id');

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
            ->where("users.id","=",83)
            ->where("permission.Status","=","1")
            ->where("permission.Level","=","2")
            ->where("permission.MenuInduk","=",$item->id)
            ->where("permission.isSuperAdmin","=","0")
            ->orderBy("permission.Order","asc")->get()->unique('id');
    $array2 = array();
    foreach ($dt2 as $key2) {
        $temp2 = array();
        $temp2['PermissionName'] = $key2->PermissionName;
        $temp2['Link'] = $key2->Link;
        $temp2['Icon'] = $key2->Icon;
        $temp2['ParentType'] = $key2->SubMenu;
        $array3 = array();
        $temp2['submenu'] = $array3;
        array_push($array2, $temp2);
    }
    $temp['submenu'] = $array2;
    array_push($oMenu, $temp);
}

$navbars = $oMenu;

$premiumCategories = [
    'pos' => ['PermissionName' => 'Operasional POS Kasir', 'Icon' => 'fas fa-cash-register', 'submenu' => [], 'ParentType' => 1],
    'display' => ['PermissionName' => 'Layar Antrean & KDS', 'Icon' => 'fas fa-desktop', 'submenu' => [], 'ParentType' => 1],
];

foreach ($navbars as $lv1) {
    if (!empty($lv1['submenu'])) {
        foreach ($lv1['submenu'] as $lv2) {
            $targetCat = null;
            $l2Name = strtolower(trim($lv2['PermissionName']));

            if (in_array($l2Name, ['info kitchen', 'queue antrian fnb', 'monitor counter (recall)', 'antrian fnb dapur', 'queue lapangan', 'customer display pos'])) {
                $targetCat = 'display';
            }

            if ($targetCat && isset($premiumCategories[$targetCat])) {
                $premiumCategories[$targetCat]['submenu'][] = $lv2;
            }
        }
    }
}

// Apotek translation logic
$displayItems = isset($premiumCategories['display']['submenu']) ? $premiumCategories['display']['submenu'] : [];
$apotekItems = [];
$fnbItems = [];
$otherDisplayItems = [];
$jenisUsaha = isset($cData[0]['JenisUsaha']) ? $cData[0]['JenisUsaha'] : '';

foreach ($displayItems as $item) {
    $name = strtolower(trim($item['PermissionName']));
    if (in_array($name, ['info kitchen', 'queue antrian fnb', 'monitor counter (recall)', 'antrian fnb dapur', 'queue lapangan'])) {
        if ($jenisUsaha == 'Apotek' || $jenisUsaha == 'Klinik') {
            if ($name == 'info kitchen') {
                $item['PermissionName'] = 'Monitor Peracikan Obat';
                $item['Link'] = 'infoperacikan';
                $apotekItems[] = $item;
            } elseif ($name == 'queue antrian fnb') {
                $item['PermissionName'] = 'Antrean Pengambilan Obat';
                $item['Link'] = 'queue-apotek';
                $apotekItems[] = $item;
            }
        } else {
            $fnbItems[] = $item;
        }
    } else {
        $otherDisplayItems[] = $item;
    }
}

$newDisplaySubmenu = [];
if (count($fnbItems) > 0 && $jenisUsaha != 'Apotek' && $jenisUsaha != 'Klinik') {
    foreach ($fnbItems as $fnb) {
        $newDisplaySubmenu[] = $fnb;
    }
}
if (count($apotekItems) > 0 && ($jenisUsaha == 'Apotek' || $jenisUsaha == 'Klinik')) {
    foreach ($apotekItems as $apt) {
        $newDisplaySubmenu[] = $apt;
    }
}
foreach ($otherDisplayItems as $item) {
    $newDisplaySubmenu[] = $item;
}
$premiumCategories['display']['submenu'] = $newDisplaySubmenu;

echo "Display Menus:\n";
foreach ($premiumCategories['display']['submenu'] as $sub) {
    echo "- " . $sub['PermissionName'] . " (" . $sub['Link'] . ")\n";
}
