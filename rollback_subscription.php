<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "Memulai ROLLBACK paket subscription...\n";

DB::beginTransaction();

try {
    $now = Carbon::now();

    // 1. Bersihkan tabel
    DB::table('subscriptiondetail')->delete();
    DB::table('subscriptionheader')->delete();

    // 2. Restore Subscription Headers
    $headers = [
        ["NoTransaksi"=>"POS 0001","NamaSubscription"=>"POS FNB SEMESTERAN","JenisUsaha"=>"FnB","LamaSubsription"=>6],
        ["NoTransaksi"=>"PKT001","NamaSubscription"=>"PREMIUM","JenisUsaha"=>"Retail","LamaSubsription"=>12],
        ["NoTransaksi"=>"2002","NamaSubscription"=>"BASIC","JenisUsaha"=>"Hiburan","LamaSubsription"=>12],
        ["NoTransaksi"=>"PKT002","NamaSubscription"=>"PRO","JenisUsaha"=>"Retail","LamaSubsription"=>12],
        ["NoTransaksi"=>"PRT0002","NamaSubscription"=>"POS RETAIL TRIWULAN","JenisUsaha"=>"Retail","LamaSubsription"=>3],
        ["NoTransaksi"=>"PRT0003","NamaSubscription"=>"POS RETAIL TAHUNAN","JenisUsaha"=>"Retail","LamaSubsription"=>12],
        ["NoTransaksi"=>"PFNB001","NamaSubscription"=>"BASIC","JenisUsaha"=>"FnB","LamaSubsription"=>12],
        ["NoTransaksi"=>"PFNB002","NamaSubscription"=>"PREMIUM","JenisUsaha"=>"FnB","LamaSubsription"=>12],
        ["NoTransaksi"=>"PFNB003","NamaSubscription"=>"PRO","JenisUsaha"=>"FnB","LamaSubsription"=>12],
        ["NoTransaksi"=>"PHMST01","NamaSubscription"=>"BASIC","JenisUsaha"=>"Retail","LamaSubsription"=>12],
        ["NoTransaksi"=>"2022","NamaSubscription"=>"PREMIUM","JenisUsaha"=>"Hiburan","LamaSubsription"=>12],
        ["NoTransaksi"=>"2003","NamaSubscription"=>"PRO","JenisUsaha"=>"Hiburan","LamaSubsription"=>12],
        ["NoTransaksi"=>"TEST-SUB-99","NamaSubscription"=>"Test Premium Package F&B","JenisUsaha"=>"FnB","LamaSubsription"=>12],
        ["NoTransaksi"=>"FREE","NamaSubscription"=>"FREE","JenisUsaha"=>"FnB","LamaSubsription"=>12] // Added for DEMOTIKET
    ];

    $permissions = DB::table('permission')->pluck('id')->toArray();

    foreach ($headers as $h) {
        DB::table('subscriptionheader')->insert([
            'NoTransaksi' => $h['NoTransaksi'],
            'Tanggal' => $now->format('Y-m-d'),
            'NamaSubscription' => $h['NamaSubscription'],
            'DeskripsiSubscription' => $h['NamaSubscription'],
            'Harga' => 0,
            'Potongan' => 0,
            'LamaSubsription' => $h['LamaSubsription'],
            'AllowAccounting' => 1,
            'AllowPesananMeja' => 1,
            'AllowPaymentGateway' => 1,
            'AllowKatalogOnline' => 1,
            'AllowMonitorAntrean' => 1,
            'JenisUsaha' => $h['JenisUsaha'],
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $details = [];
        $noUrut = 1;
        foreach ($permissions as $pid) {
            $details[] = [
                'NoTransaksi' => $h['NoTransaksi'],
                'PermissionID' => $pid,
                'NoUrut' => $noUrut++,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        DB::table('subscriptiondetail')->insert($details);
    }
    echo "Restore 14 Packages (Header & Detail) Berhasil.\n";

    // 3. Restore Company
    $companies = [
        ["KodePartner"=>"999999","KodePaketLangganan"=>null],
        ["KodePartner"=>"CL0001","KodePaketLangganan"=>"2003"],
        ["KodePartner"=>"CL0002","KodePaketLangganan"=>"POS 0001"],
        ["KodePartner"=>"CL0003","KodePaketLangganan"=>"2002"],
        ["KodePartner"=>"CL0004","KodePaketLangganan"=>"PFNB003"],
        ["KodePartner"=>"CL0005","KodePaketLangganan"=>"PFNB003"],
        ["KodePartner"=>"CL0006","KodePaketLangganan"=>"2002"],
        ["KodePartner"=>"CL0007","KodePaketLangganan"=>"PKT002"],
        ["KodePartner"=>"CL0008","KodePaketLangganan"=>"2002"],
        ["KodePartner"=>"CL0009","KodePaketLangganan"=>"2003"],
        ["KodePartner"=>"CL0010","KodePaketLangganan"=>"2003"],
        ["KodePartner"=>"CL0011","KodePaketLangganan"=>"2003"],
        ["KodePartner"=>"CL0012","KodePaketLangganan"=>"PFNB001"],
        ["KodePartner"=>"CL0013","KodePaketLangganan"=>"PFNB003"],
        ["KodePartner"=>"CL0014","KodePaketLangganan"=>"2022"],
        ["KodePartner"=>"demoapotek","KodePaketLangganan"=>"2022"],
        ["KodePartner"=>"DEMOGATE","KodePaketLangganan"=>"2022"],
        ["KodePartner"=>"DEMOTIKET","KodePaketLangganan"=>"FREE"]
    ];

    foreach ($companies as $c) {
        DB::table('company')->where('KodePartner', $c['KodePartner'])->update([
            'KodePaketLangganan' => $c['KodePaketLangganan']
        ]);
    }
    echo "Restore ".count($companies)." Companies Berhasil.\n";

    DB::commit();
    echo "\nBERHASIL ROLLBACK!\n";
} catch (\Exception $e) {
    DB::rollback();
    echo "GAGAL: " . $e->getMessage() . "\n";
}
