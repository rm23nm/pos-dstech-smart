<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Log;

use App\Models\User;
use App\Models\KaryawanPayroll;
use App\Models\Absensi;
use App\Models\Rekening;
use App\Models\DocumentNumbering;
use App\Models\KasKeluarHeader;
use App\Models\KasKeluarDetail;
use App\Models\HariLibur;
use App\Models\PengajuanAbsen;

class PenggajianController extends Controller
{
    // ==========================================
    // 1. MASTER GAJI (Setup Gaji Pokok per User)
    // ==========================================
    public function masterGaji(Request $request)
    {
        $roid = Auth::user()->RecordOwnerID;

        // Ambil semua user dengan RoleID = 2 (atau Karyawan biasa, tapi untuk aman ambil semua non-superadmin atau sesuaikan)
        // Disini kita ambil semua user saja dan leftJoin dengan karyawan_payroll
        $users = User::select('users.id', 'users.name', 'users.email', 'karyawan_payroll.GajiPokok', 'karyawan_payroll.KodeAkunGaji', 'karyawan_payroll.TarifLemburPerJam', 'karyawan_payroll.TarifDendaPerMenit')
            ->leftJoin('karyawan_payroll', 'users.id', '=', 'karyawan_payroll.user_id')
            ->where('users.RecordOwnerID', $roid)
            ->get();

        // Ambil Rekening Beban (Jenis 2, Kelompok Laba Rugi / Beban) untuk default akun
        $rekeningBeban = Rekening::where('RecordOwnerID', $roid)
            ->where('Jenis', 2)
            ->get(); // Anda bisa filter lebih spesifik ke "Beban Gaji" jika tau kodenya

        return view('hrd.MasterGaji', compact('users', 'rekeningBeban'));
    }

    public function storeMasterGaji(Request $request)
    {
        try {
            $roid = Auth::user()->RecordOwnerID;
            $dataGaji = $request->input('gaji'); // array of user_id => [GajiPokok, KodeAkunGaji]

            if($dataGaji) {
                foreach($dataGaji as $user_id => $val) {
                    $payroll = KaryawanPayroll::where('user_id', $user_id)->where('RecordOwnerID', $roid)->first();
                    if(!$payroll) {
                        $payroll = new KaryawanPayroll;
                        $payroll->user_id = $user_id;
                        $payroll->RecordOwnerID = $roid;
                    }
                    $payroll->GajiPokok = str_replace(',', '', $val['GajiPokok'] ?? 0);
                    $payroll->TarifLemburPerJam = str_replace(',', '', $val['TarifLemburPerJam'] ?? 0);
                    $payroll->TarifDendaPerMenit = str_replace(',', '', $val['TarifDendaPerMenit'] ?? 0);
                    $payroll->KodeAkunGaji = $val['KodeAkunGaji'] ?? '';
                    $payroll->save();
                }
            }

            return response()->json(['success' => true, 'message' => 'Data Master Gaji berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function getKomponenGaji($id)
    {
        $komponen = \App\Models\KaryawanKomponenGaji::where('user_id', $id)->get();
        return response()->json($komponen);
    }

    public function storeKomponenGaji(Request $request)
    {
        $roid = Auth::user()->RecordOwnerID;
        $user_id = $request->user_id;

        // Hapus komponen lama
        \App\Models\KaryawanKomponenGaji::where('user_id', $user_id)->delete();

        // Simpan komponen baru jika ada
        if($request->has('komponen')) {
            foreach($request->komponen as $k) {
                \App\Models\KaryawanKomponenGaji::create([
                    'RecordOwnerID' => $roid,
                    'user_id' => $user_id,
                    'Jenis' => $k['Jenis'],
                    'NamaKomponen' => $k['NamaKomponen'],
                    'Sifat' => $k['Sifat'],
                    'Nominal' => str_replace(',', '', $k['Nominal'])
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Komponen Gaji Karyawan berhasil diperbarui.']);
    }

    // ==========================================
    // 2. PROSES PENGGAJIAN (Hitung & Posting Kas)
    // ==========================================
    public function prosesPenggajian(Request $request)
    {
        $roid = Auth::user()->RecordOwnerID;
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->format('Y'));

        // Hitung Hari Kerja Efektif (Total Hari - Minggu - Hari Libur)
        $totalDaysInMonth = Carbon::create($tahun, $bulan)->daysInMonth;
        $totalSundays = 0;
        for ($i = 1; $i <= $totalDaysInMonth; $i++) {
            if (Carbon::create($tahun, $bulan, $i)->isSunday()) {
                $totalSundays++;
            }
        }
        $hariLiburCount = HariLibur::where('RecordOwnerID', $roid)
            ->whereMonth('Tanggal', $bulan)
            ->whereYear('Tanggal', $tahun)
            ->count();
        
        $totalHariKerja = $totalDaysInMonth - $totalSundays - $hariLiburCount;

        // Tarik data absen bulan terpilih
        $absensiRaw = Absensi::where('RecordOwnerID', $roid)
            ->whereMonth('Tanggal', $bulan)
            ->whereYear('Tanggal', $tahun)
            ->get();

        // Hitung rekapan per user
        $rekap = [];
        foreach($absensiRaw as $absen) {
            $uid = $absen->user_id;
            if(!isset($rekap[$uid])) {
                $rekap[$uid] = [
                    'TotalHadir' => 0,
                    'TotalTelatMenit' => 0,
                    'TotalLemburJam' => 0,
                    'TotalDenda' => 0,
                    'TotalBonusLembur' => 0,
                    'JamLembur' => 0,
                    'MenitTelat' => 0
                ];
            }
            $rekap[$uid]['TotalHadir'] += 1;
            // Absensi::JamLembur is an integer or decimal
            $rekap[$uid]['JamLembur'] += ($absen->JamLembur ?? 0);
            
            // Calculate Menit Telat if needed from string or float DendaTelat column if it stores minutes.
            // Wait, DendaTelat in DB is currently a monetary amount or minutes? 
            // In older code, DendaTelat was the nominal. If it was nominal, we should count it. Let's just sum it for now.
            // For JamLembur, we sum it up.
            $rekap[$uid]['TotalDenda'] += $absen->DendaTelat;
            $rekap[$uid]['TotalBonusLembur'] += $absen->BonusLembur;
        }

        // Ambil Data Izin (Status = Disetujui)
        $pengajuanIzin = PengajuanAbsen::where('RecordOwnerID', $roid)
            ->where('StatusApproval', 'Disetujui')
            ->where(function ($q) use ($bulan, $tahun) {
                $q->whereMonth('TanggalMulai', $bulan)->whereYear('TanggalMulai', $tahun)
                  ->orWhereMonth('TanggalSelesai', $bulan)->whereYear('TanggalSelesai', $tahun);
            })->get();
        
        $rekapIzin = [];
        foreach($pengajuanIzin as $izin) {
            $uid = $izin->user_id;
            if(!isset($rekapIzin[$uid])) $rekapIzin[$uid] = 0;
            
            // Hitung jumlah hari izin pada bulan tersebut
            $start = Carbon::parse($izin->TanggalMulai);
            $end = Carbon::parse($izin->TanggalSelesai);
            
            for($d = $start; $d->lte($end); $d->addDay()) {
                if ($d->format('m') == $bulan && $d->format('Y') == $tahun && !$d->isSunday()) {
                    // Cek apakah hari tsb bukan hari libur
                    $isLibur = HariLibur::where('RecordOwnerID', $roid)->whereDate('Tanggal', $d->format('Y-m-d'))->exists();
                    if (!$isLibur) {
                        $rekapIzin[$uid] += 1;
                    }
                }
            }
        }

        // Gabungkan dengan Master Gaji
        $users = User::select('users.id', 'users.name', 
                DB::raw('IFNULL(karyawan_payroll.GajiPokok, 0) as GajiPokok'), 
                DB::raw('IFNULL(karyawan_payroll.TarifLemburPerJam, 0) as TarifLemburPerJam'),
                DB::raw('IFNULL(karyawan_payroll.TarifDendaPerMenit, 0) as TarifDendaPerMenit'),
                'karyawan_payroll.KodeAkunGaji')
            ->leftJoin('karyawan_payroll', 'users.id', '=', 'karyawan_payroll.user_id')
            ->where('users.RecordOwnerID', $roid)
            ->with('komponenGaji')
            ->get();

        $dataPenggajian = [];
        foreach($users as $u) {
            $uid = $u->id;
            $hadir = $rekap[$uid]['TotalHadir'] ?? 0;
            $izin = $rekapIzin[$uid] ?? 0;
            
            // Denda
            $denda = $rekap[$uid]['TotalDenda'] ?? 0;
            
            // Lembur
            $jamLembur = $rekap[$uid]['JamLembur'] ?? 0;
            $lembur = 0;
            if($u->TarifLemburPerJam > 0 && $jamLembur > 0) {
                $lembur = $jamLembur * $u->TarifLemburPerJam;
            } else {
                $lembur = $rekap[$uid]['TotalBonusLembur'] ?? 0;
            }
            
            // Hitung Mangkir
            $mangkir = $totalHariKerja - $hadir - $izin;
            if ($mangkir < 0) $mangkir = 0;

            // Potongan Mangkir Prorata
            $potonganMangkir = 0;
            if ($totalHariKerja > 0) {
                $potonganMangkir = round(($u->GajiPokok / $totalHariKerja) * $mangkir);
            }

            // Hitung Komponen Gaji Tambahan (Tunjangan & Potongan)
            $totalTunjangan = 0;
            $totalPotongan = 0;
            $detailKomponen = [];

            if($u->komponenGaji) {
                foreach($u->komponenGaji as $komp) {
                    $nom = $komp->Nominal;
                    if($komp->Sifat == 'Harian') {
                        $nom = $nom * $hadir;
                    }

                    if($komp->Jenis == 'Tunjangan') {
                        $totalTunjangan += $nom;
                    } else {
                        $totalPotongan += $nom;
                    }

                    $detailKomponen[] = [
                        'Nama' => $komp->NamaKomponen,
                        'Nominal' => $nom,
                        'Jenis' => $komp->Jenis
                    ];
                }
            }

            $gajiBersih = $u->GajiPokok + $lembur + $totalTunjangan - $denda - $potonganMangkir - $totalPotongan;
            if($gajiBersih < 0) $gajiBersih = 0;

            $dataPenggajian[] = [
                'user_id' => $uid,
                'name' => $u->name,
                'Hadir' => $hadir,
                'Izin' => $izin,
                'Mangkir' => $mangkir,
                'GajiPokok' => $u->GajiPokok,
                'TarifLemburPerJam' => $u->TarifLemburPerJam,
                'TarifDendaPerMenit' => $u->TarifDendaPerMenit,
                'Lembur' => $lembur,
                'Denda' => $denda,
                'PotonganMangkir' => $potonganMangkir,
                'TotalTunjangan' => $totalTunjangan,
                'TotalPotonganLain' => $totalPotongan,
                'DetailKomponen' => $detailKomponen,
                'GajiBersih' => $gajiBersih,
                'KodeAkunGaji' => $u->KodeAkunGaji
            ];
        }

        // Rekening Kas Bank untuk Opsi Pembayaran (Posisi Kas)
        $rekeningKasBank = Rekening::selectRaw("rekeningakutansi.*")
            ->leftJoin('kelompokrekening', function ($value){
                $value->on('kelompokrekening.id','=','rekeningakutansi.KodeKelompok')
                ->on('kelompokrekening.RecordOwnerID','=','rekeningakutansi.RecordOwnerID');
            })
            ->where('rekeningakutansi.RecordOwnerID', $roid)
            ->where('rekeningakutansi.Jenis', 2)
            ->where('kelompokrekening.Posisi', 1)
            ->where('kelompokrekening.NeracaLR', 1)
            ->get();

        return view('hrd.ProsesPenggajian', compact('dataPenggajian', 'bulan', 'tahun', 'rekeningKasBank'));
    }

    public function postingKasKeluar(Request $request)
    {
        try {
            DB::beginTransaction();

            $roid = Auth::user()->RecordOwnerID;
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
            $akunPembayar = $request->input('AkunPembayar'); // Kode Akun Kas/Bank
            $dataTerpilih = $request->input('pilih'); // array of user_id yang dichecklist

            if(!$akunPembayar) {
                return response()->json(['success' => false, 'message' => 'Pilih Akun Kas/Bank Pembayar terlebih dahulu.']);
            }
            if(!$dataTerpilih || count($dataTerpilih) == 0) {
                return response()->json(['success' => false, 'message' => 'Pilih minimal satu karyawan untuk diposting.']);
            }

            // Hitung total nilai transaksi
            $detailKas = [];
            $totalTransaksi = 0;

            foreach($dataTerpilih as $uid) {
                // Ambil ulang nilai Gaji Bersih dari request
                $gajiBersih = str_replace(',', '', $request->input('gaji_bersih')[$uid]);
                $kodeAkunBeban = $request->input('kode_akun_beban')[$uid];
                $namaKaryawan = $request->input('nama_karyawan')[$uid];

                if(!$kodeAkunBeban) {
                    throw new \Exception("Akun Beban Gaji untuk {$namaKaryawan} belum disetting di Master Gaji.");
                }

                if($gajiBersih > 0) {
                    $totalTransaksi += $gajiBersih;
                    $detailKas[] = [
                        'KodeAkun' => $kodeAkunBeban,
                        'Keterangan' => "Gaji Karyawan: {$namaKaryawan} Periode {$bulan}-{$tahun}",
                        'TotalTransaksi' => $gajiBersih
                    ];
                }
            }

            if($totalTransaksi <= 0) {
                throw new \Exception("Total Gaji Bersih yang dipilih adalah Rp 0. Tidak ada yang perlu diposting.");
            }

            // ============================================
            // INJEKSI KE KasKeluarHeader & KasKeluarDetail
            // (Mengikuti alur KasKeluarController)
            // ============================================
            $currentDate = Carbon::now();
            $numberingData = new DocumentNumbering();
            $NoTransaksi = $numberingData->GetNewDoc("KOUT", "kaskeluarheader", "NoTransaksi");

            $header = new KasKeluarHeader();
            $header->NoTransaksi = $NoTransaksi;
            $header->TglTransaksi = $currentDate->format('Y-m-d');
            $header->TglPencatatan = $currentDate->format('Y-m-d H:i:s');
            $header->KodeAkun = $akunPembayar;
            $header->StatusDocument = 'O'; // Open
            $header->Keterangan = "Posting Gaji Karyawan Periode {$bulan}-{$tahun}";
            $header->TotalTransaksi = $totalTransaksi;
            $header->RecordOwnerID = $roid;
            $header->save();

            $index = 0;
            foreach ($detailKas as $dt) {
                $detail = new KasKeluarDetail();
                $detail->NoTransaksi = $NoTransaksi;
                $detail->LineNumber = $index;
                $detail->KodeAkun = $dt['KodeAkun'];
                $detail->Keterangan = $dt['Keterangan'];
                $detail->TotalTransaksi = $dt['TotalTransaksi'];
                $detail->RecordOwnerID = $roid;
                $detail->save();
                $index++;
            }

            // ============================================
            // Auto Journal AccountingService
            // ============================================
            $journal = new \App\Services\AccountingService();
            $journal->initialize("KK", $currentDate->format('Y-m-d'), $NoTransaksi, "O", false);

            // Per Detail (Debet - Beban Gaji)
            foreach ($detailKas as $dt) {
                $res = $journal->addDetailWithAccount($dt['KodeAkun'], 1, $dt['TotalTransaksi'], $dt['Keterangan']);
                if (!$res['success']) throw new \Exception("Jurnal Error: " . $res['message']);
            }

            // Akun Kas/Bank Header (Kredit - Kas Pembayar)
            $res = $journal->addDetailWithAccount($akunPembayar, 2, $totalTransaksi, "Posting Gaji Karyawan Periode {$bulan}-{$tahun}");
            if (!$res['success']) throw new \Exception("Jurnal Error: " . $res['message']);

            $res = $journal->save();
            if (!$res['success']) throw new \Exception("Jurnal Save Error: " . $res['message']);

            DB::commit();

            return response()->json(['success' => true, 'message' => "Posting Kas Keluar berhasil. Nomor Bukti: {$NoTransaksi}"]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }
}
