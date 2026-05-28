<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$email    = 'demobengkel@pos.dstechsmart.com';
$password = '12345678';
$kode     = 'DEMO-BENGKEL-001';
$nama     = 'Demo Bengkel Smart';
$jenis    = 'Bengkel';

// =====================================================
// 1. USER
// =====================================================
$existingUser = DB::table('users')->where('email', $email)->first();
if ($existingUser) {
    echo "User OK: " . $email . PHP_EOL;
} else {
    DB::table('users')->insert([
        'name'              => 'Admin Demo Bengkel',
        'email'             => $email,
        'password'          => Hash::make($password),
        'RecordOwnerID'     => $kode,
        'BranchID'          => $kode,
        'Active'            => 1,
        'isConfirmed'       => 1,
        'email_verified_at' => now(),
        'created_at'        => now(),
        'updated_at'        => now(),
    ]);
    echo "User created: " . $email . PHP_EOL;
}

// =====================================================
// 2. COMPANY
// =====================================================
$existingCompany = DB::table('company')->where('KodePartner', $kode)->first();
if ($existingCompany) {
    echo "Company OK: " . $nama . PHP_EOL;
} else {
    DB::table('company')->insert([
        'KodePartner'           => $kode,
        'NamaPartner'           => $nama,
        'AlamatTagihan'         => 'Jl. Bengkel Raya No. 88, Jakarta Selatan',
        'NoTlp'                 => '021-12345678',
        'NoHP'                  => '0822-5849-3130',
        'NIKPIC'                => '3175001234560001',
        'NamaPIC'               => 'Admin Demo',
        'tempStore'             => $kode,
        'JenisUsaha'            => $jenis,
        'isActive'              => 1,
        'isInitialSetting'      => 1,
        'FooterNota'            => 'Terima kasih telah mempercayai Demo Bengkel Smart!',
        'RunningText'           => 'Selamat datang di Demo Bengkel Smart - Servis Cepat & Terpercaya!',
        'TypeKitchenBackgraund' => 'color',
        'KitchenBackgraund'     => '#1a237e',
        'created_at'            => now(),
        'updated_at'            => now(),
    ]);
    echo "Company created: " . $nama . PHP_EOL;
}

// =====================================================
// 3. ITEMMASTER - Jasa & Sparepart Bengkel
// =====================================================
$existingItems = DB::table('itemmaster')->where('RecordOwnerID', $kode)->count();
if ($existingItems > 0) {
    echo "Items already exist (" . $existingItems . "). Skipping." . PHP_EOL;
} else {
    $items = [
        // Jasa Servis
        ['KodeItem'=>'JSV-001','NamaItem'=>'Servis Mesin Ringan',      'HargaJual'=>150000,'HPP'=>30000, 'TypeItem'=>'Jasa',   'Gambar'=>'https://img.icons8.com/color/96/engine.png'],
        ['KodeItem'=>'JSV-002','NamaItem'=>'Servis Mesin Berat',       'HargaJual'=>350000,'HPP'=>75000, 'TypeItem'=>'Jasa',   'Gambar'=>'https://img.icons8.com/color/96/engine.png'],
        ['KodeItem'=>'JSV-003','NamaItem'=>'Tune Up Motor',            'HargaJual'=>120000,'HPP'=>25000, 'TypeItem'=>'Jasa',   'Gambar'=>'https://img.icons8.com/color/96/motorcycle.png'],
        ['KodeItem'=>'JSV-004','NamaItem'=>'Tune Up Mobil',            'HargaJual'=>250000,'HPP'=>50000, 'TypeItem'=>'Jasa',   'Gambar'=>'https://img.icons8.com/color/96/car.png'],
        ['KodeItem'=>'JSV-005','NamaItem'=>'Ganti Oli Mesin (Motor)',  'HargaJual'=>50000, 'HPP'=>10000, 'TypeItem'=>'Jasa',   'Gambar'=>'https://img.icons8.com/color/96/oil-pump.png'],
        ['KodeItem'=>'JSV-006','NamaItem'=>'Ganti Oli Mesin (Mobil)',  'HargaJual'=>85000, 'HPP'=>20000, 'TypeItem'=>'Jasa',   'Gambar'=>'https://img.icons8.com/color/96/oil-pump.png'],
        ['KodeItem'=>'JSV-007','NamaItem'=>'Spooring & Balancing',     'HargaJual'=>200000,'HPP'=>40000, 'TypeItem'=>'Jasa',   'Gambar'=>'https://img.icons8.com/color/96/tire.png'],
        ['KodeItem'=>'JSV-008','NamaItem'=>'Servis Rem Motor/Mobil',   'HargaJual'=>175000,'HPP'=>35000, 'TypeItem'=>'Jasa',   'Gambar'=>'https://img.icons8.com/color/96/brakes.png'],
        ['KodeItem'=>'JSV-009','NamaItem'=>'Cuci Motor',               'HargaJual'=>20000, 'HPP'=>5000,  'TypeItem'=>'Jasa',   'Gambar'=>'https://img.icons8.com/color/96/motorcycle.png'],
        ['KodeItem'=>'JSV-010','NamaItem'=>'Cuci Mobil',               'HargaJual'=>50000, 'HPP'=>12000, 'TypeItem'=>'Jasa',   'Gambar'=>'https://img.icons8.com/color/96/car-wash.png'],
        // Sparepart
        ['KodeItem'=>'SPR-001','NamaItem'=>'Oli Mesin Shell Helix 1L', 'HargaJual'=>85000, 'HPP'=>60000, 'TypeItem'=>'Barang', 'Gambar'=>'https://img.icons8.com/color/96/oil-pump.png'],
        ['KodeItem'=>'SPR-002','NamaItem'=>'Busi NGK Standard',        'HargaJual'=>35000, 'HPP'=>22000, 'TypeItem'=>'Barang', 'Gambar'=>'https://img.icons8.com/color/96/spark-plug.png'],
        ['KodeItem'=>'SPR-003','NamaItem'=>'Filter Udara Universal',   'HargaJual'=>55000, 'HPP'=>35000, 'TypeItem'=>'Barang', 'Gambar'=>'https://img.icons8.com/color/96/air-filter.png'],
        ['KodeItem'=>'SPR-004','NamaItem'=>'Kampas Rem Depan (Motor)', 'HargaJual'=>75000, 'HPP'=>45000, 'TypeItem'=>'Barang', 'Gambar'=>'https://img.icons8.com/color/96/brakes.png'],
        ['KodeItem'=>'SPR-005','NamaItem'=>'Kampas Rem Depan (Mobil)', 'HargaJual'=>250000,'HPP'=>160000,'TypeItem'=>'Barang', 'Gambar'=>'https://img.icons8.com/color/96/brakes.png'],
        ['KodeItem'=>'SPR-006','NamaItem'=>'Rantai Motor RK 428',      'HargaJual'=>150000,'HPP'=>95000, 'TypeItem'=>'Barang', 'Gambar'=>'https://img.icons8.com/color/96/chain.png'],
        ['KodeItem'=>'SPR-007','NamaItem'=>'Ban Motor 80/90-17',       'HargaJual'=>350000,'HPP'=>220000,'TypeItem'=>'Barang', 'Gambar'=>'https://img.icons8.com/color/96/tire.png'],
        ['KodeItem'=>'SPR-008','NamaItem'=>'Aki Motor MF 12Ah',        'HargaJual'=>280000,'HPP'=>180000,'TypeItem'=>'Barang', 'Gambar'=>'https://img.icons8.com/color/96/battery.png'],
        ['KodeItem'=>'SPR-009','NamaItem'=>'Aki Mobil NS60',           'HargaJual'=>650000,'HPP'=>420000,'TypeItem'=>'Barang', 'Gambar'=>'https://img.icons8.com/color/96/battery.png'],
        ['KodeItem'=>'SPR-010','NamaItem'=>'Lampu Depan LED H4',       'HargaJual'=>120000,'HPP'=>75000, 'TypeItem'=>'Barang', 'Gambar'=>'https://img.icons8.com/color/96/light.png'],
    ];

    $created = 0;
    foreach ($items as $item) {
        DB::table('itemmaster')->insert([
            'KodeItem'           => $item['KodeItem'],
            'NamaItem'           => $item['NamaItem'],
            'KodeJenisItem'      => 'UMUM',
            'KodeMerk'           => 'UMUM',
            'TypeItem'           => $item['TypeItem'],
            'Rak'                => '-',
            'KodeGudang'         => $kode,
            'KodeSupplier'       => '-',
            'Satuan'             => ($item['TypeItem'] === 'Jasa') ? 'Pekerjaan' : 'Pcs',
            'Barcode'            => $item['KodeItem'],
            'HargaJual'          => $item['HargaJual'],
            'HargaPokokPenjualan'=> $item['HPP'],
            'HargaBeliTerakhir'  => $item['HPP'],
            'Gambar'             => $item['Gambar'],
            'Stock'              => ($item['TypeItem'] === 'Barang') ? 50 : 9999,
            'StockMinimum'       => 5,
            'isKonsinyasi'       => 0,
            'Active'             => 1,
            'VatPercent'         => 0,
            'RecordOwnerID'      => $kode,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
        $created++;
    }
    echo $created . " items created in itemmaster." . PHP_EOL;
}

echo "==========================================" . PHP_EOL;
echo "DONE! Login: " . $email . " / " . $password . PHP_EOL;
echo "==========================================" . PHP_EOL;
