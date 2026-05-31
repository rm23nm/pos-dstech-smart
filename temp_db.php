
<?php
$titik = DB::table('titiklampu')->where('NamaTitikLampu', 'LIKE', '%Meja 3%')->first();
$orders = DB::table('tableorderheader')->where('tableid', $titik->id)->orderBy('id', 'desc')->limit(3)->get();
echo "Titik Status: " . $titik->Status . "\n";
foreach($orders as $o) {
    echo "Order: " . $o->NoTransaksi . " | Status: " . $o->Status . " | DocStatus: " . $o->DocumentStatus . " | JamMulai: " . $o->JamMulai . "\n";
}
