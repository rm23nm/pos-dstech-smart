<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Insert default termin if not exists
$termId = null;
$termExists = DB::table('terminpembayaran')
    ->where('RecordOwnerID', 'CL0013')
    ->where('NamaTermin', 'COD')
    ->first();

if (!$termExists) {
    $termId = DB::table('terminpembayaran')->insertGetId([
        'NamaTermin' => 'COD',
        'JumlahHari' => 0,
        'ExtraDays' => 1,
        'RecordOwnerID' => 'CL0013',
        'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
        'updated_at' => Carbon\Carbon::now('Asia/Jakarta'),
    ]);
    echo "Term COD created for CL0013 with ID: " . $termId . "\n";
} else {
    $termId = $termExists->id;
    echo "Term COD already exists with ID: " . $termId . "\n";
}

// 2. Update company table
$company = DB::table('company')->where('KodePartner', 'CL0013')->first();
if ($company) {
    DB::table('company')
        ->where('KodePartner', 'CL0013')
        ->update([
            'GudangPoS' => 'UMM',
            'TerminBayarPoS' => $termId,
            'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
        ]);
    echo "Company settings updated successfully (GudangPoS = UMM, TerminBayarPoS = " . $termId . ")\n";
} else {
    echo "Company record not found.\n";
}
