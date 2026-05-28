<?php
use Illuminate\Support\Facades\DB;
$comp = DB::table('company')->where('KodePartner', 'DEMO-BENGKEL-001')->first();
echo "KodePartner: " . $comp->KodePartner . " | " . json_encode($comp);
