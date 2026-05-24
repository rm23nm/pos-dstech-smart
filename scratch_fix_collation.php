<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=xpos', 'root', '');

// 1. Get all procedures
$stmt = $pdo->query("SHOW PROCEDURE STATUS WHERE Db = 'xpos'");
$procedures = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sp_definitions = [];
foreach ($procedures as $proc) {
    $name = $proc['Name'];
    $stmt2 = $pdo->query("SHOW CREATE PROCEDURE `$name`");
    $create_stmt = $stmt2->fetch(PDO::FETCH_ASSOC)['Create Procedure'];
    $sp_definitions[$name] = $create_stmt;
}

// 2. Change Database Collation
$pdo->exec("ALTER DATABASE xpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
echo "Database collation changed to utf8mb4_unicode_ci\n";

// 3. Drop and Recreate Procedures
foreach ($sp_definitions as $name => $create_stmt) {
    $pdo->exec("DROP PROCEDURE IF EXISTS `$name`");
    // Remove the definer part to avoid issues and let it be created with the current default collation
    $create_stmt = preg_replace('/CREATE DEFINER=`[^`]+`@`[^`]+` PROCEDURE/', 'CREATE PROCEDURE', $create_stmt);
    $pdo->exec($create_stmt);
    echo "Recreated $name\n";
}

echo "All procedures fixed!\n";
