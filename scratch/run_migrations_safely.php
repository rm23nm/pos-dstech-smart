<?php
// Set infinite time limit and memory limit
set_time_limit(0);
ini_set('memory_limit', '512M');

// Boot Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Starting Safe Migration Process on Live...\n";

// Get list of all migration files from database/migrations
$migrationsDir = database_path('migrations');
$files = scandir($migrationsDir);

// Get already run migrations
try {
    $runMigrations = DB::table('migrations')->pluck('migration')->toArray();
} catch (\Throwable $e) {
    echo "Fatal Error: migrations table does not exist or database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Find pending migrations
$pending = [];
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $migrationName = pathinfo($file, PATHINFO_FILENAME);
        if (!in_array($migrationName, $runMigrations)) {
            $pending[] = [
                'file' => $file,
                'name' => $migrationName,
                'path' => $migrationsDir . '/' . $file
            ];
        }
    }
}

// Sort migrations by name to ensure correct order
usort($pending, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});

echo "Found " . count($pending) . " pending migrations.\n";

$batch = DB::table('migrations')->max('batch') + 1;

foreach ($pending as $p) {
    echo "Running migration: " . $p['name'] . "\n";
    try {
        // Run specific migration file
        $migrationInstance = require $p['path'];
        if (!is_object($migrationInstance)) {
            $content = file_get_contents($p['path']);
            if (preg_match('/class\s+(\w+)/i', $content, $matches)) {
                $className = $matches[1];
                $migrationInstance = new $className();
            }
        }
        
        if (is_object($migrationInstance)) {
            $migrationInstance->up();
            
            // Mark as run
            DB::table('migrations')->insert([
                'migration' => $p['name'],
                'batch' => $batch
            ]);
            echo "Migration successful: " . $p['name'] . "\n";
        } else {
            echo "Error: Could not resolve migration instance for " . $p['name'] . "\n";
        }
    } catch (\Throwable $e) {
        $message = $e->getMessage();
        
        // Check if the error is due to duplicate column, table, index or foreign key
        if (strpos($message, 'Duplicate column name') !== false || 
            strpos($message, 'already exists') !== false ||
            strpos($message, 'Base table or view already exists') !== false ||
            strpos($message, 'Duplicate key name') !== false) {
            echo "Column/Table/Key already exists. Marking migration as run: " . $p['name'] . "\n";
            try {
                DB::table('migrations')->insert([
                    'migration' => $p['name'],
                    'batch' => $batch
                ]);
                echo "Successfully marked as run.\n";
            } catch (\Throwable $insertEx) {
                echo "Failed to mark as run: " . $insertEx->getMessage() . "\n";
            }
        } else {
            echo "Migration failed with error: " . $message . "\n";
            echo "File path: " . $p['path'] . "\n";
        }
    }
    echo "----------------------------------------\n";
}

echo "Safe migration patch execution completed!\n";
