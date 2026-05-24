<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=xpos', 'root', '');
$stmt = $pdo->query("SELECT DISTINCT NoTransaksi FROM subscriptiondetail WHERE PermissionID = 88");
$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 0;
foreach($packages as $pkg) {
    $noTrans = $pkg['NoTransaksi'];
    // check if already exists
    $check = $pdo->prepare("SELECT COUNT(*) FROM subscriptiondetail WHERE NoTransaksi = ? AND PermissionID = ?");
    $check->execute([$noTrans, 121]);
    if ($check->fetchColumn() == 0) {
        $ins = $pdo->prepare("INSERT INTO subscriptiondetail (NoTransaksi, PermissionID) VALUES (?, ?)");
        $ins->execute([$noTrans, 121]);
        $count++;
    }
}
echo "Berhasil menyuntikkan permission 121 ke $count paket langganan.\n";
