<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$companies = ['demoapotek', 'CL0010', 'CL0013', 'CL0014'];

foreach ($companies as $code) {
    echo "Processing company: {$code}...\n";
    
    // Check if COD term exists for this company
    $term = DB::table('terminpembayaran')
        ->where('RecordOwnerID', $code)
        ->where('NamaTermin', 'COD')
        ->first();
        
    if (!$term) {
        $termId = DB::table('terminpembayaran')->insertGetId([
            'NamaTermin' => 'COD',
            'JumlahHari' => 0,
            'ExtraDays' => 1,
            'RecordOwnerID' => $code,
            'created_at' => Carbon::now('Asia/Jakarta'),
            'updated_at' => Carbon::now('Asia/Jakarta'),
        ]);
        echo "  Created COD term with ID: {$termId}\n";
    } else {
        $termId = $term->id;
        echo "  COD term already exists with ID: {$termId}\n";
    }
    
    // Update company settings
    $company = DB::table('company')->where('KodePartner', $code)->first();
    if ($company) {
        $updates = [
            'TerminBayarPoS' => $termId,
            'updated_at' => Carbon::now('Asia/Jakarta')
        ];
        
        // If GudangPoS is empty, set default to UMM
        if (empty($company->GudangPoS)) {
            $updates['GudangPoS'] = 'UMM';
            echo "  Setting GudangPoS to UMM\n";
        }
        
        DB::table('company')
            ->where('KodePartner', $code)
            ->update($updates);
            
        echo "  Updated company settings successfully!\n";
    } else {
        echo "  Company record not found!\n";
    }
}
echo "All done!\n";
