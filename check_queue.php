<?php
use Illuminate\Support\Facades\DB;
$perms = DB::table('permission')->where('Link', 'like', '%queue%')->get();
foreach($perms as $p) {
    echo $p->PermissionName . ' | ' . $p->Link . ' | ' . $p->MenuInduk . PHP_EOL;
}
