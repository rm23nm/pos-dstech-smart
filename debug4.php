<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$no = '26050000000010';
$ro = 'DEMO-BENGKEL-001';

$pkb = \DB::table('bengkel_work_order_details')->where('NoPKB', 'PKB-20260528085444')->get();

foreach($pkb as $idx => $row) {
    \DB::table('fakturpenjualandetail')->insert([
        'NoTransaksi' => $no,
        'NoUrut' => $idx + 1,
        'KodeItem' => $row->KodeItem,
        'Qty' => $row->Qty,
        'QtyKonversi' => 1,
        'QtyRetur' => 0,
        'Satuan' => 'PCS',
        'Harga' => $row->Harga,
        'Discount' => 0,
        'HargaNet' => $row->Harga,
        'BaseReff' => 'POS',
        'BaseLine' => -1,
        'KodeGudang' => 'UTM',
        'LineStatus' => 'C',
        'VatPercent' => 0,
        'HargaPokokPenjualan' => $row->Harga,
        'RecordOwnerID' => $ro
    ]);
}
echo "Done injecting";
