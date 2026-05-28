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
            return redirect()->route('booking-bengkel.index', $kodePartner);
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
        return redirect()->route('booking-bengkel.index', $kodePartner);
    }

    /**
     * Ambil rincian transaksi (history)
     */
    public function getHistoryDetail(Request $request, $kodePartner)
    {
        $noTransaksi = $request->input('NoTransaksi');
        $pelanggan = Auth::guard('pelanggan')->user();

        // Validasi kepemilikan transaksi
        $header = \App\Models\FakturPenjualanHeader::where('NoTransaksi', $noTransaksi)
            ->where('KodePelanggan', $pelanggan->KodePelanggan)
            ->where('RecordOwnerID', $kodePartner)
            ->first();

        if (!$header) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        if (!empty($header->NoPKB)) {
            $details = \Illuminate\Support\Facades\DB::table('bengkel_work_order_details')
                ->where('NoPKB', $header->NoPKB)
                ->where('RecordOwnerID', $kodePartner)
                ->get();
        } else {
            $details = \Illuminate\Support\Facades\DB::table('fakturpenjualandetail')
                ->leftJoin('itemmaster', function ($join) use ($kodePartner) {
                    $join->on('fakturpenjualandetail.KodeItem', '=', 'itemmaster.KodeItem')
                         ->where('itemmaster.RecordOwnerID', '=', $kodePartner);
                })
                ->where('fakturpenjualandetail.NoTransaksi', $noTransaksi)
                ->where('fakturpenjualandetail.RecordOwnerID', $kodePartner)
                ->select('fakturpenjualandetail.*', 'itemmaster.NamaItem')
                ->get();
        }

        return response()->json([
            'success' => true,
            'header' => $header,
            'details' => $details
        ]);
    }

    /**
     * Cetak Faktur untuk Pelanggan
     */
    public function printFaktur($kodePartner, $noTransaksi)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        // Validasi kepemilikan transaksi
        $header = \App\Models\FakturPenjualanHeader::where('NoTransaksi', $noTransaksi)
            ->where('KodePelanggan', $pelanggan->KodePelanggan)
            ->where('RecordOwnerID', $kodePartner)
            ->first();

        if (!$header) {
            abort(404, 'Faktur tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $sql = "DISTINCT fakturpenjualanheader.NoTransaksi, DATE_FORMAT(fakturpenjualanheader.TglTransaksi, '%d-%m-%Y %H:%i') TglTransaksi,
            fakturpenjualanheader.TglJatuhTempo, fakturpenjualanheader.NoReff, fakturpenjualanheader.NoResep, fakturpenjualanheader.NamaDokter, fakturpenjualanheader.NamaPasien, 
            fakturpenjualanheader.KodePelanggan, pelanggan.NamaPelanggan, fakturpenjualanheader.Termin, 
            terminpembayaran.NamaTermin, fakturpenjualanheader.TotalPembelian, fakturpenjualanheader.Pajak,
            fakturpenjualanheader.TotalPembayaran, fakturpenjualanheader.TotalPembelian - COALESCE(fakturpenjualanheader.TotalPembayaran,0) - fakturpenjualanheader.TotalRetur TotalHutang, 
            fakturpenjualanheader.TotalRetur,fakturpenjualandetail.NoUrut, fakturpenjualandetail.KodeItem,
            itemmaster.NamaItem,fakturpenjualandetail.Qty, fakturpenjualandetail.Harga, fakturpenjualandetail.Discount,
            fakturpenjualandetail.HargaNet, fakturpenjualandetail.VatPercent,  COALESCE(pelanggan.Alamat,'') Alamat, 
            coalesce(pelanggan.NoTlp1) NoTlpPelanggan,COALESCE(pelanggan.Email,'') Email,
            'CLOSE' AS StatusDocument, fakturpenjualanheader.Transaksi, company.NamaPartner, company.AlamatTagihan,
            company.NoTlp, company.NoHP, company.icon,fakturpenjualanheader.TotalTransaksi, 
            fakturpenjualanheader.Potongan, company.NPWP, '' CompanyEmail ";

        $model = \App\Models\FakturPenjualanHeader::selectRaw($sql)
            ->leftJoin('terminpembayaran', function ($value){
                $value->on('fakturpenjualanheader.KodeTermin','=','terminpembayaran.id')
                ->on('terminpembayaran.RecordOwnerID','=','fakturpenjualanheader.RecordOwnerID');
            })
            ->leftJoin('pelanggan', function ($value){
                $value->on('fakturpenjualanheader.KodePelanggan','=','pelanggan.KodePelanggan')
                ->on('pelanggan.RecordOwnerID','=','fakturpenjualanheader.RecordOwnerID');
            })
            ->leftJoin('fakturpenjualandetail', function ($value){
                $value->on('fakturpenjualandetail.NoTransaksi','=','fakturpenjualanheader.NoTransaksi')
                ->on('fakturpenjualandetail.RecordOwnerID','=','fakturpenjualanheader.RecordOwnerID');
            })
            ->leftJoin('itemmaster', function ($value){
                $value->on('fakturpenjualandetail.KodeItem','=','itemmaster.KodeItem')
                ->on('fakturpenjualandetail.RecordOwnerID','=','itemmaster.RecordOwnerID');
            })
            ->leftJoin('company', 'company.KodePartner', 'fakturpenjualanheader.RecordOwnerID')
            ->where('fakturpenjualanheader.RecordOwnerID', $kodePartner)
            ->where('fakturpenjualanheader.NoTransaksi', $noTransaksi)
            ->get();

        $oCompany = \App\Models\Company::where('KodePartner', $kodePartner)->first();
        $slip = !empty($oCompany['DefaultSlip']) ? $oCompany['DefaultSlip'] : 'slip1'; // Fallback if empty

        $modelArray = json_decode(json_encode($model), true);

        return view("Transaksi.Penjualan.slip.".$slip, [
            'faktur' => $model,
            'data' => $modelArray,
            'company' => $oCompany
        ]);
    }
}
