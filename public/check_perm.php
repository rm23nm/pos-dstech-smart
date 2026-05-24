<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$all = \Illuminate\Support\Facades\DB::table('permission')->get();
foreach($all as $s) {
    if (strpos(strtolower($s->PermissionName), 'akses') !== false || strpos(strtolower($s->PermissionName), 'subscrip') !== false || strpos(strtolower($s->PermissionName), 'super') !== false || strpos(strtolower($s->Link), 'admin') !== false) {
        echo "- " . $s->PermissionName . " (Link: " . $s->Link . ", Level: " . $s->Level . ")\n";
    }
}
