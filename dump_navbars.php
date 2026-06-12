<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;

// Mock user login
Auth::loginUsingId(1); // SuperAdmin

$oCompany = DB::table('company')
    ->where('KodePartner', Auth::user()->RecordOwnerID)
    ->get()->toArray();

$PermissionID = [];

$dt1 = UserRole::selectRaw("permission.*")
    ->Join("permissionrole", function ($value) {
        $value->on("userrole.roleid","=","permissionrole.roleid")
                ->on("userrole.RecordOwnerID",'=', "permissionrole.RecordOwnerID");
    })
    ->Join("permission","permission.id","=","permissionrole.permissionid")
    ->Join("users",function($value){
        $value->on("userrole.userid","=","users.id")
                ->on("userrole.RecordOwnerID","=","users.RecordOwnerID");
    })
    ->where("users.email","=",Auth::user()->email)
    ->where("permission.MenuInduk","=","0")
    ->where("permission.Status","=","1")
    ->orderBy("permission.Order","asc")->get();

$array1 = array();
foreach ($dt1 as $key) {
    $temp = array();
    $temp['PermissionName'] = $key->PermissionName;
    $temp['Link'] = $key->Link;
    $temp['Icon'] = $key->Icon;
    $temp['ParentType'] = $key->SubMenu;

    $dt2 = UserRole::selectRaw("permission.*")
        ->Join("permissionrole", function ($value) {
            $value->on("userrole.roleid","=","permissionrole.roleid")
                    ->on("userrole.RecordOwnerID",'=', "permissionrole.RecordOwnerID");
        })
        ->Join("permission","permission.id","=","permissionrole.permissionid")
        ->Join("users",function($value){
            $value->on("userrole.userid","=","users.id")
                    ->on("userrole.RecordOwnerID","=","users.RecordOwnerID");
        })
        ->where("users.email","=",Auth::user()->email)
        ->where("permission.Level","=","2")
        ->where("permission.MenuInduk","=",$key->id)
        ->orderBy("permission.Order","asc")->get();

    $array2 = array();
    foreach ($dt2 as $key2) {
        $temp2 = array();
        $temp2['PermissionName'] = $key2->PermissionName;
        $temp2['Link'] = $key2->Link;
        $temp2['Icon'] = $key2->Icon;
        $temp2['ParentType'] = $key2->SubMenu;

        $array2[] = $temp2;
    }
    $temp['submenu'] = $array2;
    $array1[] = $temp;
}

foreach ($array1 as $lv1) {
    if ($lv1['PermissionName'] == 'Management Attendance') {
        echo json_encode($lv1, JSON_PRETTY_PRINT);
    }
}
