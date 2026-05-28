<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pelanggan;
use App\Models\Company;
use App\Models\FakturPenjualanHeader;

class BookingBengkelAuthController extends Controller
{
    /**
     * Tampilkan halaman dasbor pelanggan (setelah login)
     */
    public function dashboard($kodePartner)
    {
        $company = Company::where('KodePartner', $kodePartner)->first();
        if (!$company) {
            abort(404, 'Bengkel tidak ditemukan');
        }

        $pelanggan = Auth::guard('pelanggan')->user();
        if (!$pelanggan) {
            return redirect()->route('booking-bengkel', $kodePartner);
        }

        // Ambil histori servis dari FakturPenjualanHeader untuk pelanggan ini
        $history = FakturPenjualanHeader::where('KodePelanggan', $pelanggan->KodePelanggan)
            ->where('RecordOwnerID', $kodePartner)
            ->orderBy('TglTransaksi', 'desc')
            ->get();

        // Ambil histori/jadwal booking
        $bookings = \Illuminate\Support\Facades\DB::table('bengkel_bookings')
            ->where('KodePelanggan', $pelanggan->KodePelanggan)
            ->where('RecordOwnerID', $kodePartner)
            ->orderBy('TglBooking', 'desc')
            ->get();

        return view('Booking.Bengkel.dashboard', compact('company', 'kodePartner', 'pelanggan', 'history', 'bookings'));
    }

    /**
     * Proses Login
     */
    public function login(Request $request, $kodePartner)
    {
        $request->validate([
            'login_identifier' => 'required', // Bisa NoTlp1 atau Email
            'password' => 'required'
        ]);

        $pelanggan = Pelanggan::where(function($query) use ($request) {
                $query->where('NoTlp1', $request->login_identifier)
                      ->orWhere('Email', $request->login_identifier);
            })
            ->where('RecordOwnerID', $kodePartner)
            ->first();

        if ($pelanggan && Hash::check($request->password, $pelanggan->password)) {
            Auth::guard('pelanggan')->login($pelanggan);
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Nomor HP/Email atau Password salah.'
        ]);
    }

    /**
     * Proses Register
     */
    public function register(Request $request, $kodePartner)
    {
        $request->validate([
            'NamaPelanggan' => 'required',
            'NoTlp1' => 'required',
            'password' => 'required|min:6'
        ]);

        // Cek apakah No HP sudah terdaftar di bengkel ini
        $exists = Pelanggan::where('NoTlp1', $request->NoTlp1)->where('RecordOwnerID', $kodePartner)->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp sudah terdaftar.'
            ]);
        }

        // Generate Kode Pelanggan baru (contoh: CUST-YYYYMMDD-HHMMSS)
        $kodePelanggan = 'CUST-' . date('Ymd-His') . rand(10,99);

        $pelanggan = Pelanggan::create([
            'KodePelanggan' => $kodePelanggan,
            'KodeGrupPelanggan' => '-',
            'NamaPelanggan' => $request->NamaPelanggan,
            'NoTlp1' => $request->NoTlp1,
            'NoTlp2' => '-',
            'Email' => $request->Email ?? '-',
            'Alamat' => '-',
            'Keterangan' => '-',
            'LimitPiutang' => 0,
            'ProvID' => '-',
            'KotaID' => '-',
            'KelID' => '-',
            'KecID' => '-',
            'password' => Hash::make($request->password),
            'RecordOwnerID' => $kodePartner,
            'Status' => 1
        ]);

        // Langsung login setelah register
        Auth::guard('pelanggan')->login($pelanggan);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil!'
        ]);
    }

    /**
     * Proses Logout
     */
    public function logout(Request $request, $kodePartner)
    {
        Auth::guard('pelanggan')->logout();
        return redirect()->route('booking-bengkel', $kodePartner);
    }
}
