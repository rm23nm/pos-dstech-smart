<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=xpos', 'root', '');
$stmt = $pdo->query("SELECT * FROM permission WHERE PermissionName LIKE '%Controller%' OR PermissionName LIKE '%Manajemen Gate%'");
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    print_r($row);
}
