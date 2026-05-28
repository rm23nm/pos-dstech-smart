<?php
use Illuminate\Support\Facades\DB;

$mb = DB::table('permission')->where('PermissionName', 'Management Bengkel')->first();
if ($mb) {
    $sa = DB::table('permission')->where('PermissionName', 'Mekanik Progres')->first();
    if (!$sa) {
        $idSA = DB::table('permission')->insertGetId([
            'PermissionName' => 'Mekanik Progres',
            'Link' => 'dashboard-mekanik',
            'Icon' => 'bi bi-tools',
            'Level' => 2,
            'MenuInduk' => $mb->id,
            'Status' => 1,
            'SubMenu' => 0,
            'Order' => 2
        ]);
        
        DB::table('permissionrole')->updateOrInsert([
            'RoleID' => 1,
            'PermissionID' => $idSA
        ], ['RecordOwnerID' => '']);
        
        $subNo = 'SUB-DEMO-BENGKEL-001';
        $maxUrut = DB::table('subscriptiondetail')->where('NoTransaksi', $subNo)->max('NoUrut') ?? 100;
        DB::table('subscriptiondetail')->updateOrInsert([
            'NoTransaksi' => $subNo,
            'PermissionID' => $idSA
        ], ['NoUrut' => $maxUrut + 1]);
        
        echo "Inserted Mekanik Progres menu with ID: $idSA\n";
    } else {
        echo "Mekanik Progres menu already exists.\n";
    }
} else {
    echo "Management Bengkel menu not found!\n";
}
