<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

// Ambil semua roles yang sudah punya permission 113 (Info Kitchen)
$rolesWithKitchen = DB::table('permissionrole')
    ->where('permissionid', 113)
    ->get();

echo "Total roles dengan Info Kitchen: " . count($rolesWithKitchen) . "\n\n";

$newPermIds = [114, 115, 116]; // Display, Monitor Antrean, Monitor Counter
$inserted = 0;
$skipped = 0;

foreach ($rolesWithKitchen as $rp) {
    foreach ($newPermIds as $pid) {
        $exists = DB::table('permissionrole')
            ->where('roleid', $rp->roleid)
            ->where('permissionid', $pid)
            ->exists();
        
        if (!$exists) {
            DB::table('permissionrole')->insert([
                'roleid'        => $rp->roleid,
                'permissionid'  => $pid,
                'RecordOwnerID' => $rp->RecordOwnerID,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
            echo "Added: roleid={$rp->roleid} ({$rp->RecordOwnerID}) -> permissionid={$pid}\n";
            $inserted++;
        } else {
            $skipped++;
        }
    }
}

echo "\n=== Selesai: {$inserted} ditambahkan, {$skipped} sudah ada ===\n";
