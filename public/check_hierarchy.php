<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$kelompokAkses = \Illuminate\Support\Facades\DB::table('permission')->where('PermissionName', 'Kelompok Akses')->first();
if ($kelompokAkses) {
    echo "Kelompok Akses: Level = " . $kelompokAkses->Level . ", MenuInduk = " . $kelompokAkses->MenuInduk . "\n";
    $parent = \Illuminate\Support\Facades\DB::table('permission')->where('id', $kelompokAkses->MenuInduk)->first();
    if ($parent) {
        echo "Parent: " . $parent->PermissionName . ", Level = " . $parent->Level . ", MenuInduk = " . $parent->MenuInduk . "\n";
        $grandparent = \Illuminate\Support\Facades\DB::table('permission')->where('id', $parent->MenuInduk)->first();
        if ($grandparent) {
            echo "Grandparent: " . $grandparent->PermissionName . ", Level = " . $grandparent->Level . ", MenuInduk = " . $grandparent->MenuInduk . "\n";
        }
    }
}
