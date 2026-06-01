<?php
namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $appointments = DB::table('klinik_appointments')
            ->join('klinik_patients', 'klinik_appointments.PatientID', '=', 'klinik_patients.id')
            ->join('klinik_polis', 'klinik_appointments.PoliID', '=', 'klinik_polis.id')
            ->join('klinik_doctors', 'klinik_appointments.DoctorID', '=', 'klinik_doctors.id')
            ->select(
                'klinik_appointments.*',
                'klinik_patients.NamaPasien',
                'klinik_patients.NoRM',
                'klinik_polis.NamaPoli',
                'klinik_doctors.NamaDokter'
            )
            ->where('klinik_appointments.RecordOwnerID', $user->RecordOwnerID)
            ->orderBy('klinik_appointments.TanggalDaftar', 'desc')
            ->get();

        $patients = DB::table('klinik_patients')->where('RecordOwnerID', $user->RecordOwnerID)->get();
        $polis = DB::table('klinik_polis')->where('RecordOwnerID', $user->RecordOwnerID)->get();
        $doctors = DB::table('klinik_doctors')->where('RecordOwnerID', $user->RecordOwnerID)->get();
        $lokets = DB::table('klinik_lokets')->where('RecordOwnerID', $user->RecordOwnerID)->where('isActive', 1)->get();

        return view('klinik.appointments.index', compact('appointments', 'patients', 'polis', 'doctors', 'lokets'));
    }

    public function getDoctorsByPoli($poliId)
    {
        $user = Auth::user();
        $doctors = DB::table('klinik_doctors')
            ->where('PoliID', $poliId)
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->get();
        return response()->json($doctors);
    }

    public function getPatientBpjs($patientId)
    {
        $user = Auth::user();
        $patient = DB::table('klinik_patients')
            ->where('id', $patientId)
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->select('NoKartuBPJS', 'KelasRawatBPJS', 'FaskesBPJS', 'StatusBPJS')
            ->first();
        return response()->json($patient);
    }

    public function store(Request $request)
    {
        $request->validate([
            'PatientID' => 'required|integer',
            'PoliID' => 'required|integer',
            'DoctorID' => 'required|integer',
            'TanggalDaftar' => 'required|date',
            'CatatanPendaftaran' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        // Generate NoAntrean based on Poli and Date
        $countToday = DB::table('klinik_appointments')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->where('PoliID', $request->PoliID)
            ->whereDate('TanggalDaftar', date('Y-m-d', strtotime($request->TanggalDaftar)))
            ->count();
            
        $poli = DB::table('klinik_polis')
            ->leftJoin('klinik_doctors', 'klinik_polis.DoctorID', '=', 'klinik_doctors.id')
            ->select('klinik_polis.*', 'klinik_doctors.NamaDokter as AssignedDoctor')
            ->where('klinik_polis.id', $request->PoliID)
            ->where('klinik_polis.RecordOwnerID', $user->RecordOwnerID)
            ->first();
        $prefix = ($poli && !empty($poli->KodePoli)) ? $poli->KodePoli . '-' : 'A-';
        
        $noAntrean = $prefix . str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);

        DB::table('klinik_appointments')->insert([
            'RecordOwnerID' => $user->RecordOwnerID,
            'NoAntrean' => $noAntrean,
            'TanggalDaftar' => $request->TanggalDaftar,
            'PatientID' => $request->PatientID,
            'PoliID' => $request->PoliID,
            'DoctorID' => $request->DoctorID,
            'TipeKunjungan' => $request->TipeKunjungan ?? 'Umum',
            'NoSEP' => $request->NoSEP,
            'NoRujukan' => $request->NoRujukan,
            'Status' => 'Menunggu',
            'CatatanPendaftaran' => $request->CatatanPendaftaran,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('klinik-appointments')->with('success', 'Antrean berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Status' => 'required|string',
        ]);

        $user = Auth::user();
        DB::table('klinik_appointments')->where('id', $id)->where('RecordOwnerID', $user->RecordOwnerID)->update([
            'Status' => $request->Status,
            'updated_at' => now(),
        ]);

        return redirect()->route('klinik-appointments')->with('success', 'Status Antrean berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        DB::table('klinik_appointments')->where('id', $id)->where('RecordOwnerID', $user->RecordOwnerID)->delete();
        return redirect()->route('klinik-appointments')->with('success', 'Antrean berhasil dihapus.');
    }

    // --- FITUR DISPLAY ANTREAN POLI ---

    public function displayPoli($poli_id)
    {
        $user = Auth::user();
        $poli = DB::table('klinik_polis')
            ->leftJoin('klinik_doctors', 'klinik_polis.DoctorID', '=', 'klinik_doctors.id')
            ->select('klinik_polis.*', 'klinik_doctors.NamaDokter as AssignedDoctor')
            ->where('klinik_polis.id', $poli_id)
            ->where('klinik_polis.RecordOwnerID', $user->RecordOwnerID)
            ->first();

        if (!$poli) {
            abort(404, 'Poli tidak ditemukan');
        }

        $defaultDoctor = $poli->DoctorID ? $poli->AssignedDoctor : 'JADWAL PRAKTIK TUTUP';

        $company = DB::table('company')->where('KodePartner', $user->RecordOwnerID)->first();
        $globalVideos = [];
        if ($company) {
            if (!empty($company->VideoKlinikDisplay)) $globalVideos[] = $company->VideoKlinikDisplay;
            if (!empty($company->VideoKlinikDisplay2)) $globalVideos[] = $company->VideoKlinikDisplay2;
            if (!empty($company->VideoKlinikDisplay3)) $globalVideos[] = $company->VideoKlinikDisplay3;
            if (!empty($company->VideoKlinikDisplay4)) $globalVideos[] = $company->VideoKlinikDisplay4;
            if (!empty($company->VideoKlinikDisplay5)) $globalVideos[] = $company->VideoKlinikDisplay5;
        }

        return view('klinik.display.poli', compact('poli', 'defaultDoctor', 'globalVideos'));
    }

    public function getDisplayPoliData($poli_id)
    {
        $user = Auth::user();
        $today = \Carbon\Carbon::today()->format('Y-m-d');

        // Antrean Terakhir yang sedang diperiksa (dipanggil) KHUSUS POLI INI
        $currentCall = DB::table('klinik_appointments')
            ->join('klinik_patients', 'klinik_appointments.PatientID', '=', 'klinik_patients.id')
            ->join('klinik_polis', 'klinik_appointments.PoliID', '=', 'klinik_polis.id')
            ->leftJoin('klinik_doctors', 'klinik_appointments.DoctorID', '=', 'klinik_doctors.id')
            ->select('klinik_appointments.NoAntrean', 'klinik_appointments.updated_at', 'klinik_patients.NamaPasien', 'klinik_polis.NamaPoli', 'klinik_doctors.NamaDokter')
            ->where('klinik_appointments.RecordOwnerID', $user->RecordOwnerID)
            ->where('klinik_appointments.PoliID', $poli_id)
            ->whereDate('klinik_appointments.TanggalDaftar', $today)
            ->where('klinik_appointments.Status', 'Diperiksa')
            ->orderBy('klinik_appointments.updated_at', 'desc')
            ->first();

        // 5 Antrean Menunggu Selanjutnya KHUSUS POLI INI
        $waitingList = DB::table('klinik_appointments')
            ->join('klinik_polis', 'klinik_appointments.PoliID', '=', 'klinik_polis.id')
            ->select('klinik_appointments.NoAntrean', 'klinik_polis.NamaPoli')
            ->where('klinik_appointments.RecordOwnerID', $user->RecordOwnerID)
            ->where('klinik_appointments.PoliID', $poli_id)
            ->whereDate('klinik_appointments.TanggalDaftar', $today)
            ->where('klinik_appointments.Status', 'Menunggu')
            ->orderBy('klinik_appointments.id', 'asc')
            ->take(6)
            ->get();

        return response()->json([
            'current_call' => $currentCall ? $currentCall : null,
            'waiting_list' => $waitingList
        ]);
    }

    public function panggilPoli(Request $request)
    {
        $user = Auth::user();
        $id = $request->id;

        if ($id) {
            $appointment = DB::table('klinik_appointments')
                ->where('id', $id)
                ->where('RecordOwnerID', $user->RecordOwnerID)
                ->first();

            if ($appointment) {
                // Update yang baru dipanggil menjadi Diperiksa
                DB::table('klinik_appointments')
                    ->where('id', $id)
                    ->update([
                        'Status' => 'Diperiksa',
                        'updated_at' => now()
                    ]);

                return response()->json(['success' => true, 'nomor' => $appointment->NoAntrean]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Gagal memanggil antrean.']);
    }

    public function ulangiPanggilPoli(Request $request)
    {
        $user = Auth::user();
        $id = $request->id;

        if ($id) {
            DB::table('klinik_appointments')
                ->where('id', $id)
                ->where('RecordOwnerID', $user->RecordOwnerID)
                ->update(['updated_at' => now()]); // Touch timestamp to trigger sound again

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengulang panggilan.']);
    }
}
