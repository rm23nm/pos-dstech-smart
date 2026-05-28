<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::statement("SET sql_mode = '';");
    
    // Get the dealer role id
    $role = DB::table('roles')->where('RecordOwnerID', 'DEALER-001')->first();
    if (!$role) {
        die("Dealer role not found\n");
    }
    
    // Get all permissions that normally exist for a retail/full account
    $permissions = DB::table('permission')->get();
    
    $inserts = [];
    foreach ($permissions as $perm) {
        // Check if already exists to prevent duplicate entries
        $exists = DB::table('permissionrole')
            ->where('roleid', $role->id)
            ->where('permissionid', $perm->id)
            ->where('RecordOwnerID', 'DEALER-001')
            ->exists();
            
        if (!$exists) {
            $inserts[] = [
                'roleid' => $role->id,
                'permissionid' => $perm->id,
                'RecordOwnerID' => 'DEALER-001'
            ];
        }
    }
    
    if (count($inserts) > 0) {
        DB::table('permissionrole')->insert($inserts);
        echo "Granted " . count($inserts) . " permissions to Dealer role.\n";
    } else {
        echo "Permissions already granted.\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
