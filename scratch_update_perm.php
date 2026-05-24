<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=xpos', 'root', '');
$stmt = $pdo->prepare("UPDATE permission SET Level = 2, MenuInduk = 1, SubMenu = 1 WHERE id = 121");
$stmt->execute();
echo "Update sukses.\n";
