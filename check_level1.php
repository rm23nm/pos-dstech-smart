<?php
use Illuminate\Support\Facades\DB;
$perms = DB::table('permission')->where('Level', 1)->get();
foreach($perms as $p) {
    echo $p->id . ' | ' . $p->PermissionName . "\n";
}
