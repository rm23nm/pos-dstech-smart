<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$recordOwnerID = 'DEMO-BENGKEL-001';
$cutoff = Carbon\Carbon::now('Asia/Jakarta')->subMinutes(60);

$selesai = DB::table('bengkel_work_orders')
	->leftJoin('mekanik', function($j) use ($recordOwnerID) {
		$j->on('bengkel_work_orders.KodeMekanik', '=', 'mekanik.KodeMekanik')
		  ->where('mekanik.RecordOwnerID', '=', $recordOwnerID);
	})
	->leftJoin('kendaraan', 'bengkel_work_orders.PlatNomor', '=', 'kendaraan.PlatNomor')
	->select('bengkel_work_orders.*', 'mekanik.NamaMekanik', 'kendaraan.JenisKendaraan', 'kendaraan.Merek')
	->where('bengkel_work_orders.RecordOwnerID', $recordOwnerID)
	->where('bengkel_work_orders.StatusServis', 2) // Selesai servis
	->where('bengkel_work_orders.updated_at', '>=', $cutoff) // Belum lewat 60 menit
	->whereNotExists(function($q) {
		$q->select(DB::raw(1))
		  ->from('fakturpenjualanheader')
		  ->whereColumn('fakturpenjualanheader.NoPKB', 'bengkel_work_orders.NoPKB')
		  ->where('fakturpenjualanheader.Status', '!=', 'D');
	})
	->orderBy('bengkel_work_orders.updated_at', 'DESC')
	->get();

echo "SELESAI COUNT: " . count($selesai) . "\n";
foreach ($selesai as $s) {
    echo "NoPKB: " . $s->NoPKB . "\n";
}

// Let's check what fakturpenjualanheader has for one of them
$faktur = DB::table('fakturpenjualanheader')->where('NoPKB', 'PKB-20260527174906')->get();
echo "\nFAKTUR FOR PKB-20260527174906:\n";
echo json_encode($faktur, JSON_PRETTY_PRINT);
