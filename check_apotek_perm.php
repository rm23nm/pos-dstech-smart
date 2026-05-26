<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
echo "PermissionRoles demoapotek: " . DB::table('permissionrole')->where('RecordOwnerID','demoapotek')->count() . "\n";
$role = DB::table('roles')->where('RecordOwnerID','demoapotek')->first();
if($role) {
    echo "PermissionRoles demoapotek (by roleid): " . DB::table('permissionrole')->where('roleid',$role->id)->count() . "\n";
}
