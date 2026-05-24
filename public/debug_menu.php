<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::where('email', 'rm23n@ymail.com')->first();
if ($user) {
    \Illuminate\Support\Facades\Auth::login($user);

    $oObject = \App\Models\UserRole::selectRaw("permission.PermissionName, permission.isSuperAdmin")
                ->Join("permissionrole", function ($value)
                {
                    $value->on("userrole.roleid","=","permissionrole.roleid")
                            ->on("userrole.RecordOwnerID",'=', "permissionrole.RecordOwnerID");
                })
                ->Join("permission","permission.id","=","permissionrole.permissionid")
                ->Join("users",function($value){
                    $value->on("userrole.userid","=","users.id")
                            ->on("userrole.RecordOwnerID","=","users.RecordOwnerID");
                })
                ->leftJoin('company','permissionrole.RecordOwnerID', '=', 'company.KodePartner');

    $oObject->where("users.email","=",\Illuminate\Support\Facades\Auth::user()->email)
            ->where("users.RecordOwnerID","=",\Illuminate\Support\Facades\Auth::user()->RecordOwnerID)
            ->where("permission.Status","=","1")
            ->where("permission.Level","=","1");

    $oObject = $oObject->orderBy("permission.Order","asc")->get();

    echo "Level 1 Menu Items for " . $user->email . ":\n";
    foreach($oObject as $item) {
        echo "- " . $item->PermissionName . " (isSuperAdmin=" . $item->isSuperAdmin . ")\n";
    }
}
