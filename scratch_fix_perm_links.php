<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

// Fix link untuk permission 115 dan 116
// 115 = Monitor Antrean -> link route name = 'queue/{id}' tapi di sidebar butuh URL, 
//       gunakan link yang bisa diakses dari header blade
// 116 = Monitor Counter -> link route name = 'countermonitor'

// Fix permission 115 Monitor Antrean - ganti ke route name yang benar
DB::table('permission')->where('id', 115)->update([
    'Link' => 'queue/{id}', // URL pattern untuk queue
    'updated_at' => now(),
]);
echo "Updated Monitor Antrean (115) link to 'queue/{id}'\n";

// Fix permission 116 Monitor Counter - ganti ke route name yang benar
DB::table('permission')->where('id', 116)->update([
    'Link' => 'countermonitor',
    'updated_at' => now(),
]);
echo "Updated Monitor Counter (116) link to 'countermonitor'\n";

// Verify
$perms = DB::table('permission')->whereIn('id', [113, 114, 115, 116])->get();
foreach ($perms as $p) {
    echo "ID: {$p->id} | {$p->PermissionName} | Level: {$p->Level} | Parent: {$p->MenuInduk} | Link: {$p->Link}\n";
}
