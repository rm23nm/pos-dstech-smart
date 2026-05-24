<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GateApiController extends Controller
{
    /**
     * Endpoint untuk dipanggil oleh ESP32 saat men-scan tiket barcode atau kartu RFID
     */
    public function scan(Request $request)
    {
        // Parameter:
        // device_id: ID alat ESP32
        // code: Barcode Tiket atau RFID UID
        // type: 'barcode' atau 'rfid' (opsional, jika kosong sistem akan menerka berdasarkan format)
        
        $deviceId = $request->input('device_id');
        $code = $request->input('code');
        $type = $request->input('type');

        if (!$deviceId || !$code) {
            return response()->json([
                'success' => false,
                'open' => false,
                'message' => 'Device ID dan Code harus diisi'
            ], 400);
        }

        // Validasi Device
        $device = DB::table('gate_devices')->where('DeviceID', $deviceId)->first();
        if (!$device) {
            return response()->json([
                'success' => false,
                'open' => false,
                'message' => 'Alat Gate tidak terdaftar'
            ], 403);
        }

        if ($type === 'rfid' || strlen($code) < 10) { 
            // Anggap string pendek (seperti format hex) atau secara eksplisit dikirim type 'rfid' sebagai RFID Member
            return $this->handleRfidScan($code, $device);
        } else {
            // Anggap sebagai barcode tiket (string panjang hasil cetakan)
            return $this->handleBarcodeScan($code, $device);
        }
    }

    private function handleBarcodeScan($barcode, $device)
    {
        $tiket = DB::table('tiket_masuk')
                    ->where('BarcodeTiket', $barcode)
                    ->where('RecordOwnerID', $device->RecordOwnerID)
                    ->first();

        if (!$tiket) {
            return response()->json([
                'success' => false,
                'open' => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        if ($tiket->Status == 1) {
            return response()->json([
                'success' => false,
                'open' => false,
                'message' => 'Tiket sudah pernah digunakan pada ' . $tiket->WaktuPakai
            ], 403);
        }

        // Validasi Akses Area
        $hasAccess = DB::table('gate_device_tickets')
                        ->where('DeviceID', $device->DeviceID)
                        ->where('KodeItem', $tiket->KodeItem)
                        ->exists();

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'open' => false,
                'message' => 'Akses Ditolak: Tiket ini tidak berlaku untuk area ini'
            ], 403);
        }

        // Tiket valid, tandai sudah dipakai
        DB::table('tiket_masuk')
            ->where('id', $tiket->id)
            ->update([
                'Status' => 1,
                'WaktuPakai' => Carbon::now()
            ]);

        Log::info("Gate Opened via Tiket. Barcode: $barcode, Device: " . $device->DeviceID);

        return response()->json([
            'success' => true,
            'open' => true,
            'message' => 'Akses Tiket Diberikan'
        ], 200);
    }

    private function handleRfidScan($uid, $device)
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
    }
}
