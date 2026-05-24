<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'last_activity')) {
    \Illuminate\Support\Facades\DB::statement("ALTER TABLE `users` ADD `last_activity` VARCHAR(255) NULL");
    echo "Added last_activity column to users table.\n";
}

$sqlFile = 'C:\Users\DSTech  Smart\Downloads\xpos.sql';
if (!file_exists($sqlFile)) {
    die("File not found!");
}

$contents = file_get_contents($sqlFile);
if ($contents === false) {
    die("Could not read file");
}

\Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
\Illuminate\Support\Facades\DB::table('users')->truncate();
\Illuminate\Support\Facades\DB::table('userrole')->truncate();

// Find INSERT INTO users
$patternUsers = '/INSERT INTO `?users`?\s+.*?(?:\nINSERT INTO|\Z|(?:;\r?\n))/is';
if (preg_match_all($patternUsers, $contents, $matches)) {
    foreach ($matches[0] as $match) {
        if (stripos($match, 'users') !== false && stripos($match, 'userrole') === false && stripos($match, 'permissionrole') === false) {
            $stmt = trim(preg_replace('/;\s*$/', '', $match));
            if ($stmt) {
                try {
                    \Illuminate\Support\Facades\DB::unprepared($stmt);
                    echo "Inserted users successfully.\n";
                } catch (\Exception $e) {
                    echo "Error users: " . $e->getMessage() . "\n";
                }
            }
        }
    }
}

// Find INSERT INTO userrole
$patternUserRole = '/INSERT INTO `?userrole`?\s+.*?(?:\nINSERT INTO|\Z|(?:;\r?\n))/is';
if (preg_match_all($patternUserRole, $contents, $matches)) {
    foreach ($matches[0] as $match) {
        if (stripos($match, 'userrole') !== false && stripos($match, 'permissionrole') === false) {
            $stmt = trim(preg_replace('/;\s*$/', '', $match));
            if ($stmt) {
                try {
                    \Illuminate\Support\Facades\DB::unprepared($stmt);
                    echo "Inserted userrole successfully.\n";
                } catch (\Exception $e) {
                    echo "Error userrole: " . $e->getMessage() . "\n";
                }
            }
        }
    }
}

\Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
echo "Restore complete.\n";

$countUsers = \Illuminate\Support\Facades\DB::table('users')->count();
echo "Users in DB now: " . $countUsers . "\n";
