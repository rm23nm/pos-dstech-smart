import os
code = """
$req = new Illuminate\Http\Request();
$req->merge(['JenisPaket' => 'PAKETMEMBER', 'paketid' => '', 'tableid' => 67, 'KodeSales' => 'CASHIER', 'DurasiPaket' => 3, 'TglTransaksi' => '2026-05-25', 'JamMulai' => '18:00', 'JamSelesai' => '21:00', 'KodePelanggan' => '26010000000002', 'OpsiBayar' => 'LANGSUNG', 'MetodePembayaran' => 1, 'NominalBayar' => 0, 'ServiceType' => 'DINE_IN', 'fnbItems' => []]);
Auth::loginUsingId(1);
echo json_encode(app(App\Http\Controllers\TableOrderController::class)->storePaket($req)->getData());
"""
with open('test_tinker.php', 'w') as f:
    f.write(code)
os.system('C:\\xampp\\php\\php.exe artisan tinker < test_tinker.php')
