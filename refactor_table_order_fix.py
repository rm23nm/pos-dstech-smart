import re

with open("app/Http/Controllers/TableOrderController.php", "r") as f:
    content = f.read()

old_query = """        $customerMemberships = DB::table('customer_memberships')
            ->join('member_packages', 'customer_memberships.KodePaketMember', '=', 'member_packages.KodePaket')
            ->select('customer_memberships.*', 'member_packages.KelompokLampu')
            ->where('customer_memberships.RecordOwnerID', $roid)
            ->where('customer_memberships.Status', 'ACTIVE')
            ->where('customer_memberships.ValidUntil', '>=', Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'))
            ->get();"""

new_query = """        $customerMemberships = DB::table('customer_memberships')
            ->join('member_packages', 'customer_memberships.KodePaketMember', '=', 'member_packages.KodePaket')
            ->select('customer_memberships.*', 'member_packages.KelompokLampu')
            ->where('customer_memberships.RecordOwnerID', $roid)
            ->where('customer_memberships.ValidUntil', '>=', Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'))
            ->get();"""

content = content.replace(old_query, new_query)

with open("app/Http/Controllers/TableOrderController.php", "w") as f:
    f.write(content)

print("Fixed TableOrderController customerMemberships query")
