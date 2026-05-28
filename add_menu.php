<?php

use Illuminate\Support\Facades\DB;

$existing = DB::table('permission')->where('PermissionName', 'Laporan Komisi Mekanik')->first();

if (!$existing) {
    // Find parent Laporan Penjualan or similar to get the Parent ID
    $parent = DB::table('permission')->where('PermissionName', 'Penjualan')->where('MenuID', 'like', '04%')->first();
    
    if ($parent) {
        // Find last child of that parent
        $lastChild = DB::table('permission')->where('MenuID', 'like', $parent->MenuID . '.%')->orderBy('MenuID', 'desc')->first();
        
        $newIdStr = "";
        if ($lastChild) {
            $parts = explode('.', $lastChild->MenuID);
            $lastNum = (int)end($parts);
            $newIdStr = $parent->MenuID . '.' . sprintf('%02d', $lastNum + 1);
        } else {
            $newIdStr = $parent->MenuID . '.01';
        }
        
        DB::table('permission')->insert([
            'PermissionName' => 'Laporan Komisi Mekanik',
            'MenuID' => $newIdStr,
            'Icon' => '',
            'Link' => 'report/komisi-mekanik',
            'Status' => 1
        ]);
        
        echo "Inserted new menu with ID: " . $newIdStr . "\n";
    } else {
        echo "Parent menu not found.\n";
    }
} else {
    echo "Menu already exists.\n";
}
