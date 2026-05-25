import os

code = """
$roid = 'CL0010';
$customerMemberships = DB::table('customer_memberships')
            ->join('member_packages', 'customer_memberships.KodePaketMember', '=', 'member_packages.KodePaket')
            ->select('customer_memberships.*', 'member_packages.KelompokLampu', 'member_packages.NamaPaket')
            ->where('customer_memberships.RecordOwnerID', $roid)
            ->where('customer_memberships.ValidUntil', '>=', \\Carbon\\Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'))
            ->get();

$titikLampu = DB::table('titiklampu')->where('NamaTitikLampu', 'Badminton 2')->first();

echo "Member Memberships:\n";
echo json_encode($customerMemberships, JSON_PRETTY_PRINT);
echo "\nTitik Lampu:\n";
echo json_encode($titikLampu, JSON_PRETTY_PRINT);
"""

with open('test_tinker6.php', 'w') as f:
    f.write(code)

os.system('C:\\xampp\\php\\php.exe artisan tinker < test_tinker6.php')
