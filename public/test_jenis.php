<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \DB::table('users')->where('email', 'demoresto@pos.dstechsmart.com')->first();
$role = \DB::table('role_user')->where('user_id', $user->id)->first();
$perms = \DB::table('permission_role')->where('role_id', $role->role_id)->pluck('permission_id')->toArray();
$permNames = \DB::table('permissions')->whereIn('id', $perms)->pluck('name')->toArray();

echo "Perms for demoresto: " . implode(', ', $permNames);
