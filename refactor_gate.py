import re

with open("app/Http/Controllers/GateApiController.php", "r") as f:
    content = f.read()

old_block = """    private function handleRfidScan($uid, $device)
    {
        // Cari pelanggan dengan RFID UID tersebut
        $member = DB::table('pelanggan')
                    ->where('RFID_UID', $uid)
                    ->where('RecordOwnerID', $device->RecordOwnerID)
                    ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'open' => false,
                'message' => 'Kartu Member tidak terdaftar'
            ], 404);
        }

        // Validasi Akses Area untuk Member
        $hasAccess = DB::table('gate_device_tickets')
                        ->where('DeviceID', $device->DeviceID)
                        ->where('KodeItem', $member->KodePaketMember)
                        ->exists();

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'open' => false,
                'message' => 'Akses Ditolak: Paket member Anda tidak berlaku untuk area ini'
            ], 403);
        }

        // TODO: Jika ada kolom masa aktif member di tabel pelanggan, bisa ditambahkan filter disini
        // Saat ini, selama terdaftar dan status Aktif, pintu terbuka.
        
        Log::info("Gate Opened via Member RFID. UID: $uid, Member: " . $member->NamaPelanggan . ", Device: " . $device->DeviceID);

        return response()->json([
            'success' => true,
            'open' => true,
            'message' => 'Akses Member Diberikan',
            'member_name' => $member->NamaPelanggan
        ], 200);
    }"""

new_block = """    private function handleRfidScan($uid, $device)
    {
        // Cari pelanggan dengan RFID UID tersebut
        $member = DB::table('pelanggan')
                    ->where('RFID_UID', $uid)
                    ->where('RecordOwnerID', $device->RecordOwnerID)
                    ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'open' => false,
                'message' => 'Kartu Member tidak terdaftar'
            ], 404);
        }

        // Cari semua paket aktif pelanggan di customer_memberships
        $activeMemberships = DB::table('customer_memberships')
            ->where('KodePelanggan', $member->KodePelanggan)
            ->where('RecordOwnerID', $device->RecordOwnerID)
            ->where('ValidUntil', '>=', Carbon::now('Asia/Jakarta'))
            ->get();

        if ($activeMemberships->isEmpty()) {
            return response()->json([
                'success' => false,
                'open' => false,
                'message' => 'Akses Ditolak: Tidak ada paket member yang aktif atau paket sudah kedaluwarsa.'
            ], 403);
        }

        $validMembership = null;

        foreach ($activeMemberships as $am) {
            // Validasi Akses Area untuk Member
            $hasAccess = DB::table('gate_device_tickets')
                            ->where('DeviceID', $device->DeviceID)
                            ->where('KodeItem', $am->KodePaketMember)
                            ->exists();

            if ($hasAccess) {
                // Cek kuota
                if ($am->MaxPlay == 0 || $am->Played < $am->MaxPlay) {
                    $validMembership = $am;
                    break;
                }
            }
        }

        if (!$validMembership) {
            return response()->json([
                'success' => false,
                'open' => false,
                'message' => 'Akses Ditolak: Paket tidak berlaku untuk area ini atau kuota sudah habis.'
            ], 403);
        }

        // Potong kuota
        DB::table('customer_memberships')
            ->where('id', $validMembership->id)
            ->update([
                'Played' => DB::raw('Played + 1')
            ]);
        
        Log::info("Gate Opened via Member RFID. UID: $uid, Member: " . $member->NamaPelanggan . ", Device: " . $device->DeviceID . ", Package Used: " . $validMembership->KodePaketMember);

        return response()->json([
            'success' => true,
            'open' => true,
            'message' => 'Akses Member Diberikan',
            'member_name' => $member->NamaPelanggan
        ], 200);
    }"""

content = content.replace(old_block, new_block)

with open("app/Http/Controllers/GateApiController.php", "w") as f:
    f.write(content)

print("Done refactoring GateApiController handleRfidScan()")
