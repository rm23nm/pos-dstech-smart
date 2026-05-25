import re

with open("app/Http/Controllers/TableOrderController.php", "r") as f:
    content = f.read()

# I need to replace the PAKETMEMBER validation block inside store().
old_block = """            if ($request->input('JenisPaket') == 'PAKETMEMBER') {
                $pelanggan = DB::table('pelanggan')
                    ->where('KodePelanggan', $request->input('KodePelanggan'))
                    ->where('RecordOwnerID', $roid)
                    ->first();

                if (!$pelanggan) {
                    return response()->json(['success' => false, 'message' => 'Pelanggan tidak ditemukan atau belum dipilih.']);
                }
                if ($pelanggan->IsMember != 1 || $pelanggan->isPaidMembership != 1) {
                    return response()->json(['success' => false, 'message' => 'Pelanggan bukan member aktif atau paket belum dibayar.']);
                }
                if ($pelanggan->ValidUntil && Carbon::parse($pelanggan->ValidUntil)->isPast()) {
                    return response()->json(['success' => false, 'message' => 'Masa berlaku paket member telah habis (Expired: ' . $pelanggan->ValidUntil . ').']);
                }
                if ($pelanggan->MaxPlay > 0 && $pelanggan->Played >= $pelanggan->MaxPlay) {
                    return response()->json(['success' => false, 'message' => 'Kuota kunjungan paket member sudah habis (' . $pelanggan->Played . '/' . $pelanggan->MaxPlay . ').']);
                }

                if ($pelanggan->KodePaketMember) {
                    $paketMember = DB::table('itemmaster')
                        ->where('KodeItem', $pelanggan->KodePaketMember)
                        ->where('RecordOwnerID', $roid)
                        ->first();
                        
                    $memberConfig = DB::table('member_packages')
                        ->where('KodePaket', $pelanggan->KodePaketMember)
                        ->where('RecordOwnerID', $roid)
                        ->first();

                    if ($memberConfig && $memberConfig->KategoriPaket !== 'HIBURAN') {
                        return response()->json(['success' => false, 'message' => 'Paket member Anda (' . $paketMember->NamaItem . ') tidak diperuntukkan untuk layanan Hiburan (Billing/Meja), melainkan khusus ' . $memberConfig->KategoriPaket . '.']);
                    }
                    
                    if ($memberConfig && $memberConfig->Tipe === 'QUOTA') {
                        if ($pelanggan->MaxPlay > 0 && $pelanggan->Played >= $pelanggan->MaxPlay) {
                            return response()->json(['success' => false, 'message' => 'Kuota kunjungan paket member Anda (' . $paketMember->NamaItem . ') sudah habis.']);
                        }
                    }
                        
                    if ($paketMember && $table->NamaKelompok) {
                        $namaPaket = strtolower($paketMember->NamaItem);
                        $namaGrup = strtolower($table->NamaKelompok);
                        $namaGrupClean = str_replace(['meja ', 'lapangan ', 'ruang ', 'room '], '', $namaGrup);
                        
                        if (str_contains($namaGrupClean, 'billiard')) {
                            $namaGrupClean = 'billiar';
                        }
                        
                        if (strpos($namaPaket, trim($namaGrupClean)) === false) {
                            return response()->json(['success' => false, 'message' => 'Paket member Anda (' . $paketMember->NamaItem . ') tidak dapat digunakan untuk kategori ' . $table->NamaKelompok . '.']);
                        }
                    }
                }
                
                $memberConfigFinal = DB::table('member_packages')->where('KodePaket', $pelanggan->KodePaketMember)->where('RecordOwnerID', $roid)->first();
                $hargaPaket = 0;
                
                if ($memberConfigFinal && $memberConfigFinal->Tipe === 'DISCOUNT') {
                    $normalPriceTable = DB::table('pakettransaksi')->where('id', $request->input('paketid'))->where('RecordOwnerID', $roid)->first();
                    if ($normalPriceTable) {
                        $diskonPersen = $memberConfigFinal->DiskonBelanja ?? 0;
                        $hargaPaket = $normalPriceTable->HargaNormal - ($normalPriceTable->HargaNormal * ($diskonPersen / 100));
                    }
                }
                
            } else {"""

new_block = """            if ($request->input('JenisPaket') == 'PAKETMEMBER') {
                $pelanggan = DB::table('pelanggan')
                    ->where('KodePelanggan', $request->input('KodePelanggan'))
                    ->where('RecordOwnerID', $roid)
                    ->first();

                if (!$pelanggan) {
                    return response()->json(['success' => false, 'message' => 'Pelanggan tidak ditemukan atau belum dipilih.']);
                }
                if ($pelanggan->IsMember != 1 || $pelanggan->isPaidMembership != 1) {
                    return response()->json(['success' => false, 'message' => 'Pelanggan bukan member aktif atau paket belum dibayar.']);
                }

                // Check active memberships in customer_memberships table matching the category
                $namaGrup = strtolower($table->NamaKelompok);
                $namaGrupClean = str_replace(['meja ', 'lapangan ', 'ruang ', 'room '], '', $namaGrup);
                if (str_contains($namaGrupClean, 'billiard')) {
                    $namaGrupClean = 'billiar';
                }
                $namaGrupClean = trim($namaGrupClean);

                $activeMemberships = DB::table('customer_memberships')
                    ->join('member_packages', function($join) {
                        $join->on('customer_memberships.KodePaketMember', '=', 'member_packages.KodePaket')
                             ->on('customer_memberships.RecordOwnerID', '=', 'member_packages.RecordOwnerID');
                    })
                    ->join('itemmaster', function($join) {
                        $join->on('customer_memberships.KodePaketMember', '=', 'itemmaster.KodeItem')
                             ->on('customer_memberships.RecordOwnerID', '=', 'itemmaster.RecordOwnerID');
                    })
                    ->where('customer_memberships.KodePelanggan', $request->input('KodePelanggan'))
                    ->where('customer_memberships.RecordOwnerID', $roid)
                    ->where('customer_memberships.ValidUntil', '>=', Carbon::now('Asia/Jakarta'))
                    ->whereRaw('(customer_memberships.MaxPlay = 0 OR customer_memberships.Played < customer_memberships.MaxPlay)')
                    ->select('customer_memberships.*', 'member_packages.KategoriPaket', 'member_packages.Tipe', 'member_packages.DiskonBelanja', 'itemmaster.NamaItem', 'member_packages.TargetKategori')
                    ->get();

                if ($activeMemberships->isEmpty()) {
                    return response()->json(['success' => false, 'message' => 'Member tidak memiliki paket aktif yang tersedia atau kuota sudah habis.']);
                }

                $validMembership = null;
                foreach ($activeMemberships as $am) {
                    if ($am->KategoriPaket !== 'HIBURAN') continue;
                    
                    // Validate category
                    if ($am->TargetKategori) {
                        if (strtolower(trim($am->TargetKategori)) === $namaGrupClean) {
                            $validMembership = $am;
                            break;
                        }
                    } else {
                        // Fallback to name checking
                        $namaPaket = strtolower($am->NamaItem);
                        if (strpos($namaPaket, $namaGrupClean) !== false) {
                            $validMembership = $am;
                            break;
                        }
                    }
                }

                if (!$validMembership) {
                    return response()->json(['success' => false, 'message' => 'Member memiliki paket aktif, tetapi tidak ada yang berlaku untuk kategori ' . $table->NamaKelompok . '.']);
                }

                // Simpan ID membership yang dipakai ke session atau update played
                // Deduct Play Quota Immediately
                DB::table('customer_memberships')
                    ->where('id', $validMembership->id)
                    ->update([
                        'Played' => DB::raw('Played + 1')
                    ]);
                    
                $hargaPaket = 0;
                
                if ($validMembership->Tipe === 'DISCOUNT') {
                    $normalPriceTable = DB::table('pakettransaksi')->where('id', $request->input('paketid'))->where('RecordOwnerID', $roid)->first();
                    if ($normalPriceTable) {
                        $diskonPersen = $validMembership->DiskonBelanja ?? 0;
                        $hargaPaket = $normalPriceTable->HargaNormal - ($normalPriceTable->HargaNormal * ($diskonPersen / 100));
                    }
                }
                
            } else {"""

content = content.replace(old_block, new_block)

with open("app/Http/Controllers/TableOrderController.php", "w") as f:
    f.write(content)

print("Done refactoring TableOrderController store()")
