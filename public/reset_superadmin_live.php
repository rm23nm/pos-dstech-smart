<?php
// Script untuk mereset password superadmin di live
// PENTING: Hapus file ini setelah digunakan!

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$email = 'fulladmin@gmail.com';
$newPassword = 'M4m4cantik@';

$user = \App\Models\User::where('email', $email)->first();

if ($user) {
    $user->password = \Illuminate\Support\Facades\Hash::make($newPassword);
    $user->save();
    echo "Password untuk $email berhasil direset.\n";
    echo "Silahkan login dengan password baru: $newPassword\n";
    echo "\n!! SEGERA HAPUS FILE INI SETELAH DIGUNAKAN !!";
} else {
    echo "User $email tidak ditemukan!";
}
