<?php
namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patients = DB::table('klinik_patients')->where('RecordOwnerID', $user->RecordOwnerID)->get();
        $asuransis = DB::table('klinik_asuransis')->where('RecordOwnerID', $user->RecordOwnerID)->where('isActive', 1)->get();
        return view('klinik.patients.index', compact('patients', 'asuransis'));
    }

    public function cekBpjs(Request $request)
    {
        $bpjsService = new \App\Services\BpjsService();
        $response = $bpjsService->cekKartu($request->noKartu, date('Y-m-d'));
        
        // Auto-save ke database jika patient_id dikirim (misal dari form Kunjungan)
        if ($response['success'] && $request->has('patient_id')) {
            $user = Auth::user();
            DB::table('klinik_patients')->where('id', $request->patient_id)->where('RecordOwnerID', $user->RecordOwnerID)->update([
                'NoKartuBPJS' => $request->noKartu,
                'KelasRawatBPJS' => $response['data']['kelasRawat']['keterangan'] ?? null,
                'FaskesBPJS' => $response['data']['provUmum']['nmProvider'] ?? null,
                'StatusBPJS' => $response['data']['statusPeserta']['keterangan'] ?? null,
            ]);
        }

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $request->validate([
            'NIK' => 'nullable|string|max:20',
            'NamaPasien' => 'required|string|max:255',
            'TanggalLahir' => 'nullable|date',
            'JenisKelamin' => 'nullable|in:L,P',
            'Alamat' => 'nullable|string',
            'NoHP' => 'nullable|string|max:20',
            'GolonganDarah' => 'nullable|string|max:5',
            'RiwayatAlergi' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        // Generate NoRM: RM-YYMMDD-XXXX
        $count = DB::table('klinik_patients')->where('RecordOwnerID', $user->RecordOwnerID)->count() + 1;
        $noRM = 'RM-' . date('ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $newId = DB::table('klinik_patients')->insertGetId([
            'RecordOwnerID' => $user->RecordOwnerID,
            'NoRM' => $noRM,
            'NIK' => $request->NIK,
            'NamaPasien' => $request->NamaPasien,
            'TanggalLahir' => $request->TanggalLahir,
            'JenisKelamin' => $request->JenisKelamin,
            'Alamat' => $request->Alamat,
            'NoHP' => $request->NoHP,
            'GolonganDarah' => $request->GolonganDarah,
            'RiwayatAlergi' => $request->RiwayatAlergi,
            'NoKartuBPJS' => $request->NoKartuBPJS,
            'KelasRawatBPJS' => $request->KelasRawatBPJS,
            'FaskesBPJS' => $request->FaskesBPJS,
            'StatusBPJS' => $request->StatusBPJS,
            'ProviderAsuransiLain' => $request->ProviderAsuransiLain,
            'NoKartuAsuransiLain' => $request->NoKartuAsuransiLain,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->has('is_ajax')) {
            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil ditambahkan',
                'data' => [
                    'id' => $newId,
                    'NoRM' => $noRM,
                    'NamaPasien' => $request->NamaPasien
                ]
            ]);
        }

        return redirect()->route('klinik-patients')->with('success', 'Data Pasien berhasil ditambahkan. (No RM: ' . $noRM . ')');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'NamaPasien' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        DB::table('klinik_patients')->where('id', $id)->where('RecordOwnerID', $user->RecordOwnerID)->update([
            'NIK' => $request->NIK,
            'NamaPasien' => $request->NamaPasien,
            'TanggalLahir' => $request->TanggalLahir,
            'JenisKelamin' => $request->JenisKelamin,
            'Alamat' => $request->Alamat,
            'NoHP' => $request->NoHP,
            'GolonganDarah' => $request->GolonganDarah,
            'RiwayatAlergi' => $request->RiwayatAlergi,
            'NoKartuBPJS' => $request->NoKartuBPJS,
            'KelasRawatBPJS' => $request->KelasRawatBPJS,
            'FaskesBPJS' => $request->FaskesBPJS,
            'StatusBPJS' => $request->StatusBPJS,
            'ProviderAsuransiLain' => $request->ProviderAsuransiLain,
            'NoKartuAsuransiLain' => $request->NoKartuAsuransiLain,
            'updated_at' => now(),
        ]);

        return redirect()->route('klinik-patients')->with('success', 'Data Pasien berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        DB::table('klinik_patients')->where('id', $id)->where('RecordOwnerID', $user->RecordOwnerID)->delete();
        return redirect()->route('klinik-patients')->with('success', 'Data Pasien berhasil dihapus.');
    }
}
