<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$recordOwnerID = 'DEMO-BENGKEL-001';
$pkb = DB::table('bengkel_work_orders')
	->where('RecordOwnerID', $recordOwnerID)
	->where('StatusServis', 2)
	->whereNotExists(function($query) {
		$query->select(DB::raw(1))
			  ->from('fakturpenjualanheader')
			  ->whereColumn('fakturpenjualanheader.NoPKB', 'bengkel_work_orders.NoPKB')
			  ->where('fakturpenjualanheader.Status', '!=', 'D');
	})
	->get();

echo "getTarikPKB result:\n";
echo json_encode($pkb, JSON_PRETTY_PRINT);
