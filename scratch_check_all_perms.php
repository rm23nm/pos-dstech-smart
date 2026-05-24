<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=xpos', 'root', '');
$stmt = $pdo->query("SELECT id, PermissionName, Level, MenuInduk FROM permission WHERE Status = 1 ORDER BY id");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row) {
    echo str_pad($row['id'], 5) . str_pad($row['Level'], 3) . str_pad($row['MenuInduk'], 5) . $row['PermissionName'] . "\n";
}
