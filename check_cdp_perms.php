<?php
use Illuminate\Support\Facades\DB;
$perms = DB::table('permission')->where('PermissionName', 'like', '%Customer Display POS%')->get();
foreach($perms as $p) {
    echo $p->id . ' | ' . $p->PermissionName . ' | Link: ' . $p->Link . "\n";
}
