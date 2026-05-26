<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::beginTransaction();

    $targetIds = [74, 79];
    foreach ($targetIds as $tid) {
        $distinctPerms = DB::table('permissionrole')->where('roleid', $tid)->distinct()->pluck('permissionid');
        $recordOwner = ($tid == 74) ? 'DEMOTIKET' : 'DEMOGATE';
        DB::table('permissionrole')->where('roleid', $tid)->delete();
        $inserts = [];
        foreach ($distinctPerms as $pid) {
            $inserts[] = [
                'permissionid' => $pid,
                'roleid' => $tid,
                'RecordOwnerID' => $recordOwner
            ];
        }
        if(count($inserts) > 0) {
            DB::table('permissionrole')->insert($inserts);
        }
        echo "Role ".$tid." now has ".count($inserts)." distinct permissions.\n";
    }

    DB::commit();
    echo "Done!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
