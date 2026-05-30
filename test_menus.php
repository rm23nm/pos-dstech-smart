<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Auth::loginUsingId(81);
$user = Auth::user();

$menus = DB::table('userrole')
    ->selectRaw('permission.*')
    ->join('permissionrole', function($join) {
        $join->on('userrole.roleid', '=', 'permissionrole.roleid')
             ->on('userrole.RecordOwnerID', '=', 'permissionrole.RecordOwnerID');
    })
    ->join('permission', 'permission.id', '=', 'permissionrole.permissionid')
    ->join('users', function($join) {
        $join->on('userrole.userid', '=', 'users.id')
             ->on('userrole.RecordOwnerID', '=', 'users.RecordOwnerID');
    })
    ->join('subscriptiondetail', 'permission.id', '=', 'subscriptiondetail.PermissionID')
    ->join('company', 'subscriptiondetail.NoTransaksi', '=', 'company.KodePaketLangganan')
    ->where('users.id', 81)
    ->where('permission.Level', 2)
    ->where('permission.MenuInduk', 1)
    ->get();

print_r($menus->pluck('PermissionName')->toArray());
