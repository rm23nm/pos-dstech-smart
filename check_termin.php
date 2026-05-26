<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$companies = DB::table('company')->get();
$cl0009 = DB::table('company')->where('KodePartner', 'CL0009')->first();
$defaultTermin = $cl0009 ? $cl0009->TerminBayarPoS : 'CASH';

echo "Default Termin from CL0009 is: " . $defaultTermin . "\n";

foreach($companies as $c) {
    echo $c->KodePartner . ' : TerminBayarPoS = ' . ($c->TerminBayarPoS ? $c->TerminBayarPoS : 'NULL') . "\n";
    
    if (empty($c->TerminBayarPoS)) {
        DB::table('company')->where('KodePartner', $c->KodePartner)->update([
            'TerminBayarPoS' => $defaultTermin
        ]);
        echo " -> Updated " . $c->KodePartner . " TerminBayarPoS to " . $defaultTermin . "\n";
    }
}
