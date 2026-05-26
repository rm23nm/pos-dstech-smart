<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$setting = DB::table('settingaccount')->where('RecordOwnerID', 'demoapotek')->first();

if ($setting) {
    echo "Updating itemmaster for demoapotek where empty accounts exist...\n";
    
    DB::table('itemmaster')
        ->where('RecordOwnerID', 'demoapotek')
        ->where(function($q) {
            $q->whereNull('AcctHPP')->orWhere('AcctHPP', '');
        })
        ->update(['AcctHPP' => $setting->InvAcctHargaPokokPenjualan]);
        
    DB::table('itemmaster')
        ->where('RecordOwnerID', 'demoapotek')
        ->where(function($q) {
            $q->whereNull('AcctPenjualan')->orWhere('AcctPenjualan', '');
        })
        ->update(['AcctPenjualan' => $setting->InvAcctPendapatanJual]);
        
    DB::table('itemmaster')
        ->where('RecordOwnerID', 'demoapotek')
        ->where(function($q) {
            $q->whereNull('AcctPenjualanJasa')->orWhere('AcctPenjualanJasa', '');
        })
        ->update(['AcctPenjualanJasa' => $setting->InvAcctPendapatanJasa]);
        
    DB::table('itemmaster')
        ->where('RecordOwnerID', 'demoapotek')
        ->where(function($q) {
            $q->whereNull('AcctPersediaan')->orWhere('AcctPersediaan', '');
        })
        ->update(['AcctPersediaan' => $setting->InvAcctPersediaan]);
        
    echo "Done updating empty accounts in itemmaster for demoapotek.\n";
}
