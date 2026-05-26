<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$json = file_get_contents('demo_data.json');
$data = json_decode($json, true);

if (!$data) {
    echo "Failed to load demo_data.json\n";
    exit;
}

$tables = [
    'company' => 'KodePartner',
    'users' => 'id', // use id for users to prevent conflict, wait, maybe delete first?
    'settingaccount' => 'RecordOwnerID',
    'printer' => 'RecordOwnerID',
    'metodepembayaran' => 'id',
    'satuan' => 'id',
    'merk' => 'id',
    'jenisitem' => 'id',
    'gudang' => 'id',
    'supplier' => 'id',
    'itemmaster' => 'id',
    'itemwarehouses' => 'id',
];

foreach ($data as $tableName => $rows) {
    if (empty($rows)) continue;
    
    $primaryKey = $tables[$tableName] ?? 'id';
    
    echo "Importing table $tableName (" . count($rows) . " rows)\n";
    
    foreach ($rows as $row) {
        $rowArray = (array)$row;
        
        // Check if exists
        $ownerField = 'RecordOwnerID';
        if ($tableName === 'company') {
            $ownerField = 'KodePartner';
        }
        
        // For simple lookup, let's just delete the existing for these owners and re-insert, OR upsert.
        // It's safer to check by id if it has one, but id can clash between local and live.
        // It's safer to delete by RecordOwnerID and insert, EXCEPT for users/company where we might just check uniqueness.
        
        if (in_array($tableName, ['company'])) {
            $existing = DB::table($tableName)->where('KodePartner', $rowArray['KodePartner'])->first();
            if ($existing) {
                DB::table($tableName)->where('KodePartner', $rowArray['KodePartner'])->update($rowArray);
            } else {
                DB::table($tableName)->insert($rowArray);
            }
        } elseif ($tableName === 'users') {
            $existing = DB::table($tableName)->where('email', $rowArray['email'])->first();
            if ($existing) {
                DB::table($tableName)->where('email', $rowArray['email'])->update($rowArray);
            } else {
                DB::table($tableName)->insert($rowArray);
            }
        } else {
            // Check by ID if present
            if (isset($rowArray['id'])) {
                $existing = DB::table($tableName)->where('id', $rowArray['id'])->first();
                if ($existing) {
                    DB::table($tableName)->where('id', $rowArray['id'])->update($rowArray);
                } else {
                    DB::table($tableName)->insert($rowArray);
                }
            } else {
                // If no ID, just try to insert (settingaccount, printer usually don't have id in export? wait I exported raw)
                // Let's just catch exceptions
                try {
                    // Try to update first based on RecordOwnerID if it's a 1-to-1 table
                    if (in_array($tableName, ['settingaccount', 'printer'])) {
                        $existing = DB::table($tableName)->where('RecordOwnerID', $rowArray['RecordOwnerID'])->first();
                        if ($existing) {
                            DB::table($tableName)->where('RecordOwnerID', $rowArray['RecordOwnerID'])->update($rowArray);
                        } else {
                            DB::table($tableName)->insert($rowArray);
                        }
                    } else {
                        DB::table($tableName)->insert($rowArray);
                    }
                } catch (\Exception $e) {
                    echo "  Skipped duplicate or error: " . $e->getMessage() . "\n";
                }
            }
        }
    }
}

echo "Done importing demo data.\n";
