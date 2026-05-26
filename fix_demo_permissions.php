<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$sourceRecordOwner = 'DEMORETAIL';
$targetRecordOwners = ['DEMOGATE', 'DEMOTIKET'];

try {
    DB::beginTransaction();

    foreach ($targetRecordOwners as $targetRecordOwner) {
        // Find SuperAdmin role for source
        $sourceRole = DB::table('roles')->where('RoleName', 'SuperAdmin')->where('RecordOwnerID', $sourceRecordOwner)->first();
        if (!$sourceRole) {
            echo "Source role not found for $sourceRecordOwner\n";
            continue;
        }

        // Find SuperAdmin role for target
        $targetRole = DB::table('roles')->where('RoleName', 'SuperAdmin')->where('RecordOwnerID', $targetRecordOwner)->first();
        if (!$targetRole) {
            echo "Target role not found for $targetRecordOwner\n";
            continue;
        }

        // Get permissions of source role
        $sourcePermissions = DB::table('role_has_permissions')->where('role_id', $sourceRole->id)->get();
        
        // Delete existing permissions for target role
        DB::table('role_has_permissions')->where('role_id', $targetRole->id)->delete();

        // Insert copied permissions
        $permissionsToInsert = [];
        foreach ($sourcePermissions as $sp) {
            $permissionsToInsert[] = [
                'permission_id' => $sp->permission_id,
                'role_id' => $targetRole->id
            ];
        }

        if (count($permissionsToInsert) > 0) {
            DB::table('role_has_permissions')->insert($permissionsToInsert);
            echo "Copied " . count($permissionsToInsert) . " permissions to $targetRecordOwner SuperAdmin.\n";
        }
        
        // Let's also check if they need access to Ticketing menus. 
        // We'll give them all permissions that exist in the system just in case.
        $allPermissions = DB::table('permissions')->get();
        $missingPermissions = [];
        $existingPermIds = array_column($permissionsToInsert, 'permission_id');
        
        foreach ($allPermissions as $ap) {
            if (!in_array($ap->id, $existingPermIds)) {
                $missingPermissions[] = [
                    'permission_id' => $ap->id,
                    'role_id' => $targetRole->id
                ];
            }
        }
        
        if (count($missingPermissions) > 0) {
            DB::table('role_has_permissions')->insert($missingPermissions);
            echo "Added " . count($missingPermissions) . " extra permissions to $targetRecordOwner SuperAdmin.\n";
        }
    }

    DB::commit();
    echo "Done copying permissions.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
