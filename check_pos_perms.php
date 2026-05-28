<?php
use Illuminate\Support\Facades\DB;
$perms = DB::table('permission')
    ->where('PermissionName', 'like', '%POS%')
    ->orWhere('PermissionName', 'like', '%Kasir%')
    ->get();
foreach($perms as $p) {
    echo $p->id . ' | ' . $p->PermissionName . ' | Level: ' . $p->Level . ' | Induk: ' . $p->MenuInduk . ' | Link: ' . $p->Link . "\n";
}
