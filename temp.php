
$titik = DB::table('titiklampu')->where('NamaTitikLampu', 'LIKE', '%Meja 3%')->first();
$orders = DB::table('tableorderheader')->where('tableid', $titik->id)->orderBy('id', 'desc')->limit(3)->get();
echo json_encode(['titik_status' => $titik->Status, 'orders' => $orders]);
