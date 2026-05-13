<?php

echo "<h2>Memulai Sinkronisasi GitHub ke Live...</h2>";
echo "<pre>";

// Jalankan git pull
$output = shell_exec("cd /www/wwwroot/pos.dstechsmart.com && git pull origin main 2>&1");

if ($output) {
    echo "<b>Hasil:</b>\n" . htmlspecialchars($output);
} else {
    echo "<b>Gagal:</b> Tidak ada respon dari perintah git pull. Pastikan Git terinstall di server.";
}

echo "</pre>";
echo "<hr>";
echo "<p>Jika berhasil, silakan hapus file ini demi keamanan.</p>";
