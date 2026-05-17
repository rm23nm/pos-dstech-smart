<?php

echo "<h2>Membersihkan Cache Laravel di Live Server...</h2>";
echo "<pre>";

// Daftar perintah artisan untuk membersihkan cache
$commands = [
    "php artisan route:clear",
    "php artisan view:clear",
    "php artisan config:clear",
    "php artisan cache:clear",
    "php artisan optimize:clear"
];

foreach ($commands as $cmd) {
    echo "Menjalankan: <b>$cmd</b>\n";
    $output = shell_exec("cd /www/wwwroot/pos.dstechsmart.com && $cmd 2>&1");
    echo "Hasil:\n" . htmlspecialchars($output) . "\n\n";
}

echo "</pre>";
echo "<hr>";
echo "<p>Pembersihan selesai! Silakan refresh halaman utama.</p>";
