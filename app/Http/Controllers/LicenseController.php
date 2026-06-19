<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfflineLicense;
use Carbon\Carbon;

class LicenseController extends Controller
{
    // API untuk diakses oleh aplikasi lokal offline
    public function checkLicense(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
            'hardware_id' => 'required|string',
            'device_name' => 'nullable|string'
        ]);

        $license = OfflineLicense::where('license_key', $request->license_key)->first();

        if (!$license) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'License key tidak ditemukan.'
            ], 404);
        }

        if ($license->status !== 'active') {
            return response()->json([
                'status' => 'banned',
                'message' => 'License key ini telah diblokir/dinonaktifkan oleh administrator.'
            ], 403);
        }

        if ($license->valid_until && Carbon::now()->startOfDay()->gt(Carbon::parse($license->valid_until)->endOfDay())) {
            return response()->json([
                'status' => 'expired',
                'message' => 'Masa berlaku lisensi ini telah habis pada ' . date('d M Y', strtotime($license->valid_until))
            ], 403);
        }

        // Cek penguncian perangkat (Hardware ID)
        $device = \App\Models\OfflineLicenseDevice::where('offline_license_id', $license->id)
                    ->where('hardware_id', $request->hardware_id)
                    ->first();

        if (!$device) {
            // Perangkat belum terdaftar. Cek apakah masih ada slot.
            $registeredCount = \App\Models\OfflineLicenseDevice::where('offline_license_id', $license->id)->count();
            
            if ($registeredCount >= $license->max_devices) {
                return response()->json([
                    'status' => 'device_limit_reached',
                    'message' => 'Batas maksimal perangkat ('.$license->max_devices.' perangkat) untuk lisensi ini telah tercapai. Tidak bisa digunakan di komputer ini.'
                ], 403);
            }

            // Daftarkan perangkat baru
            \App\Models\OfflineLicenseDevice::create([
                'offline_license_id' => $license->id,
                'hardware_id' => $request->hardware_id,
                'device_name' => $request->device_name ?? 'Unknown PC'
            ]);
        }

        return response()->json([
            'status' => 'valid',
            'client_name' => $license->client_name,
            'valid_until' => $license->valid_until,
            'message' => 'License valid.'
        ]);
    }
}
