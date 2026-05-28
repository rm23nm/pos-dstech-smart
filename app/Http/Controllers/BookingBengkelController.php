<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use Carbon\Carbon;

class BookingBengkelController extends Controller
{
    /**
     * Tampilkan halaman publik untuk melakukan booking
     */
    public function index($kodePartner)
    {
        $company = Company::where('KodePartner', $kodePartner)->first();

        if (!$company) {
            abort(404, 'Bengkel tidak ditemukan');
        }

        // Auto-populate Demo Content
        if (empty($company->BannerBooking) || $company->BannerBooking == '' || empty($company->Banner1)) {
            $company->HeadlineBanner = 'Solusi Terbaik untuk Kendaraan Anda';
            $company->SubHeadlineBanner = 'Layanan bengkel profesional, mekanik berpengalaman, dan peralatan modern. Kami menjamin kendaraan Anda kembali dalam kondisi prima.';
            $company->AboutUs = 'Demo Bengkel Smart adalah bengkel modern yang mengedepankan kualitas dan kepuasan pelanggan. Dengan pengalaman melayani ribuan kendaraan, kami menyediakan layanan servis berkala, perbaikan mesin, ganti oli, hingga perawatan AC dan kelistrikan mobil Anda.';
            $company->TermAndConditionBookingOnline = '<ul><li>Booking wajib dilakukan minimal H-1 sebelum jadwal kedatangan.</li><li>Harap datang tepat waktu sesuai jadwal booking Anda.</li><li>Pembatalan booking harap diinformasikan minimal 2 jam sebelumnya.</li><li>Estimasi waktu pengerjaan dapat berubah menyesuaikan kondisi aktual kendaraan.</li></ul>';
            $company->BannerBooking = url('images/bengkel_hero.png');
            
            // Promo Banners
            $company->Banner1 = url('images/bengkel_promo_1.png');
            $company->BannerHeader1 = 'Promo Spooring & Balancing';
            $company->BannerText1 = 'Dapatkan diskon 20% untuk layanan spooring dan balancing setiap hari Selasa dan Rabu.';
            
            $company->Banner2 = url('images/bengkel_promo_2.png');
            $company->BannerHeader2 = 'Paket Ganti Oli Hemat';
            $company->BannerText2 = 'Gratis filter oli dan pengecekan 15 titik kendaraan untuk setiap pembelian oli mesin sintetik.';
            
            $company->Banner3 = url('images/bengkel_promo_3.png');
            $company->BannerHeader3 = 'Cuci Mobil & Detailing';
            $company->BannerText3 = 'Kembalikan kilau mobil Anda! Layanan auto detailing premium dengan harga terjangkau.';

            $company->save();
        }

        $advisors = \Illuminate\Support\Facades\DB::table('mekanik')->where('RecordOwnerID', $kodePartner)->get();

        return view('Booking.Bengkel.index', compact('company', 'kodePartner', 'advisors'));
    }

    /**
     * Cek data pelanggan berdasarkan Nomor WA atau Plat Nomor
     */
    public function checkCustomer(Request $request, $kodePartner)
    {
        $nomor = $request->input('nomor'); // Bisa Nomor WA atau Plat Nomor
        if (!$nomor) {
            return response()->json(['success' => false]);
        }

        // Cari berdasarkan No WA dulu
        $pelanggan = \App\Models\Pelanggan::where('RecordOwnerID', $kodePartner)
            ->where('NoTlp1', $nomor)
            ->first();

        $platNomor = null;
        if ($pelanggan) {
            // Ambil kendaraan terakhir jika ada
            $kendaraan = \App\Models\Kendaraan::where('RecordOwnerID', $kodePartner)
                ->where('KodePelanggan', $pelanggan->KodePelanggan)
                ->first();
            if ($kendaraan) {
                $platNomor = $kendaraan->PlatNomor;
            }
        } else {
            // Coba cari berdasarkan Plat Nomor
            $kendaraan = \App\Models\Kendaraan::where('RecordOwnerID', $kodePartner)
                ->where('PlatNomor', $nomor)
                ->first();
            
            if ($kendaraan) {
                $pelanggan = \App\Models\Pelanggan::where('RecordOwnerID', $kodePartner)
                    ->where('KodePelanggan', $kendaraan->KodePelanggan)
                    ->first();
                $platNomor = $kendaraan->PlatNomor;
            }
        }

        if ($pelanggan) {
            return response()->json([
                'success' => true,
                'data' => [
                    'NamaPelanggan' => $pelanggan->NamaPelanggan,
                    'NoTlp1' => $pelanggan->NoTlp1,
                    'PlatNomor' => $platNomor
                ]
            ]);
        }

        return response()->json(['success' => false]);
    }

    /**
     * Proses penyimpanan data booking dari publik
     */
    public function store(Request $request, $kodePartner)
    {
        $request->validate([
            'PlatNomor' => 'required|string|max:20',
            'NamaPelanggan' => 'required|string|max:100',
            'NoHP' => 'required|string|max:20',
            'TglBooking' => 'required|date',
            'JamBooking' => 'required',
            'Keluhan' => 'nullable|string'
        ]);

        $company = Company::where('KodePartner', $kodePartner)->first();
        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Bengkel tidak valid']);
        }

        // Cek pelanggan, jika ada ambil KodePelanggan
        $pelanggan = DB::table('pelanggan')
            ->where('RecordOwnerID', $kodePartner)
            ->where('NoTlp1', $request->NoHP)
            ->first();
            
        $kodePelanggan = $pelanggan ? $pelanggan->KodePelanggan : null;

        // Parse time properly since input can be 12-hour AM/PM format
        $jamBookingParsed = null;
        try {
            $jamBookingParsed = Carbon::parse($request->JamBooking)->format('H:i:s');
        } catch (\Exception $e) {
            $jamBookingParsed = $request->JamBooking; // fallback
        }

        DB::table('bengkel_bookings')->insert([
            'RecordOwnerID' => $kodePartner,
            'TglBooking' => $request->TglBooking,
            'JamBooking' => $jamBookingParsed,
            'PlatNomor' => strtoupper($request->PlatNomor),
            'KodePelanggan' => $kodePelanggan,
            'NamaPelanggan' => $request->NamaPelanggan,
            'NoHP' => $request->NoHP,
            'Keluhan' => $request->Keluhan,
            'KodeAdvisor' => $request->KodeAdvisor ?? null,
            'StatusBooking' => 0, // Pending
            'created_at' => Carbon::now('Asia/Jakarta'),
            'updated_at' => Carbon::now('Asia/Jakarta')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dikirim. Silakan tunggu konfirmasi dari pihak bengkel.'
        ]);
    }

    /**
     * Tampilkan halaman dashboard admin untuk manajemen booking
     */
    public function adminIndex(Request $request)
    {
        $user = auth()->user();
        $recordOwnerID = $user->RecordOwnerID;

        $startDate = $request->input('StartDate', date('Y-m-01'));
        $endDate = $request->input('EndDate', date('Y-m-t'));
        $status = $request->input('StatusBooking', 'Semua');

        $query = DB::table('bengkel_bookings')
            ->where('RecordOwnerID', $recordOwnerID)
            ->whereBetween('TglBooking', [$startDate, $endDate]);

        if ($status !== 'Semua') {
            $query->where('StatusBooking', $status);
        }

        $bookings = $query->orderBy('TglBooking', 'ASC')
            ->orderBy('JamBooking', 'ASC')
            ->get();

        $advisors = DB::table('mekanik')->where('RecordOwnerID', $recordOwnerID)->get();

        return view('MasterData.BookingBengkel.admin_index', compact('bookings', 'startDate', 'endDate', 'status', 'advisors'));
    }

    /**
     * Tampilkan Laporan Booking Bengkel
     */
    public function reportBooking(Request $request)
    {
        $user = auth()->user();
        $recordOwnerID = $user->RecordOwnerID;

        $startDate = $request->input('StartDate', date('Y-m-01'));
        $endDate = $request->input('EndDate', date('Y-m-t'));
        $status = $request->input('StatusBooking', 'Semua');

        $query = DB::table('bengkel_bookings')
            ->where('RecordOwnerID', $recordOwnerID)
            ->whereBetween('TglBooking', [$startDate, $endDate]);

        if ($status !== 'Semua') {
            $query->where('StatusBooking', $status);
        }

        $bookings = $query->orderBy('TglBooking', 'ASC')
            ->orderBy('JamBooking', 'ASC')
            ->get();

        return view('MasterData.BookingBengkel.report', compact('bookings', 'startDate', 'endDate', 'status'));
    }

    /**
     * Update status booking (Konfirmasi, Batal, Proses PKB)
     */
    public function updateStatus(Request $request)
    {
        $user = auth()->user();
        $recordOwnerID = $user->RecordOwnerID;
        
        $booking = DB::table('bengkel_bookings')
            ->where('id', $request->id)
            ->where('RecordOwnerID', $recordOwnerID)
            ->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        DB::table('bengkel_bookings')
            ->where('id', $request->id)
            ->update([
                'StatusBooking' => $request->StatusBooking,
                'updated_at' => Carbon::now('Asia/Jakarta')
            ]);

        // Jika diproses ke PKB (Status 2), insert ke bengkel_work_orders otomatis
        if ($request->StatusBooking == 2) {
            $exist = DB::table('bengkel_work_orders')
                ->where('PlatNomor', $booking->PlatNomor)
                ->where('TglPKB', Carbon::today('Asia/Jakarta')->toDateString())
                ->where('RecordOwnerID', $recordOwnerID)
                ->first();
            
            if (!$exist) {
                $noPkb = 'PKB-' . date('YmdHis');
                $keluhanJson = [];
                if (!empty(trim($booking->Keluhan))) {
                    $keluhanJson[] = ['text' => $booking->Keluhan, 'done' => false];
                }

                // Ambil advisor default kendaraan ini
                $knd = DB::table('kendaraan')
                    ->where('PlatNomor', $booking->PlatNomor)
                    ->first();
                $kodeAdvisor = $knd ? $knd->KodeAdvisor : null;

                // Prioritaskan advisor dari form proses (jika admin mengubah), lalu dari booking online, lalu default kendaraan
                $finalAdvisor = $request->KodeAdvisor ?: ($booking->KodeAdvisor ?: $kodeAdvisor);

                DB::table('bengkel_work_orders')->insert([
                    'NoPKB' => $noPkb,
                    'TglPKB' => Carbon::today('Asia/Jakarta')->toDateString(),
                    'PlatNomor' => $booking->PlatNomor,
                    'KodePelanggan' => $booking->KodePelanggan ?? '',
                    'NamaPelanggan' => $booking->NamaPelanggan ?? '',
                    'KodeMekanik' => $finalAdvisor, 
                    'EstimasiWaktu' => 0,
                    'Keluhan' => json_encode($keluhanJson),
                    'StatusServis' => 0,
                    'RecordOwnerID' => $recordOwnerID,
                    'created_at' => Carbon::now('Asia/Jakarta'),
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Status berhasil diubah']);
    }
}
