<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $sourceId = 1;
    $targetIds = [74, 79];
    $perms = DB::table('permissionrole')->where('roleid', $sourceId)->get();
    
    DB::beginTransaction();
    foreach ($targetIds as $tid) {
        DB::table('permissionrole')->where('roleid', $tid)->delete();
        
        $inserts = [];
        $recordOwner = ($tid == 74) ? 'DEMOTIKET' : 'DEMOGATE';
        foreach ($perms as $p) {
            $inserts[] = [
                'permissionid' => $p->permissionid,
                'roleid' => $tid,
                'RecordOwnerID' => $recordOwner
            ];
        }
        
        if (count($inserts) > 0) {
            DB::table('permissionrole')->insert($inserts);
        }
        echo "Copied ".count($perms)." permissions to role ".$tid."\n";
    }
    DB::commit();
    echo "Done!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
