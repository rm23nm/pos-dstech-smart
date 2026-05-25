import re

with open("app/Http/Controllers/TicketingPoSController.php", "r") as f:
    content = f.read()

old_block = """                                DB::table('customer_memberships')->insert([
                                    'KodePelanggan' => $jsonData['KodePelanggan'],
                                    'KodePaketMember' => $package->KodePaket,
                                    'ValidFrom' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
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
                                    'maxTimePerPlay' => $package->maxTimePerPlay ?? 0,
                                    'RecordOwnerID' => $user->RecordOwnerID,
                                    'created_at' => Carbon::now('Asia/Jakarta')
                                ]);"""

content = content.replace(old_block, new_block)

with open("app/Http/Controllers/TicketingPoSController.php", "w") as f:
    f.write(content)

print("Added maxTimePerPlay to insert!")
