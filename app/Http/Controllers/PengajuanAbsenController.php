<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Log;

use App\Models\PengajuanAbsen;

class PengajuanAbsenController extends Controller
{
    // 1. Tampilan Pengajuan Saya (Karyawan)
    public function pengajuanSaya(Request $request)
    {
        $user_id = Auth::user()->id;
        $roid = Auth::user()->RecordOwnerID;

        $pengajuan = PengajuanAbsen::where('RecordOwnerID', $roid)
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('hrd.PengajuanIzin', compact('pengajuan'));
    }

    // 2. Simpan Pengajuan Baru
    public function storePengajuan(Request $request)
    {
        try {
            $request->validate([
                'JenisPengajuan' => 'required',
                'TanggalMulai' => 'required|date',
                'TanggalSelesai' => 'required|date',
                'Keterangan' => 'required'
            ]);

            $pengajuan = new PengajuanAbsen;
            $pengajuan->user_id = Auth::user()->id;
            $pengajuan->RecordOwnerID = Auth::user()->RecordOwnerID;
            $pengajuan->JenisPengajuan = $request->input('JenisPengajuan');
            $pengajuan->TanggalMulai = $request->input('TanggalMulai');
            $pengajuan->TanggalSelesai = $request->input('TanggalSelesai');
            $pengajuan->Keterangan = $request->input('Keterangan');
            
            // Handle Base64 file or normal file logic. We assume Base64 image upload from UI like webcam or file to Base64
            if ($request->has('BuktiDokumen') && $request->input('BuktiDokumen') != '') {
                $pengajuan->BuktiDokumen = $request->input('BuktiDokumen');
            }

            $pengajuan->StatusApproval = 'Pending';
            $pengajuan->save();

            return response()->json(['success' => true, 'message' => 'Pengajuan berhasil dikirim.']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengirim pengajuan: ' . $e->getMessage()]);
        }
    }

    // 3. Tampilan Approval (Manager/Admin)
    public function approvalIzin(Request $request)
    {
        $roid = Auth::user()->RecordOwnerID;

        $pengajuan = PengajuanAbsen::select('pengajuan_absen.*', 'users.name as NamaKaryawan')
            ->leftJoin('users', 'pengajuan_absen.user_id', '=', 'users.id')
            ->where('pengajuan_absen.RecordOwnerID', $roid)
            ->orderBy('pengajuan_absen.created_at', 'desc')
            ->get();

        return view('hrd.ApprovalIzin', compact('pengajuan'));
    }

    // 4. Proses Approve / Reject
    public function prosesApproval(Request $request)
    {
        try {
            $id = $request->input('id');
            $status = $request->input('status'); // 'Disetujui' or 'Ditolak'
            
            $pengajuan = PengajuanAbsen::where('id', $id)
                ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
                ->first();

            if (!$pengajuan) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.']);
            }

            $pengajuan->StatusApproval = $status;
            $pengajuan->ApprovedBy = Auth::user()->id;
            $pengajuan->save();

            return response()->json(['success' => true, 'message' => 'Pengajuan berhasil ' . strtolower($status)]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }
}
