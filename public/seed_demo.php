<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $user = DB::table('users')->where('email', 'demoresto@pos.dstechsmart.com')->first();
    if ($user) {
        echo "Demo Resto RecordOwnerID: " . $user->RecordOwnerID . "\n";
        $owner_id = $user->RecordOwnerID;

        // Check tipe order resto
        $tipe = DB::table('tipeorderresto')->where('RecordOwnerID', $owner_id)->get();
        echo "Tipe Order: " . count($tipe) . "\n";

        // Check meja
        $meja = DB::table('meja')->where('RecordOwnerID', $owner_id)->get();
        echo "Meja: " . count($meja) . "\n";

        if (count($tipe) == 0) {
            DB::table('tipeorderresto')->insert([
                ['NamaTipeOrder' => 'Dine In', 'RecordOwnerID' => $owner_id],
                ['NamaTipeOrder' => 'Take Away', 'RecordOwnerID' => $owner_id],
                ['NamaTipeOrder' => 'Gojek', 'RecordOwnerID' => $owner_id],
                ['NamaTipeOrder' => 'Grab', 'RecordOwnerID' => $owner_id]
            ]);
            echo "Inserted default Tipe Order Resto.\n";
        }

        if (count($meja) == 0) {
            // insert kelompok meja first
            $kelompok = DB::table('kelompokmeja')->where('RecordOwnerID', $owner_id)->first();
            if (!$kelompok) {
                $kid = DB::table('kelompokmeja')->insertGetId([
                    'NamaKelompok' => 'Lantai 1',
                    'RecordOwnerID' => $owner_id
                ]);
            } else {
                $kid = $kelompok->id;
            }

            DB::table('meja')->insert([
                ['NamaMeja' => 'Meja 1', 'KelompokMejaID' => $kid, 'RecordOwnerID' => $owner_id, 'Status' => 1],
                ['NamaMeja' => 'Meja 2', 'KelompokMejaID' => $kid, 'RecordOwnerID' => $owner_id, 'Status' => 1],
                ['NamaMeja' => 'Meja 3', 'KelompokMejaID' => $kid, 'RecordOwnerID' => $owner_id, 'Status' => 1],
                ['NamaMeja' => 'Meja 4', 'KelompokMejaID' => $kid, 'RecordOwnerID' => $owner_id, 'Status' => 1],
                ['NamaMeja' => 'Meja 5', 'KelompokMejaID' => $kid, 'RecordOwnerID' => $owner_id, 'Status' => 1],
            ]);
            echo "Inserted default Meja.\n";
        }

    } else {
        echo "User demoresto not found.\n";
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
