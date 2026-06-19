<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class OfflineClientController extends Controller
{
    public function showActivationForm()
    {
        return view('auth.activation');
    }

    private function getHardwareId()
    {
        $path = storage_path('app/hardware_id.txt');
        if (File::exists($path)) {
            return File::get($path);
        }

        $uuid = null;
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            @exec('wmic csproduct get uuid 2>nul', $output);
            if (!empty($output) && isset($output[1])) {
                $uuid = trim($output[1]);
            }
        }

        if (!$uuid || $uuid == '') {
            $uuid = (string) \Illuminate\Support\Str::uuid();
        }

        File::put($path, $uuid);
        return $uuid;
    }

    public function activate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
        ]);

        $licenseKey = $request->license_key;
        $hardwareId = $this->getHardwareId();
        $deviceName = gethostname() ?: 'Unknown PC';

        // Validasi ke Server Pusat (Otomatis ke localhost jika sedang dites lokal)
        $masterUrl = (str_contains(request()->getHost(), 'localhost') || str_contains(request()->getHost(), '127.0.0.1')) 
            ? url('') 
            : 'https://pos.dstechsmart.com';
        
        try {
            if ($masterUrl === url('')) {
                // Panggil method controller secara internal untuk menghindari deadlock
                $request->merge([
                    'hardware_id' => $hardwareId,
                    'device_name' => $deviceName
                ]);
                $internalResponse = app(\App\Http\Controllers\LicenseController::class)->checkLicense($request);
                $resData = json_decode($internalResponse->getContent(), true);
                
                if ($internalResponse->getStatusCode() === 200 && ($resData['status'] ?? '') === 'valid') {
                    $this->saveLicenseLocally($licenseKey, $hardwareId);
                    return redirect('/')->with('success', 'Aktivasi berhasil! (Local Mode)');
                } else {
                    $msg = $resData['message'] ?? 'License Key tidak valid.';
                    return back()->with('error', $msg);
                }
            } else {
                $response = Http::post($masterUrl . '/api/check-license', [
                    'license_key' => $licenseKey,
                    'hardware_id' => $hardwareId,
                    'device_name' => $deviceName
                ]);

                if ($response->successful() && $response->json('status') === 'valid') {
                    // Simpan License Key ke file konfigurasi local (misalnya .env atau json lokal)
                    $this->saveLicenseLocally($licenseKey, $hardwareId);
                    return redirect('/')->with('success', 'Aktivasi berhasil! Selamat menggunakan aplikasi.');
                } else {
                    $msg = $response->json('message') ?? 'License Key tidak valid.';
                    return back()->with('error', $msg);
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke Server Pusat (' . $masterUrl . '). Pastikan Anda memiliki koneksi internet saat aktivasi pertama kali. Error: ' . $e->getMessage());
        }
    }

    private function saveLicenseLocally($key, $hardwareId)
    {
        $path = storage_path('app/offline_license.json');
        File::put($path, json_encode([
            'license_key' => $key,
            'hardware_id' => $hardwareId,
            'last_checked' => now()->toDateTimeString(),
            'status' => 'valid'
        ]));
    }
}
