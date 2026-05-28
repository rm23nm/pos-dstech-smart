<?php
$content = file_get_contents("resources/views/Transaksi/Penjualan/PoS/BengkelPoS.blade.php");
preg_match_all('/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/', $content, $matches);
$vars = array_unique($matches[1]);
sort($vars);
echo implode(", ", $vars);
