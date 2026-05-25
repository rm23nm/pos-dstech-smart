import re

with open("app/Http/Controllers/PelangganController.php", "r") as f:
    content = f.read()

old_query = """        $customer_memberships = [];
        if ($KodePelanggan) {
            $customer_memberships = DB::table('customer_memberships')
                ->select('customer_memberships.*', 'itemmaster.NamaItem')
                ->leftJoin('itemmaster', function($join) {
                    $join->on('customer_memberships.KodePaketMember', '=', 'itemmaster.KodeItem')
                         ->on('customer_memberships.RecordOwnerID', '=', 'itemmaster.RecordOwnerID');
                })
                ->where('customer_memberships.KodePelanggan', $KodePelanggan)
                ->where('customer_memberships.RecordOwnerID', Auth::user()->RecordOwnerID)
                ->orderBy('customer_memberships.ValidUntil', 'desc')
                ->get();
        }"""

new_query = """        $customer_memberships = [];
        if ($KodePelanggan) {
            $customer_memberships = DB::table('customer_memberships')
                ->select('customer_memberships.*', 'itemmaster.NamaItem', 'tkelompoklampu.NamaKelompok as KelompokLampuNama')
                ->leftJoin('itemmaster', function($join) {
                    $join->on('customer_memberships.KodePaketMember', '=', 'itemmaster.KodeItem')
                         ->on('customer_memberships.RecordOwnerID', '=', 'itemmaster.RecordOwnerID');
                })
                ->leftJoin('member_packages', function($join) {
                    $join->on('customer_memberships.KodePaketMember', '=', 'member_packages.KodePaket')
                         ->on('customer_memberships.RecordOwnerID', '=', 'member_packages.RecordOwnerID');
                })
                ->leftJoin('tkelompoklampu', function($join) {
                    $join->on('member_packages.KelompokLampu', '=', 'tkelompoklampu.KodeKelompok')
                         ->on('customer_memberships.RecordOwnerID', '=', 'tkelompoklampu.RecordOwnerID');
                })
                ->where('customer_memberships.KodePelanggan', $KodePelanggan)
                ->where('customer_memberships.RecordOwnerID', Auth::user()->RecordOwnerID)
                ->orderBy('customer_memberships.ValidUntil', 'desc')
                ->get();
        }"""

content = content.replace(old_query, new_query)

with open("app/Http/Controllers/PelangganController.php", "w") as f:
    f.write(content)

print("PelangganController updated")
