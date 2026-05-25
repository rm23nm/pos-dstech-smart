import re

with open("app/Http/Controllers/FakturPenjualanController.php", "r") as f:
    content = f.read()

injection = """
                            // --- NEW: Insert to customer_memberships ---
                            $existingMembership = DB::table('customer_memberships')
                                ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
                                ->where('KodePelanggan', $jsonData['KodePelanggan'])
                                ->where('KodePaketMember', $package->KodePaket)
                                ->orderBy('ValidUntil', 'desc')
                                ->first();

                            $currentValidUntil_cm = \\\\Carbon\\\\Carbon::now('Asia/Jakarta');
                            if ($existingMembership && $existingMembership->ValidUntil) {
                                $exDate = \\\\Carbon\\\\Carbon::parse($existingMembership->ValidUntil, 'Asia/Jakarta');
                                if ($exDate->isFuture()) {
                                    $currentValidUntil_cm = $exDate;
                                }
                            }
                            
                            $addedDays = ($package->ValidDays ?? 30) * $key['Qty'];
                            $newValidUntil_cm = $currentValidUntil_cm->addDays($addedDays)->format('Y-m-d H:i:s');
                            $addedMaxPlay = ($package->MaxPlay ?? 0) * $key['Qty'];
                            
                            if ($existingMembership && \\\\Carbon\\\\Carbon::parse($existingMembership->ValidUntil, 'Asia/Jakarta')->isFuture()) {
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
                                    'maxTimePerPlay' => $package->maxTimePerPlay ?? 0,
                                    'RecordOwnerID' => Auth::user()->RecordOwnerID,
                                    'created_at' => \\\\Carbon\\\\Carbon::now('Asia/Jakarta')
                                ]);
                            }
                            // -------------------------------------------
"""

# There are 3 blocks that look like this:
# $newValidUntil = $currentValidUntil->addDays(($package->ValidDays ?? 30) * $key['Qty'])->format('Y-m-d');
# OR
# $newValidUntil = $currentValidUntil->addDays(($package->ValidDays ?? 30) * $key['Qty'])->format('Y-m-d');
# $maxPlay = ($package->MaxPlay ?? 0) ? (($package->MaxPlay ?? 0) * $key['Qty']) : 0;

# We will inject the logic right before the $updateData = [ array declaration

pattern = r"(\$newValidUntil = .*?format\('Y-m-d'\);(?:\s*\$maxPlay = .*?;)?\s*)(\$updateData = \[)"
new_content = re.sub(pattern, r"\1" + injection + r"\n\2", content)

with open("app/Http/Controllers/FakturPenjualanController.php", "w") as f:
    f.write(new_content)

print("Done refactoring FakturPenjualanController.")
