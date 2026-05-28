<?php
use Illuminate\Support\Facades\DB;

$mb = DB::table('permission')->where('PermissionName', 'Management Bengkel')->first();
if ($mb) {
    $sa = DB::table('permission')->where('PermissionName', 'Pendaftaran Servis')->first();
    if (!$sa) {
        $idSA = DB::table('permission')->insertGetId([
            'PermissionName' => 'Pendaftaran Servis',
            'Link' => 'service-advisor',
            'Icon' => 'bi bi-clipboard-plus',
            'Level' => 2,
            'MenuInduk' => $mb->id,
            'Status' => 1,
            'SubMenu' => 0,
            'Order' => 1
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
        
        echo "Inserted Service Advisor menu with ID: $idSA\n";
    } else {
        echo "Service Advisor menu already exists.\n";
    }
} else {
    echo "Management Bengkel menu not found!\n";
}
