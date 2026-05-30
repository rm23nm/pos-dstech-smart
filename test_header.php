<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Auth::loginUsingId(81);
$user = Auth::user();

$oCompany = DB::table('company')
    ->select('company.*', 'subscriptionheader.AllowMonitorAntrean', 'subscriptionheader.AllowPesananMeja', 'subscriptionheader.AllowAccounting', 'subscriptionheader.AllowPaymentGateway')
    ->leftJoin('subscriptionheader', 'company.KodePaketLangganan', '=', 'subscriptionheader.NoTransaksi')
    ->where('company.KodePartner', Auth::user()->RecordOwnerID)->get()->toArray();
// convert stdClass to array
$cData = json_decode(json_encode($oCompany), true);

$PermissionID = [];
$oMenu = [];

$oObject = DB::table('userrole')->selectRaw("permission.*")
    ->join("permissionrole", function ($value) {
        $value->on("userrole.roleid","=","permissionrole.roleid")
                ->on("userrole.RecordOwnerID",'=', "permissionrole.RecordOwnerID");
    })
    ->join("permission","permission.id","=","permissionrole.permissionid")
    ->join("users",function($value){
        $value->on("userrole.userid","=","users.id")
                ->on("userrole.RecordOwnerID","=","users.RecordOwnerID");
    })
    ->leftJoin('company','permissionrole.RecordOwnerID', '=', 'company.KodePartner')
    ->join('subscriptiondetail', function ($value){
        $value->on('permission.id','=','subscriptiondetail.PermissionID')
        ->on('subscriptiondetail.NoTransaksi','=','company.KodePaketLangganan');
    })
    ->where("users.id","=",81)
    ->where("permission.Status","=","1")
    ->where("permission.Level","=","1")
    ->where("permission.isSuperAdmin","=","0")
    ->orderBy("permission.Order","asc")
    ->get()->unique('id');

foreach ($oObject as $item) {
    $temp = array();
    $temp['PermissionName'] = $item->PermissionName;
    $temp['Link'] = $item->Link;
    $temp['Icon'] = $item->Icon;
    $temp['ParentType'] = $item->SubMenu;

    $dt2 = DB::table('userrole')->selectRaw("permission.*")
        ->join("permissionrole", function ($value) {
            $value->on("userrole.roleid","=","permissionrole.roleid")
                    ->on("userrole.RecordOwnerID",'=', "permissionrole.RecordOwnerID");
        })
        ->join("permission","permission.id","=","permissionrole.permissionid")
        ->join("users",function($value){
            $value->on("userrole.userid","=","users.id")
                    ->on("userrole.RecordOwnerID","=","users.RecordOwnerID");
        })
        ->leftJoin('company','permissionrole.RecordOwnerID', '=', 'company.KodePartner')
        ->join('subscriptiondetail', function ($value){
            $value->on('permission.id','=','subscriptiondetail.PermissionID')
            ->on('subscriptiondetail.NoTransaksi','=','company.KodePaketLangganan');
        })
        ->where("users.id","=",81)
        ->where("permission.Status","=","1")
        ->where("permission.Level","=","2")
        ->where("permission.MenuInduk","=",$item->id)
        ->where("permission.isSuperAdmin","=","0")
        ->orderBy("permission.Order","asc")
        ->get()->unique('id');

    $array2 = array();
    foreach ($dt2 as $key2) {
        $temp2 = array();
        $temp2['PermissionName'] = $key2->PermissionName;
        $temp2['Link'] = $key2->Link;
        $temp2['Icon'] = $key2->Icon;
        $temp2['ParentType'] = $key2->SubMenu;

        $dt3 = DB::table('userrole')->selectRaw("permission.*")
            ->join("permissionrole", function ($value) {
                $value->on("userrole.roleid","=","permissionrole.roleid")
                        ->on("userrole.RecordOwnerID",'=', "permissionrole.RecordOwnerID");
            })
            ->join("permission","permission.id","=","permissionrole.permissionid")
            ->join("users",function($value){
                $value->on("userrole.userid","=","users.id")
                        ->on("userrole.RecordOwnerID","=","users.RecordOwnerID");
            })
            ->leftJoin('company','permissionrole.RecordOwnerID', '=', 'company.KodePartner')
            ->join('subscriptiondetail', function ($value){
                $value->on('permission.id','=','subscriptiondetail.PermissionID')
                ->on('subscriptiondetail.NoTransaksi','=','company.KodePaketLangganan');
            })
            ->where("users.id","=",81)
            ->where("permission.Status","=","1")
            ->where("permission.Level","=","3")
            ->where("permission.MenuInduk","=",$key2->id)
            ->where("permission.isSuperAdmin","=","0")
            ->orderBy("permission.Order","asc")
            ->get()->unique('id');

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
    'booking' => ['PermissionName' => 'Booking & Reservasi', 'Icon' => 'far fa-calendar-alt', 'submenu' => [], 'ParentType' => 1],
    'display' => ['PermissionName' => 'Layar Antrean & KDS', 'Icon' => 'fas fa-desktop', 'submenu' => [], 'ParentType' => 1],
    'resto' => ['PermissionName' => 'Manajemen Resto', 'Icon' => 'fas fa-utensils', 'submenu' => [], 'ParentType' => 1],
    'inventory' => ['PermissionName' => 'Inventori & Stok', 'Icon' => 'fas fa-boxes', 'submenu' => [], 'ParentType' => 1],
    'consignment' => ['PermissionName' => 'Barang Konsinyasi', 'Icon' => 'fas fa-handshake', 'submenu' => [], 'ParentType' => 1],
    'purchasing' => ['PermissionName' => 'Pembelian & Supplier', 'Icon' => 'fas fa-shopping-cart', 'submenu' => [], 'ParentType' => 1],
    'crm' => ['PermissionName' => 'Mitra Bisnis & CRM', 'Icon' => 'fas fa-users', 'submenu' => [], 'ParentType' => 1],
    'finance' => ['PermissionName' => 'Kas, Bank & Biaya', 'Icon' => 'fas fa-wallet', 'submenu' => [], 'ParentType' => 1],
    'accounting' => ['PermissionName' => 'Akuntansi & COA', 'Icon' => 'fas fa-book', 'submenu' => [], 'ParentType' => 1],
    'reports_sales' => ['PermissionName' => 'Laporan Bisnis & Stok', 'Icon' => 'fas fa-chart-bar', 'submenu' => [], 'ParentType' => 1],
    'reports_accounting' => ['PermissionName' => 'Laporan Keuangan', 'Icon' => 'fas fa-balance-scale', 'submenu' => [], 'ParentType' => 1],
    'system' => ['PermissionName' => 'Sistem & Pengaturan', 'Icon' => 'fas fa-cogs', 'submenu' => [], 'ParentType' => 1],
    'bengkel' => ['PermissionName' => 'Manajemen Bengkel', 'Icon' => 'fas fa-tools', 'submenu' => [], 'ParentType' => 1],
    'dealer' => ['PermissionName' => 'Dealer Kendaraan', 'Icon' => 'fas fa-car-side', 'submenu' => [], 'ParentType' => 1]
];

foreach ($navbars as $lv1) {
    if (!empty($lv1['submenu'])) {
        foreach ($lv1['submenu'] as $lv2) {
            $targetCat = null;
            $l2Name = strtolower(trim($lv2['PermissionName']));

            if ($l2Name === 'bussiness partner') {
                $targetCat = 'crm';
            } elseif ($l2Name === 'manajemen gate') {
                $targetCat = 'billiard';
            } elseif ($l2Name === 'controller') {
                $isSystemController = false;
                if (!empty($lv2['submenu'])) {
                    foreach ($lv2['submenu'] as $keyLv3 => $lv3) {
                        $l3Name = strtolower(trim($lv3['PermissionName']));
                        if (isset($cData[0]['JenisUsaha']) && $cData[0]['JenisUsaha'] === 'TiketGate') {
                            if (str_contains($l3Name, 'lampu') || str_contains($l3Name, 'table order')) {
                                unset($lv2['submenu'][$keyLv3]);
                                continue;
                            }
                        }
                        if (str_contains($l3Name, 'serial number') || str_contains($l3Name, 'generator')) {
                            $isSystemController = true;
                        }
                    }
                }
                if ($isSystemController) {
                    $targetCat = 'system';
                } else {
                    if (isset($cData[0]['JenisUsaha']) && $cData[0]['JenisUsaha'] === 'TiketGate') {
                        continue;
                    }
                    $targetCat = 'billiard';
                }
            } elseif ($l2Name === 'paket') {
                $targetCat = 'billiard';
            } elseif ($l2Name === 'paket member') {
                $targetCat = 'billiard';
            } elseif (in_array($l2Name, ['info kitchen', 'queue antrian fnb', 'monitor counter (recall)', 'antrian fnb dapur', 'queue lapangan', 'customer display pos'])) {
                $targetCat = 'display';
            }

            if ($targetCat && isset($premiumCategories[$targetCat])) {
                $premiumCategories[$targetCat]['submenu'][] = $lv2;
            }
        }
    }
}

$activePremiumCategories = [];
foreach ($premiumCategories as $key => $cat) {
    if (!empty($cat['submenu'])) {
        $activePremiumCategories[$key] = $cat;
    }
}

if (isset($cData[0]['JenisUsaha']) && $cData[0]['JenisUsaha'] == 'FnB') {
    $keysToRemove = ['bengkel', 'dealer'];
    foreach ($keysToRemove as $removeKey) {
        unset($activePremiumCategories[$removeKey]);
    }
}

echo "Active Categories:\n";
print_r(array_keys($activePremiumCategories));
if (isset($activePremiumCategories['billiard'])) {
    echo "\nBilliard Submenus:\n";
    foreach ($activePremiumCategories['billiard']['submenu'] as $sub) {
        echo "- " . $sub['PermissionName'] . "\n";
    }
}
if (isset($activePremiumCategories['display'])) {
    echo "\nDisplay Submenus:\n";
    foreach ($activePremiumCategories['display']['submenu'] as $sub) {
        echo "- " . $sub['PermissionName'] . "\n";
    }
}
