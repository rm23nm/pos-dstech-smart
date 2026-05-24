<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::where('email', 'fulladmin@gmail.com')->first();
if ($user) {
    echo "User exists in DB!\n";
    echo "ID: " . $user->id . "\n";
    echo "Email: " . $user->email . "\n";
    echo "RecordOwnerID: " . $user->RecordOwnerID . "\n";
    
    // reset password for convenience
    $user->password = bcrypt('M4m4cantik@');
    $user->save();
    echo "Password has been reset to: M4m4cantik@\n";
    
    $roles = \Illuminate\Support\Facades\DB::table('userrole')->where('userid', $user->id)->get();
    echo "Roles:\n";
    foreach($roles as $r) {
        echo "- RoleID: " . $r->roleid . "\n";
    }
} else {
    echo "User fulladmin@gmail.com still NOT found in DB.\n";
}
