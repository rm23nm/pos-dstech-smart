import os
code = """
$roid = 'CL0010';
echo json_encode(DB::table('customer_memberships')->join('member_packages', 'customer_memberships.KodePaketMember', '=', 'member_packages.KodePaket')->select('customer_memberships.*', 'member_packages.KelompokLampu', 'member_packages.NamaPaket')->where('customer_memberships.RecordOwnerID', $roid)->where('customer_memberships.ValidUntil', '>=', \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'))->get());
"""
with open('test_tinker4.php', 'w') as f:
    f.write(code)
os.system('C:\\xampp\\php\\php.exe artisan tinker < test_tinker4.php')
