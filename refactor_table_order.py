import re

with open("app/Http/Controllers/TableOrderController.php", "r") as f:
    content = f.read()

old_query = """        $pelanggan = Pelanggan::selectRaw($sql)
                    ->where('RecordOwnerID','=',$roid)
                    ->where('Status','=',1)->get();
        $metodepembayaran = MetodePembayaran::where('RecordOwnerID','=',$roid)->get();"""

new_query = """        $pelanggan = Pelanggan::selectRaw($sql)
                    ->where('RecordOwnerID','=',$roid)
                    ->where('Status','=',1)->get();
        
        $customerMemberships = DB::table('customer_memberships')
            ->join('member_packages', 'customer_memberships.KodePaketMember', '=', 'member_packages.KodePaket')
            ->select('customer_memberships.*', 'member_packages.KelompokLampu')
            ->where('customer_memberships.RecordOwnerID', $roid)
            ->where('customer_memberships.Status', 'ACTIVE')
            ->where('customer_memberships.ValidUntil', '>=', Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'))
            ->get();

        $metodepembayaran = MetodePembayaran::where('RecordOwnerID','=',$roid)->get();"""

content = content.replace(old_query, new_query)

old_return = """return view('Transaksi.Penjualan.PoS.billing_new', compact(
            'company', 'titiklampu', 'kelompoklampu', 'pelanggan', 'metodepembayaran', 
            'itemmaster', 'grupmenu', 'sales', 'fakturpenjualandetail'
        ));"""

new_return = """return view('Transaksi.Penjualan.PoS.billing_new', compact(
            'company', 'titiklampu', 'kelompoklampu', 'pelanggan', 'metodepembayaran', 
            'itemmaster', 'grupmenu', 'sales', 'fakturpenjualandetail', 'customerMemberships'
        ));"""

content = content.replace(old_return, new_return)

with open("app/Http/Controllers/TableOrderController.php", "w") as f:
    f.write(content)

print("TableOrderController updated!")
