<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Sinkronisasi Subscription Detail...\n";

try {
    DB::beginTransaction();

    $newIds = [119, 136, 137, 138, 139, 140, 141];
    
    $subscriptions = DB::table('subscriptiondetail')
        ->where('PermissionID', 114)
        ->select('NoTransaksi')
        ->distinct()
        ->get();

    $inserted = 0;

    foreach ($subscriptions as $sub) {
        $noTransaksi = $sub->NoTransaksi;

        // Get max NoUrut
        $maxUrut = DB::table('subscriptiondetail')
            ->where('NoTransaksi', $noTransaksi)
            ->max('NoUrut');

        foreach ($newIds as $pid) {
            $exists = DB::table('subscriptiondetail')
                ->where('NoTransaksi', $noTransaksi)
                ->where('PermissionID', $pid)
                ->exists();

            if (!$exists) {
                $maxUrut++;
                DB::table('subscriptiondetail')->insert([
                    'NoTransaksi' => $noTransaksi,
                    'NoUrut' => $maxUrut,
                    'PermissionID' => $pid,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $inserted++;
            }
        }
    }

    // Also sync the roles so SuperAdmin gets these permissions automatically
    // The user might be logging in as SuperAdmin or Admin and couldn't see it
    $roles = DB::table('permissionrole')
        ->where('permissionid', 114)
        ->select('roleid', 'RecordOwnerID')
        ->distinct()
        ->get();

    foreach ($roles as $role) {
        foreach ($newIds as $pid) {
            $exists = DB::table('permissionrole')
                ->where('roleid', $role->roleid)
                ->where('RecordOwnerID', $role->RecordOwnerID)
                ->where('permissionid', $pid)
                ->exists();

            if (!$exists) {
                DB::table('permissionrole')->insert([
                    'roleid' => $role->roleid,
                    'RecordOwnerID' => $role->RecordOwnerID,
                    'permissionid' => $pid,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    DB::commit();
    echo "Selesai! $inserted detail berlangganan dan roles berhasil disinkronkan.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
