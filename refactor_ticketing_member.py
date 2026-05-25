import re

with open("app/Http/Controllers/TicketingPoSController.php", "r") as f:
    content = f.read()

old_block = """                        if ($package) {
                            $currentValidUntil = $pelanggan->ValidUntil ? Carbon::parse($pelanggan->ValidUntil) : Carbon::now();
                            if ($currentValidUntil->isPast()) {
                                $currentValidUntil = Carbon::now();
                            }
                            $newValidUntil = $currentValidUntil->addDays(($package->ValidDays ?? 30) * $item['qty'])->format('Y-m-d');
                            $maxPlay = ($package->MaxPlay ?? 0) ? (($package->MaxPlay ?? 0) * $item['qty']) : 0;
                            $updateData = [
                                'isPaidMembership' => 1,
                                'ValidUntil' => $newValidUntil,
                                'TglBerlanggananPaketBulanan' => Carbon::now()->format('Y-m-d'),
                                'MemberPrice' => $package->Harga,
                                'Played' => 0,
                                'KodePaketMember' => $item['code']
                            ];

                            if ($maxPlay > 0) {
                                $updateData['MaxPlay'] = DB::raw("COALESCE(MaxPlay, 0) + $maxPlay");
                            }
                            if (($package->maxTimePerPlay ?? 0) > 0) {
                                $updateData['maxTimePerPlay'] = $package->maxTimePerPlay;
                            }

                            DB::table('pelanggan')
                                ->where('RecordOwnerID', $user->RecordOwnerID)
                                ->where('KodePelanggan', $jsonData['KodePelanggan'])
                                ->update($updateData);

                        } else {"""

new_block = """                        if ($package) {
                            $currentValidUntil = $pelanggan->ValidUntil ? Carbon::parse($pelanggan->ValidUntil) : Carbon::now();
                            if ($currentValidUntil->isPast()) {
                                $currentValidUntil = Carbon::now();
                            }
                            $newValidUntil = $currentValidUntil->addDays(($package->ValidDays ?? 30) * $item['qty'])->format('Y-m-d');
                            $maxPlay = ($package->MaxPlay ?? 0) ? (($package->MaxPlay ?? 0) * $item['qty']) : 0;
                            
                            // --- NEW: Insert/Update customer_memberships ---
                            $existingMembership = DB::table('customer_memberships')
                                ->where('RecordOwnerID', $user->RecordOwnerID)
                                ->where('KodePelanggan', $jsonData['KodePelanggan'])
                                ->where('KodePaketMember', $package->KodePaket)
                                ->orderBy('ValidUntil', 'desc')
                                ->first();

                            $currentValidUntil_cm = Carbon::now('Asia/Jakarta');
                            if ($existingMembership && $existingMembership->ValidUntil) {
                                $exDate = Carbon::parse($existingMembership->ValidUntil, 'Asia/Jakarta');
                                if ($exDate->isFuture()) {
                                    $currentValidUntil_cm = $exDate;
                                }
                            }
                            
                            $addedDays = ($package->ValidDays ?? 30) * $item['qty'];
                            $newValidUntil_cm = $currentValidUntil_cm->addDays($addedDays)->format('Y-m-d');
                            $addedMaxPlay = ($package->MaxPlay ?? 0) * $item['qty'];
                            
                            if ($existingMembership && Carbon::parse($existingMembership->ValidUntil, 'Asia/Jakarta')->isFuture()) {
                                DB::table('customer_memberships')
                                    ->where('id', $existingMembership->id)
                                    ->update([
                                        'ValidUntil' => $newValidUntil_cm,
                                        'MaxPlay' => DB::raw("MaxPlay + $addedMaxPlay")
                                    ]);
                            } else {
                                DB::table('customer_memberships')->insert([
                                    'KodePelanggan' => $jsonData['KodePelanggan'],
                                    'KodePaketMember' => $package->KodePaket,
                                    'ValidUntil' => $newValidUntil_cm,
                                    'MaxPlay' => $addedMaxPlay,
                                    'Played' => 0,
                                    'RecordOwnerID' => $user->RecordOwnerID,
                                    'created_at' => Carbon::now('Asia/Jakarta')
                                ]);
                            }
                            // -------------------------------------------

                            $updateData = [
                                'isPaidMembership' => 1,
                                'ValidUntil' => $newValidUntil,
                                'TglBerlanggananPaketBulanan' => Carbon::now()->format('Y-m-d'),
                                'MemberPrice' => $package->Harga,
                                'Played' => 0,
                                'KodePaketMember' => $item['code']
                            ];

                            if ($maxPlay > 0) {
                                $updateData['MaxPlay'] = DB::raw("COALESCE(MaxPlay, 0) + $maxPlay");
                            }
                            if (($package->maxTimePerPlay ?? 0) > 0) {
                                $updateData['maxTimePerPlay'] = $package->maxTimePerPlay;
                            }

                            DB::table('pelanggan')
                                ->where('RecordOwnerID', $user->RecordOwnerID)
                                ->where('KodePelanggan', $jsonData['KodePelanggan'])
                                ->update($updateData);

                        } else {"""

content = content.replace(old_block, new_block)

with open("app/Http/Controllers/TicketingPoSController.php", "w") as f:
    f.write(content)

print("Replaced successfully!")
