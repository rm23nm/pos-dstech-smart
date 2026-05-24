<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=xpos', 'root', '');
$stmt = $pdo->query("SELECT * FROM permission WHERE PermissionName LIKE '%Manajemen Gate%'");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row) {
    print_r($row);
}
