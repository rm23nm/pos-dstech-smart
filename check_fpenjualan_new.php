<?php
use Illuminate\Support\Facades\DB;
$perms = DB::table('permission')->where('Link', 'like', '%fpenjualan/new%')->get();
foreach($perms as $p) {
    echo $p->id . ' | ' . $p->PermissionName . ' | Level: ' . $p->Level . ' | Induk: ' . $p->MenuInduk . "\n";
}
