<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::where('email', 'fulladmin@gmail.com')->first();
if ($user) {
    echo "Found user: " . $user->email . " (ID: " . $user->id . ", RecordOwnerID: " . $user->RecordOwnerID . ")\n";
    $roles = \Illuminate\Support\Facades\DB::table('userrole')->where('userid', $user->id)->get();
    echo "Roles:\n";
    foreach($roles as $r) {
        echo "- RoleID: " . $r->roleid . "\n";
    }
} else {
    echo "Not found.";
}
