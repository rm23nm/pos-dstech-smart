<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $settings = DB::table('settingaccount')->get();
    $count = 0;
    foreach($settings as $setting) {
        $owner = $setting->RecordOwnerID;
        
        // Update AcctHPP
        if(!empty($setting->InvAcctHargaPokokPenjualan)) {
            $count += DB::table('itemmaster')
                ->where('RecordOwnerID', $owner)
                ->where(function($q) { $q->whereNull('AcctHPP')->orWhere('AcctHPP', ''); })
                ->update(['AcctHPP' => $setting->InvAcctHargaPokokPenjualan]);
        }
        
        // Update AcctPenjualan (Barang) -> For TypeItem 1 (Inventory), 3 (Rakitan), 5 (Konsinyasi), 6 (Bahan Baku)
        if(!empty($setting->InvAcctPendapatanJual)) {
            $count += DB::table('itemmaster')
                ->where('RecordOwnerID', $owner)
                ->whereIn('TypeItem', ['1','3','5','6'])
                ->where(function($q) { $q->whereNull('AcctPenjualan')->orWhere('AcctPenjualan', ''); })
                ->update(['AcctPenjualan' => $setting->InvAcctPendapatanJual]);
                
            // For TypeItem 2 (Non Inventory) without specific non-inv account, fallback to Jual or Jasa?
            // Usually Jual or Jasa. Let's just update all AcctPenjualan if empty.
            $count += DB::table('itemmaster')
                ->where('RecordOwnerID', $owner)
                ->where(function($q) { $q->whereNull('AcctPenjualan')->orWhere('AcctPenjualan', ''); })
                ->update(['AcctPenjualan' => $setting->InvAcctPendapatanJual]);
        }
        
        // Update AcctPenjualanJasa (Jasa) -> For TypeItem 4
        if(!empty($setting->InvAcctPendapatanJasa)) {
            $count += DB::table('itemmaster')
                ->where('RecordOwnerID', $owner)
                ->where(function($q) { $q->whereNull('AcctPenjualanJasa')->orWhere('AcctPenjualanJasa', ''); })
                ->update(['AcctPenjualanJasa' => $setting->InvAcctPendapatanJasa]);
        }
        
        // Update AcctPersediaan
        if(!empty($setting->InvAcctPersediaan)) {
            $count += DB::table('itemmaster')
                ->where('RecordOwnerID', $owner)
                ->where(function($q) { $q->whereNull('AcctPersediaan')->orWhere('AcctPersediaan', ''); })
                ->update(['AcctPersediaan' => $setting->InvAcctPersediaan]);
        }
    }
    echo 'Successfully updated ' . $count . ' rows.';
} catch(\Exception $e) {
    echo $e->getMessage();
}

