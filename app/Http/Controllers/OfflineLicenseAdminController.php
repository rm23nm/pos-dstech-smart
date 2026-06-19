<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfflineLicense;
use Illuminate\Support\Str;

class OfflineLicenseAdminController extends Controller
{
    public function index()
    {
        $licenses = OfflineLicense::orderBy('created_at', 'desc')->get();
        return view('Admin.OfflineLicense.index', compact('licenses'));
    }

    public function generate()
    {
        // Fungsi ini mungkin tidak diperlukan lagi jika di-generate di server saat submit, tapi biarkan saja untuk fallback
        $key = 'DSMS-POS-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        return response()->json(['key' => $key]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required',
            'valid_until' => 'required|date',
            'jumlah_lisensi' => 'required|integer|min:1|max:100',
            'max_devices' => 'required|integer|min:1|max:999'
        ]);

        $jumlah = $request->jumlah_lisensi;
        $insertedCount = 0;

        for ($i = 0; $i < $jumlah; $i++) {
            $key = 'DSMS-POS-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
            
            // Pastikan unik
            while (OfflineLicense::where('license_key', $key)->exists()) {
                $key = 'DSMS-POS-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
            }

            OfflineLicense::create([
                'license_key' => $key,
                'client_name' => $request->client_name . ($jumlah > 1 ? ' - ' . ($i + 1) : ''),
                'valid_until' => $request->valid_until,
                'max_devices' => $request->max_devices,
                'status' => 'active'
            ]);
            $insertedCount++;
        }

        return redirect()->route('admin.offline-licenses')->with('success', $insertedCount . ' Lisensi berhasil dibuat.');
    }

    public function destroy($id)
    {
        OfflineLicense::findOrFail($id)->delete();
        return back()->with('success', 'Lisensi dihapus.');
    }

    public function toggleStatus($id)
    {
        $license = OfflineLicense::findOrFail($id);
        $license->status = $license->status === 'active' ? 'banned' : 'active';
        $license->save();
        return back()->with('success', 'Status lisensi diubah.');
    }
}
