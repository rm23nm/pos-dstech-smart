<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=xpos', 'root', '');
foreach($pdo->query('DESCRIBE tiket_masuk') as $row) {
    echo $row['Field'] . " | ";
}
echo "\n";
