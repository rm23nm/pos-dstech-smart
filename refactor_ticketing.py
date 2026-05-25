import re

with open("app/Http/Controllers/TicketingPoSController.php", "r") as f:
    content = f.read()

new_method = """
    public function checkInMember(Request $request)
    {
        $user = Auth::user();
        $uid = $request->input('RFID_UID');

        if (!$uid) {
            return response()->json(['success' => false, 'message' => 'Kartu RFID harus discan.']);
        }

        // Cari pelanggan
        $member = DB::table('pelanggan')
                    ->where(function($q) use ($uid) {
                        $q->where('RFID_UID', $uid)
                          ->orWhere('Keterangan', $uid)
                          ->orWhere('KodePelanggan', $uid);
                    })
                    ->where('RecordOwnerID', $user->RecordOwnerID)
                    ->first();

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Kartu Member tidak terdaftar.']);
        }

        // Cari semua paket aktif pelanggan
        $activeMemberships = DB::table('customer_memberships')
            ->join('member_packages', function($join) {
                $join->on('customer_memberships.KodePaketMember', '=', 'member_packages.KodePaket')
                     ->on('customer_memberships.RecordOwnerID', '=', 'member_packages.RecordOwnerID');
            })
            ->join('itemmaster', function($join) {
                $join->on('customer_memberships.KodePaketMember', '=', 'itemmaster.KodeItem')
                     ->on('customer_memberships.RecordOwnerID', '=', 'itemmaster.RecordOwnerID');
            })
            ->where('customer_memberships.KodePelanggan', $member->KodePelanggan)
            ->where('customer_memberships.RecordOwnerID', $user->RecordOwnerID)
            ->where('customer_memberships.ValidUntil', '>=', Carbon::now('Asia/Jakarta'))
            ->select('customer_memberships.*', 'member_packages.KategoriPaket', 'itemmaster.NamaItem')
            ->get();

        if ($activeMemberships->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Member ini tidak memiliki paket aktif yang tersedia atau sudah kedaluwarsa.']);
        }

        $validMembership = null;

        foreach ($activeMemberships as $am) {
            // Abaikan paket meja/hiburan jika check-in dilakukan dari Ticketing POS (manual kasir gym/kolam)
            if ($am->KategoriPaket === 'HIBURAN') continue;

            // Cek kuota
            if ($am->MaxPlay == 0 || $am->Played < $am->MaxPlay) {
                $validMembership = $am;
                break;
            }
        }

        if (!$validMembership) {
            return response()->json(['success' => false, 'message' => 'Member memiliki paket aktif, namun kuota kunjungannya sudah habis atau paket bukan untuk Gym/Kolam.']);
        }

        // Potong kuota
        DB::table('customer_memberships')
            ->where('id', $validMembership->id)
            ->update([
                'Played' => DB::raw('Played + 1')
            ]);
            
        // (Opsional) Rekam ke riwayat kunjungan jika diperlukan
        try {
            DB::table('tiket_masuk')->insert([
                'NoTransaksi' => 'CHECKIN-MEMBER-' . time(),
                'KodeItem' => $validMembership->KodePaketMember,
                'BarcodeTiket' => $member->KodePelanggan,
                'Status' => 1,
                'WaktuPakai' => Carbon::now('Asia/Jakarta'),
                'RecordOwnerID' => $user->RecordOwnerID,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon::now('Asia/Jakarta')
            ]);
        } catch (\Exception $e) {}

        return response()->json([
            'success' => true,
            'message' => 'Check-In Berhasil untuk ' . $member->NamaPelanggan . ' menggunakan paket ' . $validMembership->NamaItem . '.',
            'member_name' => $member->NamaPelanggan,
            'package_name' => $validMembership->NamaItem,
            'played' => $validMembership->Played + 1,
            'max_play' => $validMembership->MaxPlay == 0 ? 'Unlimited' : $validMembership->MaxPlay
        ]);
    }
"""

# Insert before the last closing brace
last_brace_index = content.rfind('}')
if last_brace_index != -1:
    content = content[:last_brace_index] + new_method + content[last_brace_index:]

with open("app/Http/Controllers/TicketingPoSController.php", "w") as f:
    f.write(content)

print("Done appending checkInMember to TicketingPoSController.")
