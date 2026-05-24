<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=xpos', 'root', '');
$stmt = $pdo->query("SHOW PROCEDURE STATUS WHERE Db = 'xpos'");
$procs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$code = "<?php\n";
$code .= "require 'vendor/autoload.php';\n";
$code .= "\$app = require_once __DIR__.'/bootstrap/app.php';\n";
$code .= "\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);\n";
$code .= "\$kernel->bootstrap();\n";
$code .= "use Illuminate\Support\Facades\DB;\n";
$code .= "DB::statement('ALTER DATABASE `'.DB::getDatabaseName().'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');\n";

foreach($procs as $p) {
    $name = $p['Name'];
    $stmt2 = $pdo->query("SHOW CREATE PROCEDURE `$name`");
    $create = $stmt2->fetch(PDO::FETCH_ASSOC)['Create Procedure'];
    $create = preg_replace('/CREATE DEFINER=`[^`]+`@`[^`]+` PROCEDURE/', 'CREATE PROCEDURE', $create);
    
    // addslashes is tricky for SQL, base64 is safer for generating the php script
    $base64 = base64_encode($create);
    
    $code .= "DB::unprepared('DROP PROCEDURE IF EXISTS `$name`');\n";
    $code .= "DB::unprepared(base64_decode('$base64'));\n";
    $code .= "echo \"Recreated $name\\n\";\n";
}
file_put_contents('update_live_sps.php', $code);
echo "Generated update_live_sps.php\n";
