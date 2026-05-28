<?php
use Illuminate\Support\Facades\DB;
$kode = 'DEMO-BENGKEL-001';
// Insert a dummy subscription header
$noTransaksi = 'SUB-' . $kode;
DB::table('subscriptionheader')->updateOrInsert(
    ['NoTransaksi' => $noTransaksi],
    ['Tanggal' => date('Y-m-d'), 'NamaSubscription' => 'Premium Bengkel', 'DeskripsiSubscription' => '-', 'Harga' => 0, 'Potongan' => 0, 'LamaSubsription' => 12, 'JenisUsaha' => 'Bengkel', 'AllowMonitorAntrean' => 1, 'AllowPesananMeja' => 1, 'AllowAccounting' => 1, 'AllowPaymentGateway' => 1]
);

// Get all permissions assigned to the role of this user
$role = DB::table('roles')->where('RecordOwnerID', $kode)->first();

if ($role) {
    $permissions = DB::table('permissionrole')->where('RoleID', $role->id)->pluck('PermissionID');
    
    // Insert into subscriptiondetail
    DB::table('subscriptiondetail')->where('NoTransaksi', $noTransaksi)->delete();
    $insertData = [];
    foreach ($permissions as $pid) {
        $insertData[] = [
            'NoTransaksi' => $noTransaksi,
            'PermissionID' => $pid
        ];
    }
    DB::table('subscriptiondetail')->insert($insertData);
    
    // Update company
    DB::table('company')->where('KodePartner', $kode)->update(['KodePaketLangganan' => $noTransaksi, 'isActive' => 1]);
    
    echo "Successfully created subscription $noTransaksi with " . count($insertData) . " permissions.\n";
}
