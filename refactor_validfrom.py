import re

# Fix in TicketingPoSController.php
with open("app/Http/Controllers/TicketingPoSController.php", "r") as f:
    content = f.read()

old_block = """                                DB::table('customer_memberships')->insert([
                                    'KodePelanggan' => $jsonData['KodePelanggan'],
                                    'KodePaketMember' => $package->KodePaket,
                                    'ValidUntil' => $newValidUntil_cm,
                                    'MaxPlay' => $addedMaxPlay,
                                    'Played' => 0,
                                    'RecordOwnerID' => $user->RecordOwnerID,
                                    'created_at' => Carbon::now('Asia/Jakarta')
                                ]);"""

new_block = """                                DB::table('customer_memberships')->insert([
                                    'KodePelanggan' => $jsonData['KodePelanggan'],
                                    'KodePaketMember' => $package->KodePaket,
                                    'ValidFrom' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                                    'ValidUntil' => $newValidUntil_cm,
                                    'MaxPlay' => $addedMaxPlay,
                                    'Played' => 0,
                                    'RecordOwnerID' => $user->RecordOwnerID,
                                    'created_at' => Carbon::now('Asia/Jakarta')
                                ]);"""

content = content.replace(old_block, new_block)

with open("app/Http/Controllers/TicketingPoSController.php", "w") as f:
    f.write(content)


# Fix in FakturPenjualanController.php
with open("app/Http/Controllers/FakturPenjualanController.php", "r") as f:
    content2 = f.read()

old_block2 = """                                DB::table('customer_memberships')->insert([
                                    'KodePelanggan' => $jsonData['KodePelanggan'],
                                    'KodePaketMember' => $package->KodePaket,
                                    'ValidUntil' => $newValidUntil_cm,
                                    'MaxPlay' => $addedMaxPlay,
                                    'Played' => 0,
                                    'maxTimePerPlay' => $package->maxTimePerPlay ?? 0,
                                    'RecordOwnerID' => Auth::user()->RecordOwnerID,
                                    'created_at' => \Carbon\Carbon::now('Asia/Jakarta')
                                ]);"""

new_block2 = """                                DB::table('customer_memberships')->insert([
                                    'KodePelanggan' => $jsonData['KodePelanggan'],
                                    'KodePaketMember' => $package->KodePaket,
                                    'ValidFrom' => \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                                    'ValidUntil' => $newValidUntil_cm,
                                    'MaxPlay' => $addedMaxPlay,
                                    'Played' => 0,
                                    'maxTimePerPlay' => $package->maxTimePerPlay ?? 0,
                                    'RecordOwnerID' => Auth::user()->RecordOwnerID,
                                    'created_at' => \Carbon\Carbon::now('Asia/Jakarta')
                                ]);"""

content2 = content2.replace(old_block2, new_block2)

with open("app/Http/Controllers/FakturPenjualanController.php", "w") as f:
    f.write(content2)

print("Fixed ValidFrom successfully!")
