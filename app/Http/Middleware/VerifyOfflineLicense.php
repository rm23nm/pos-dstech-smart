<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class VerifyOfflineLicense
{
    public function handle(Request $request, Closure $next)
    {
        // Bypass pengecekan lisensi jika ini adalah Server Pusat (Live Server)
        // Server pusat tidak membutuhkan lisensi offline
        if (request()->getHost() === 'pos.dstechsmart.com' || env('IS_MASTER_SERVER', false)) {
            return $next($request);
        }
        // Pengecualian route agar tidak terjadi infinite loop atau memblokir halaman login
        if ($request->is('offline/activation') || $request->is('offline/activate') || $request->is('api/check-license') || $request->is('login') || $request->is('logout')) {
            return $next($request);
        }

        $path = storage_path('app/offline_license.json');

        // Jika file lisensi tidak ada (belum diaktifkan)
        if (!File::exists($path)) {
            return redirect()->route('offline.activation')->with('error', 'Silakan masukkan Serial Number Anda.');
        }

        $licenseData = json_decode(File::get($path), true);

        if (!$licenseData || !isset($licenseData['license_key'])) {
            return redirect()->route('offline.activation')->with('error', 'File lisensi rusak. Silakan aktivasi ulang.');
        }

        $lastChecked = Carbon::parse($licenseData['last_checked'] ?? '2000-01-01');

        // Pengecekan latar belakang setiap 1 hari (24 jam)
        if (now()->diffInDays($lastChecked) >= 1) {
            try {
                $masterUrl = (str_contains(request()->getHost(), 'localhost') || str_contains(request()->getHost(), '127.0.0.1')) 
                    ? url('') 
                    : 'https://pos.dstechsmart.com';
                
                if ($masterUrl === url('')) {
                    // Cek DB lokal langsung untuk menghindari deadlock di php artisan serve
                    $license = \App\Models\OfflineLicense::where('license_key', $licenseData['license_key'])->first();
                    if (!$license || $license->status !== 'active' || ($license->valid_until && \Carbon\Carbon::now()->startOfDay()->gt(\Carbon\Carbon::parse($license->valid_until)->endOfDay()))) {
                        \Illuminate\Support\Facades\File::delete($path);
                        return redirect()->route('offline.activation')->with('error', 'Lisensi tidak valid, diblokir, atau telah kedaluwarsa.');
                    } else {
                        // Cek device locally too!
                        $device = \App\Models\OfflineLicenseDevice::where('offline_license_id', $license->id)
                            ->where('hardware_id', $licenseData['hardware_id'] ?? 'unknown')
                            ->first();
                            
                        if (!$device) {
                            \Illuminate\Support\Facades\File::delete($path);
                            return redirect()->route('offline.activation')->with('error', 'Perangkat Anda tidak terdaftar pada lisensi ini.');
                        }

                        $licenseData['last_checked'] = now()->toDateTimeString();
                        $licenseData['valid_until'] = $license->valid_until;
                        \Illuminate\Support\Facades\File::put($path, json_encode($licenseData));
                    }
                } else {
                    $response = Http::timeout(3)->post($masterUrl . '/api/check-license', [
                        'license_key' => $licenseData['license_key'],
                        'hardware_id' => $licenseData['hardware_id'] ?? 'unknown',
                        'device_name' => gethostname() ?: 'Unknown PC'
                    ]);

                    if ($response->successful()) {
                        $resData = $response->json();
                        if ($resData['status'] !== 'valid') {
                            // Lisensi Expired / Banned / Limit Reached dari server
                            \Illuminate\Support\Facades\File::delete($path);
                            return redirect()->route('offline.activation')->with('error', $resData['message']);
                        } else {
                            // Perpanjang cache
                            $licenseData['last_checked'] = now()->toDateTimeString();
                            $licenseData['valid_until'] = $resData['valid_until']; // Update masa berlaku
                            \Illuminate\Support\Facades\File::put($path, json_encode($licenseData));
                        }
                    }
                }
            } catch (\Exception $e) {
                // Jika koneksi internet mati, berikan toleransi 7 hari
                if (now()->diffInDays($lastChecked) > 7) {
                    return redirect()->route('offline.activation')->with('error', 'Koneksi ke server gagal. Toleransi offline (7 hari) telah habis. Silakan hubungkan ke internet untuk memvalidasi lisensi.');
                }
            }
        }

        return $next($request);
    }
}
