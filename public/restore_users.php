<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sqlFile = 'C:\Users\DSTech  Smart\Downloads\xpos.sql';
if (!file_exists($sqlFile)) {
    die("File not found!");
}

$handle = fopen($sqlFile, "r");
if ($handle) {
    \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    \Illuminate\Support\Facades\DB::table('users')->truncate();
    \Illuminate\Support\Facades\DB::table('userrole')->truncate();

    $insideTable = false;
    $targetTables = ['users', '`users`', 'userrole', '`userrole`'];
    $currentTable = '';

    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        if ($line === '') continue;

        if (stripos($line, 'INSERT INTO') === 0) {
            $insideTable = false;
            foreach ($targetTables as $table) {
                if (stripos($line, "INSERT INTO $table") === 0) {
                    $insideTable = true;
                    $currentTable = $table;
                    break;
                }
            }
        }
        
        if (stripos($line, 'CREATE TABLE') === 0 || stripos($line, 'ALTER TABLE') === 0) {
            $insideTable = false;
        }
        
        if (strpos($line, '--') === 0 || strpos($line, '/*') === 0) {
            continue;
        }

        if ($insideTable) {
            try {
                \Illuminate\Support\Facades\DB::unprepared($line);
            } catch (\Exception $e) {
                // Ignore or log
            }
        }
    }
    
    \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    fclose($handle);
    echo "Users and UserRoles restored successfully!\n";
} else {
    echo "Error opening file.";
}
