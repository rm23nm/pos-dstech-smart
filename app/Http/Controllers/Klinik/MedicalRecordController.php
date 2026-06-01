<?php
namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Get today's appointments that are not canceled
        $appointments = DB::table('klinik_appointments')
            ->join('klinik_patients', 'klinik_appointments.PatientID', '=', 'klinik_patients.id')
            ->join('klinik_polis', 'klinik_appointments.PoliID', '=', 'klinik_polis.id')
            ->join('klinik_doctors', 'klinik_appointments.DoctorID', '=', 'klinik_doctors.id')
            ->select(
                'klinik_appointments.*',
                'klinik_patients.NamaPasien',
                'klinik_patients.NoRM',
                'klinik_patients.TanggalLahir',
                'klinik_polis.NamaPoli',
                'klinik_doctors.NamaDokter'
            )
            ->where('klinik_appointments.RecordOwnerID', $user->RecordOwnerID)
            ->whereDate('klinik_appointments.TanggalDaftar', date('Y-m-d'))
            ->where('klinik_appointments.Status', '!=', 'Batal')
            ->orderBy('klinik_appointments.NoAntrean', 'asc')
            ->get();

        return view('klinik.emr.index', compact('appointments'));
    }

    public function create($appointmentId)
    {
        $user = Auth::user();
        
        $appointment = DB::table('klinik_appointments')
            ->join('klinik_patients', 'klinik_appointments.PatientID', '=', 'klinik_patients.id')
            ->join('klinik_polis', 'klinik_appointments.PoliID', '=', 'klinik_polis.id')
            ->join('klinik_doctors', 'klinik_appointments.DoctorID', '=', 'klinik_doctors.id')
            ->select(
                'klinik_appointments.*',
                'klinik_patients.NamaPasien',
                'klinik_patients.NoRM',
                'klinik_patients.TanggalLahir',
                'klinik_patients.JenisKelamin',
                'klinik_patients.GolonganDarah',
                'klinik_patients.RiwayatAlergi',
                'klinik_polis.NamaPoli',
                'klinik_doctors.NamaDokter'
            )
            ->where('klinik_appointments.id', $appointmentId)
            ->where('klinik_appointments.RecordOwnerID', $user->RecordOwnerID)
            ->first();

        if (!$appointment) {
            return redirect()->route('klinik-emr')->withErrors('Antrean tidak ditemukan.');
        }

        // Cek apakah sudah ada rekam medis sebelumnya untuk appointment ini
        $emr = DB::table('klinik_medical_records')
            ->where('AppointmentID', $appointmentId)
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->first();

        return view('klinik.emr.create', compact('appointment', 'emr'));
    }

    public function store(Request $request, $appointmentId)
    {
        $request->validate([
            'Keluhan' => 'nullable|string',
            'PemeriksaanFisik' => 'nullable|string',
            'Diagnosa' => 'required|string',
            'Tindakan' => 'nullable|string',
            'ResepObat' => 'nullable|string',
            'CatatanTambahan' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        $appointment = DB::table('klinik_appointments')
            ->where('id', $appointmentId)
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->first();

        if (!$appointment) {
            return redirect()->route('klinik-emr')->withErrors('Antrean tidak valid.');
        }

        // Cek EMR exist
        $emrExists = DB::table('klinik_medical_records')
            ->where('AppointmentID', $appointmentId)
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->first();

        if ($emrExists) {
            // Update
            DB::table('klinik_medical_records')
                ->where('id', $emrExists->id)
                ->update([
                    'Keluhan' => $request->Keluhan,
                    'PemeriksaanFisik' => $request->PemeriksaanFisik,
                    'Diagnosa' => $request->Diagnosa,
                    'Tindakan' => $request->Tindakan,
                    'ResepObat' => $request->ResepObat,
                    'CatatanTambahan' => $request->CatatanTambahan,
                    'updated_at' => now(),
                ]);
        } else {
            // Insert
            DB::table('klinik_medical_records')->insert([
                'RecordOwnerID' => $user->RecordOwnerID,
                'AppointmentID' => $appointmentId,
                'PatientID' => $appointment->PatientID,
                'DoctorID' => $appointment->DoctorID,
                'TanggalPeriksa' => now(),
                'Keluhan' => $request->Keluhan,
                'PemeriksaanFisik' => $request->PemeriksaanFisik,
                'Diagnosa' => $request->Diagnosa,
                'Tindakan' => $request->Tindakan,
                'ResepObat' => $request->ResepObat,
                'CatatanTambahan' => $request->CatatanTambahan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update Appointment status to 'Selesai'
        DB::table('klinik_appointments')
            ->where('id', $appointmentId)
            ->update([
                'Status' => 'Selesai',
                'updated_at' => now(),
            ]);

        return redirect()->route('klinik-emr')->with('success', 'Rekam Medis berhasil disimpan.');
    }
}
