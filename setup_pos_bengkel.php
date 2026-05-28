<?php
use Illuminate\Support\Facades\DB;

$kode = 'DEMO-BENGKEL-001';

// =====================================================
// 1. Buat Gudang untuk demo bengkel
// =====================================================
$gudangExists = DB::table('gudang')->where('RecordOwnerID', $kode)->first();
if ($gudangExists) {
    $kodeGudang = $gudangExists->KodeGudang;
    echo "Gudang OK: " . $kodeGudang . PHP_EOL;
} else {
    // Check gudang schema
    $gudangCols = array_column(DB::select('DESCRIBE gudang'), 'Field');
    echo "Gudang columns: " . implode(', ', $gudangCols) . PHP_EOL;
    
    $insertData = [
        'RecordOwnerID' => $kode,
        'created_at'    => now(),
        'updated_at'    => now(),
    ];
    
    if (in_array('KodeGudang', $gudangCols)) $insertData['KodeGudang'] = $kode;
    if (in_array('NamaGudang', $gudangCols)) $insertData['NamaGudang'] = 'Gudang Utama';
    if (in_array('Keterangan', $gudangCols)) $insertData['Keterangan'] = 'Gudang utama bengkel';
    if (in_array('Active', $gudangCols)) $insertData['Active'] = 1;
    if (in_array('isDefault', $gudangCols)) $insertData['isDefault'] = 1;
    
    $kodeGudang = $kode;
    DB::table('gudang')->insert($insertData);
    echo "Gudang created: " . $kodeGudang . PHP_EOL;
}

// =====================================================
// 2. Update company dengan GudangPoS
// =====================================================
$company = DB::table('company')->where('KodePartner', $kode)->first();
if (!$company->GudangPoS) {
    DB::table('company')->where('KodePartner', $kode)->update([
        'GudangPoS' => $kodeGudang,
    ]);
    echo "Company GudangPoS updated: " . $kodeGudang . PHP_EOL;
} else {
    echo "GudangPoS already set: " . $company->GudangPoS . PHP_EOL;
}

// =====================================================
// 3. Setup MetodePembayaran
// =====================================================
$mpCount = DB::table('metodepembayaran')->where('RecordOwnerID', $kode)->count();
if ($mpCount === 0) {
    $mpCols = array_column(DB::select('DESCRIBE metodepembayaran'), 'Field');
    echo "MetodePembayaran columns: " . implode(', ', $mpCols) . PHP_EOL;
    
    $methods = [
        ['name' => 'Tunai',        'icon' => 'bi bi-cash'],
        ['name' => 'Debit/Kartu',  'icon' => 'bi bi-credit-card'],
        ['name' => 'Transfer',     'icon' => 'bi bi-bank'],
        ['name' => 'QRIS',         'icon' => 'bi bi-qr-code'],
    ];
    
    foreach ($methods as $m) {
        $row = ['RecordOwnerID' => $kode, 'created_at' => now(), 'updated_at' => now()];
        if (in_array('NamaMetodePembayaran', $mpCols)) $row['NamaMetodePembayaran'] = $m['name'];
        if (in_array('NamaMP', $mpCols)) $row['NamaMP'] = $m['name'];
        if (in_array('Active', $mpCols)) $row['Active'] = 1;
        DB::table('metodepembayaran')->insert($row);
    }
    echo "4 MetodePembayaran created." . PHP_EOL;
} else {
    echo "MetodePembayaran OK: " . $mpCount . PHP_EOL;
}

// =====================================================
// 4. Setup Pelanggan Umum (Walk-In)
// =====================================================
$pelangganCount = DB::table('pelanggan')->where('RecordOwnerID', $kode)->count();
if ($pelangganCount === 0) {
    $pelCols = array_column(DB::select('DESCRIBE pelanggan'), 'Field');
    $pelRow = [
        'KodePelanggan'     => 'UMUM-001',
        'NamaPelanggan'     => 'Pelanggan Umum',
        'KodeGrupPelanggan' => 'UMUM',
        'LimitPiutang'      => 0,
        'Status'            => 1,
        'PoinLoyalti'       => 0,
        'RecordOwnerID'     => $kode,
        'created_at'        => now(),
        'updated_at'        => now(),
    ];
    DB::table('pelanggan')->insert($pelRow);
    echo "Pelanggan Umum created." . PHP_EOL;
} else {
    echo "Pelanggan OK: " . $pelangganCount . PHP_EOL;
}

echo "===========================================" . PHP_EOL;
echo "DONE! POS setup complete." . PHP_EOL;
echo "===========================================" . PHP_EOL;
