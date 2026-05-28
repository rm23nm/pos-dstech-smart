<?php
use Illuminate\Support\Facades\DB;

try {
    $permissionId = DB::table('permission')->where('PermissionName', 'Laporan Komisi Mekanik')->value('id');

    if ($permissionId) {
        // Get unique NoTransaksi (Paket Langganan) that have "Lap Penjualan" (id=50)
        $paketLapPenjualan = DB::table('subscriptiondetail')
            ->where('PermissionID', 50)
            ->pluck('NoTransaksi');

        $inserted = 0;
        foreach ($paketLapPenjualan as $noTransaksi) {
            $exists = DB::table('subscriptiondetail')
                ->where('NoTransaksi', $noTransaksi)
                ->where('PermissionID', $permissionId)
                ->exists();
            
            if (!$exists) {
                $maxUrut = DB::table('subscriptiondetail')
                    ->where('NoTransaksi', $noTransaksi)
                    ->max('NoUrut') ?? 0;
                    
                DB::table('subscriptiondetail')->insert([
                    'NoTransaksi' => $noTransaksi,
                    'PermissionID' => $permissionId,
                    'NoUrut' => $maxUrut + 1
                ]);
                $inserted++;
            }
        }
        echo "Inserted $inserted subscription details.";
    } else {
        echo "Permission not found.";
    }
} catch (\Exception $e) {
    ob_clean();
    echo "ERROR MESSAGE: " . $e->getMessage();
}

