import os

ctrl_path = 'app/Http/Controllers/TableOrderController.php'
with open(ctrl_path, 'r') as f:
    ctrl_content = f.read()

old_return = """        return view("Transaksi.Penjualan.PoS.billing_new",[
            'paket' => $paket,
            'titiklampu' => $titiklampu,
            'titiklampuoption' => $titiklampuoption,
            'company' => $company,
            'sales' => $sales,
            'pelanggan' => $pelanggan,
            'metodepembayaran' => $metodepembayaran,
            'itemmaster' => $itemmaster,
            'midtransclientkey' => $midtransclientkey,
            'MetodePembayaranAutoID' => $MetodePembayaranAutoID,
            'kelompoklampu' => $kelompoklampu,
            'gruppelanggan' => $gruppelanggan,
            'oKodeSales' => Auth::user()->KodeSales,
            'jenisLangganan' => $jenisLangganan
        ]);"""

new_return = """        return view("Transaksi.Penjualan.PoS.billing_new",[
            'paket' => $paket,
            'titiklampu' => $titiklampu,
            'titiklampuoption' => $titiklampuoption,
            'company' => $company,
            'sales' => $sales,
            'pelanggan' => $pelanggan,
            'metodepembayaran' => $metodepembayaran,
            'itemmaster' => $itemmaster,
            'midtransclientkey' => $midtransclientkey,
            'MetodePembayaranAutoID' => $MetodePembayaranAutoID,
            'kelompoklampu' => $kelompoklampu,
            'gruppelanggan' => $gruppelanggan,
            'oKodeSales' => Auth::user()->KodeSales,
            'jenisLangganan' => $jenisLangganan,
            'customerMemberships' => $customerMemberships
        ]);"""

ctrl_content = ctrl_content.replace(old_return, new_return)

with open(ctrl_path, 'w') as f:
    f.write(ctrl_content)
