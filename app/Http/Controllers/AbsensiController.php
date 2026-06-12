<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Log;

use App\Models\Absensi;
use App\Models\Shift;
use App\Models\SettingAbsensi;

class AbsensiController extends Controller
{
    // === TAHAP 3: DASHBOARD ANALYTICS ===
    public function dashboardAbsensi(Request $request)
    {
        $roid = Auth::user()->RecordOwnerID;
        $today = Carbon::today()->format('Y-m-d');
        $thisMonth = Carbon::now()->format('m');
        $thisYear = Carbon::now()->format('Y');

        // Statistik Hari Ini
        $totalHadir = Absensi::where('RecordOwnerID', $roid)
            ->whereDate('Tanggal', $today)
            ->count();
            
        $totalTelatHariIni = Absensi::where('RecordOwnerID', $roid)
            ->whereDate('Tanggal', $today)
            ->where('KeteranganMasuk', 'Telat')
            ->count();

        // Statistik Bulan Ini
        $totalIzinCuti = DB::table('pengajuan_absen')
            ->where('RecordOwnerID', $roid)
            ->where('StatusApproval', 'Disetujui')
            ->whereMonth('TanggalMulai', '<=', $thisMonth)
            ->whereMonth('TanggalSelesai', '>=', $thisMonth)
            ->whereYear('TanggalMulai', $thisYear)
            ->count();

        $totalDendaBulanIni = Absensi::where('RecordOwnerID', $roid)
            ->whereMonth('Tanggal', $thisMonth)
            ->whereYear('Tanggal', $thisYear)
            ->sum('DendaTelat');

        $totalLemburBulanIni = Absensi::where('RecordOwnerID', $roid)
            ->whereMonth('Tanggal', $thisMonth)
            ->whereYear('Tanggal', $thisYear)
            ->sum('BonusLembur');

        return view('hrd.DashboardAbsensi', compact(
            'totalHadir', 'totalTelatHariIni', 'totalIzinCuti', 'totalDendaBulanIni', 'totalLemburBulanIni'
        ));
    }

    // 1. Dashboard Pribadi Karyawan
    public function absensiSaya(Request $request)
    {
        $user_id = Auth::user()->id;
        $roid = Auth::user()->RecordOwnerID;

        // Ambil riwayat absen user ini saja
        $riwayat = Absensi::where('RecordOwnerID', $roid)
            ->where('user_id', $user_id)
            ->orderBy('Tanggal', 'desc')
            ->get();

        // Ambil absen hari ini untuk user ini
        $hariIni = Carbon::now()->toDateString();
        $absenHariIni = Absensi::where('RecordOwnerID', $roid)
            ->where('user_id', $user_id)
            ->where('Tanggal', $hariIni)
            ->first();

        // Ambil daftar shift
        $shifts = Shift::where('RecordOwnerID', $roid)->get();

        return view('hrd.AbsensiSaya', compact('riwayat', 'absenHariIni', 'shifts'));
    }

    // 2. Laporan Absensi untuk HRD
    public function laporanAbsensi(Request $request)
    {
        $roid = Auth::user()->RecordOwnerID;
        
        $TglAwal = $request->input('TglAwal', Carbon::now()->startOfMonth()->toDateString());
        $TglAkhir = $request->input('TglAkhir', Carbon::now()->toDateString());

        $laporan = Absensi::select('absensi.*', 'users.name as NamaKaryawan', 'shift.NamaShift')
            ->leftJoin('users', 'absensi.user_id', '=', 'users.id')
            ->leftJoin('shift', function ($join) {
                $join->on('absensi.KodeShift', '=', 'shift.KodeShift')
                     ->on('absensi.RecordOwnerID', '=', 'shift.RecordOwnerID');
            })
            ->where('absensi.RecordOwnerID', $roid)
            ->whereBetween('absensi.Tanggal', [$TglAwal, $TglAkhir])
            ->orderBy('absensi.Tanggal', 'desc')
            ->orderBy('absensi.JamMasuk', 'desc')
            ->get();

        return view('hrd.LaporanAbsensi', compact('laporan', 'TglAwal', 'TglAkhir'));
    }

    // 3. Proses Absen Masuk
    public function absenMasuk(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            $roid = Auth::user()->RecordOwnerID;
            $hariIni = Carbon::now()->toDateString();
            $sekarang = Carbon::now();

            $cekAbsen = Absensi::where('RecordOwnerID', $roid)
                ->where('user_id', $user_id)
                ->where('Tanggal', $hariIni)
                ->first();

            if ($cekAbsen) {
                return response()->json(['success' => false, 'message' => 'Anda sudah absen masuk hari ini.']);
            }

            $KodeShift = $request->input('KodeShift');
            $shift = Shift::where('RecordOwnerID', $roid)->where('KodeShift', $KodeShift)->first();

            $latMasuk = $request->input('LatitudeMasuk');
            $lngMasuk = $request->input('LongitudeMasuk');

            // Cek Geofencing
            $setting = SettingAbsensi::where('RecordOwnerID', $roid)->first();
            if ($setting && $setting->TitikKordinatKantor && $setting->RadiusBebasAbsen > 0) {
                if (!$latMasuk || !$lngMasuk) {
                    return response()->json(['success' => false, 'message' => 'Gagal mendapatkan lokasi GPS Anda. Pastikan fitur lokasi aktif.']);
                }
                
                $coords = explode(',', $setting->TitikKordinatKantor);
                if (count($coords) == 2) {
                    $latKantor = trim($coords[0]);
                    $lngKantor = trim($coords[1]);
                    
                    // Haversine formula
                    $earthRadius = 6371000; // in meters
                    $dLat = deg2rad($latMasuk - $latKantor);
                    $dLng = deg2rad($lngMasuk - $lngKantor);
                    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latKantor)) * cos(deg2rad($latMasuk)) * sin($dLng/2) * sin($dLng/2);
                    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
                    $distance = $earthRadius * $c;

                    if ($distance > $setting->RadiusBebasAbsen) {
                        return response()->json(['success' => false, 'message' => 'Anda berada di luar jangkauan radius absen kantor (' . round($distance) . ' meter dari kantor).']);
                    }
                }
            }

            $statusKehadiran = 'Tepat Waktu';
            if ($shift) {
                // Konversi JamMulai shift ke objek waktu hari ini
                $jamMulaiShift = Carbon::createFromFormat('Y-m-d H:i:s', $hariIni . ' ' . $shift->JamMulai);
                // Beri toleransi misal 15 menit, ini bisa disesuaikan
                if ($sekarang->greaterThan($jamMulaiShift->addMinutes(15))) {
                    $statusKehadiran = 'Terlambat';
                }
            }

            $absen = new Absensi;
            $absen->RecordOwnerID = $roid;
            $absen->Tanggal = $hariIni;
            $absen->user_id = $user_id;
            $absen->KodeShift = $KodeShift;
            $absen->JamMasuk = $sekarang;
            $absen->FotoMasuk = $request->input('FotoMasuk'); // base64
            $absen->LatitudeMasuk = $request->input('LatitudeMasuk');
            $absen->LongitudeMasuk = $request->input('LongitudeMasuk');
            $absen->StatusKehadiran = $statusKehadiran;
            $absen->save();

            return response()->json(['success' => true, 'message' => 'Absen Masuk berhasil dicatat.']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan absensi: ' . $e->getMessage()]);
        }
    }

    // 4. Proses Absen Pulang
    public function absenPulang(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            $roid = Auth::user()->RecordOwnerID;
            $hariIni = Carbon::now()->toDateString();
            $sekarang = Carbon::now();

            $absen = Absensi::where('RecordOwnerID', $roid)
                ->where('user_id', $user_id)
                ->where('Tanggal', $hariIni)
                ->first();

            if (!$absen) {
                return response()->json(['success' => false, 'message' => 'Anda belum absen masuk hari ini!']);
            }

            if ($absen->JamPulang) {
                return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absen pulang hari ini.']);
            }

            $latPulang = $request->input('LatitudePulang');
            $lngPulang = $request->input('LongitudePulang');

            // Cek Geofencing
            $setting = SettingAbsensi::where('RecordOwnerID', $roid)->first();
            if ($setting && $setting->TitikKordinatKantor && $setting->RadiusBebasAbsen > 0) {
                if (!$latPulang || !$lngPulang) {
                    return response()->json(['success' => false, 'message' => 'Gagal mendapatkan lokasi GPS Anda. Pastikan fitur lokasi aktif.']);
                }
                
                $coords = explode(',', $setting->TitikKordinatKantor);
                if (count($coords) == 2) {
                    $latKantor = trim($coords[0]);
                    $lngKantor = trim($coords[1]);
                    
                    // Haversine formula
                    $earthRadius = 6371000; // in meters
                    $dLat = deg2rad($latPulang - $latKantor);
                    $dLng = deg2rad($lngPulang - $lngKantor);
                    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latKantor)) * cos(deg2rad($latPulang)) * sin($dLng/2) * sin($dLng/2);
                    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
                    $distance = $earthRadius * $c;

                    if ($distance > $setting->RadiusBebasAbsen) {
                        return response()->json(['success' => false, 'message' => 'Anda berada di luar jangkauan radius absen kantor (' . round($distance) . ' meter dari kantor).']);
                    }
                }
            }

            $absen->JamPulang = $sekarang;
            $absen->FotoPulang = $request->input('FotoPulang'); // base64
            $absen->LatitudePulang = $request->input('LatitudePulang');
            $absen->LongitudePulang = $request->input('LongitudePulang');
            $absen->save();

            return response()->json(['success' => true, 'message' => 'Absen Pulang berhasil dicatat.']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan absensi: ' . $e->getMessage()]);
        }
    }
}
