<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::beginTransaction();

    $cloneFrom = 'CL0014'; // Demo Supermarket
    $sourceCompany = DB::table('company')->where('KodePartner', $cloneFrom)->first();

    if ($sourceCompany) {
        $sourceArray = (array) $sourceCompany;

        // 1. Create demoapotek
        $existsApo = DB::table('company')->where('KodePartner', 'demoapotek')->exists();
        if (!$existsApo) {
            $apoArray = $sourceArray;
            $apoArray['KodePartner'] = 'demoapotek';
            $apoArray['NamaPartner'] = 'Demo Apotek';
            $apoArray['JenisUsaha'] = 'Apotek';
            $apoArray['Email'] = 'demoapotek@pos.dstechsmart.com';
            DB::table('company')->insert($apoArray);
            echo "Inserted demoapotek company.\n";
        } else {
            DB::table('company')->where('KodePartner', 'demoapotek')->update([
                'JenisUsaha' => 'Apotek'
            ]);
            echo "Updated demoapotek company.\n";
        }

        // 2. Create DEMOGATE
        $existsGate = DB::table('company')->where('KodePartner', 'DEMOGATE')->exists();
        if (!$existsGate) {
            $gateArray = $sourceArray;
            $gateArray['KodePartner'] = 'DEMOGATE';
            $gateArray['NamaPartner'] = 'Demo Waterpark & Gate';
            $gateArray['JenisUsaha'] = 'TiketGate';
            $gateArray['Email'] = 'demogate@pos.dstechsmart.com';
            DB::table('company')->insert($gateArray);
            echo "Inserted DEMOGATE company.\n";
        } else {
            DB::table('company')->where('KodePartner', 'DEMOGATE')->update([
                'JenisUsaha' => 'TiketGate'
            ]);
            echo "Updated DEMOGATE company.\n";
        }
    } else {
        echo "Source company $cloneFrom not found.\n";
    }

    DB::commit();
    echo "Done!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
