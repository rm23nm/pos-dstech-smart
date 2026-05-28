<?php
use Illuminate\Support\Facades\DB;
$perms = DB::table('permission')->where('PermissionName', 'like', '%Display%')->get();
foreach($perms as $p) {
    echo $p->id . ' | ' . $p->PermissionName . ' | Level: ' . $p->Level . ' | Induk: ' . $p->MenuInduk . "\n";
}
