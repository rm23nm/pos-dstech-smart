<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=xpos', 'root', '');
$stmt = $pdo->prepare("UPDATE permission SET SubMenu = 0 WHERE PermissionName = 'Manajemen Gate'");
$stmt->execute();
echo "Updated Manajemen Gate SubMenu to 0\n";
