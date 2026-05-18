<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 2. Insert pelanggan if not exists
$pelangganExists = DB::table('pelanggan')
    ->where('RecordOwnerID', 'CL0013')
    ->where('KodePelanggan', 'UMUM')
    ->exists();

if (!$pelangganExists) {
    DB::table('pelanggan')->insert([
        'KodePelanggan' => 'UMUM',
        'NamaPelanggan' => 'Pelanggan Umum',
        'KodeGrupPelanggan' => '1001',
        'LimitPiutang' => 0,
        'ProvID' => -1,
        'KotaID' => -1,
        'KelID' => -1,
        'KecID' => -1,
        'Email' => '',
        'NoTlp1' => '081200000000',
        'NoTlp2' => '',
        'Alamat' => '',
        'Keterangan' => 'Default Customer',
        'Status' => 1,
        'isPaidMembership' => 0,
        'MaxPlay' => 0,
        'MemberPrice' => 0,
        'maxTimePerPlay' => 0,
        'PelangganID' => '999999999999',
        'RecordOwnerID' => 'CL0013',
        'AllowedDay' => '',
        'ValidUntil' => '2099-12-31 23:59:59',
        'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
        'updated_at' => Carbon\Carbon::now('Asia/Jakarta'),
    ]);
    echo "Default customer 'UMUM' created successfully.\n";
} else {
    echo "Default customer 'UMUM' already exists.\n";
}
