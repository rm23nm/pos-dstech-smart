<?php
namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KioskController extends Controller
{
    // 1. Layar Kiosk (Pengambilan Antrean)
    public function index()
    {
        $user = Auth::user();
        $company = DB::table('company')->where('KodePartner', $user->RecordOwnerID)->first();
        return view('klinik.kiosk.index', compact('company'));
    }

    public function ambilAntrean(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        
        $tipe = $request->input('tipe', 'Umum');
        
        $prefix = 'U-';
        if ($tipe == 'BPJS') $prefix = 'B-';
        if ($tipe == 'Asuransi') $prefix = 'A-';

        // Cari nomor terakhir hari ini untuk tipe ini
        $lastQueue = DB::table('klinik_kiosk_queues')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->where('Tanggal', $today)
            ->where('TipeAntrean', $tipe)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastQueue) {
            // Ambil angka dari format Prefix-001
            $parts = explode('-', $lastQueue->NomorAntrean);
            if (count($parts) == 2) {
                $nextNumber = (int)$parts[1] + 1;
            } else {
                $nextNumber = (int)$lastQueue->NomorAntrean + 1;
            }
        }

        $nomorBaru = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        DB::table('klinik_kiosk_queues')->insert([
            'RecordOwnerID' => $user->RecordOwnerID,
            'NomorAntrean' => $nomorBaru,
            'TipeAntrean' => $tipe,
            'Status' => 'Menunggu',
            'Tanggal' => $today,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'nomor' => $nomorBaru,
            'message' => 'Silakan tunggu nomor Anda dipanggil.'
        ]);
    }

    // 2. Layar TV Display
    public function display()
    {
        $user = Auth::user();
        $company = DB::table('company')->where('KodePartner', $user->RecordOwnerID)->first();
        $lokets = DB::table('klinik_lokets')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->where('isActive', 1)
            ->get();
            
        return view('klinik.kiosk.display', compact('company', 'lokets'));
    }

    public function getDisplayData()
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');

        // Antrean Terakhir yang sedang dipanggil secara Global
        $currentCall = DB::table('klinik_kiosk_queues')
            ->select('klinik_kiosk_queues.*', 'klinik_lokets.NamaLoket')
            ->leftJoin('klinik_lokets', 'klinik_kiosk_queues.LoketID', '=', 'klinik_lokets.id')
            ->where('klinik_kiosk_queues.RecordOwnerID', $user->RecordOwnerID)
            ->where('klinik_kiosk_queues.Tanggal', $today)
            ->where('klinik_kiosk_queues.Status', 'Dipanggil')
            ->orderBy('klinik_kiosk_queues.updated_at', 'desc')
            ->first();

        // Ambil panggilan terakhir untuk masing-masing loket yang aktif
        $lokets = DB::table('klinik_lokets')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->where('isActive', 1)
            ->get();

        $loketStatus = [];
        foreach($lokets as $loket) {
            $lastCall = DB::table('klinik_kiosk_queues')
                ->where('RecordOwnerID', $user->RecordOwnerID)
                ->where('Tanggal', $today)
                ->whereIn('Status', ['Dipanggil', 'Selesai'])
                ->where('LoketID', $loket->id)
                ->orderBy('updated_at', 'desc')
                ->first();
                
            $loketStatus[$loket->id] = $lastCall ? $lastCall->NomorAntrean : '--';
        }

        return response()->json([
            'current_call' => $currentCall ? $currentCall->NomorAntrean : '--',
            'current_loket' => $currentCall ? ($currentCall->NamaLoket ?? 'LOKET') : '--',
            'updated_at'   => $currentCall ? $currentCall->updated_at : '',
            'loket_status' => $loketStatus
        ]);
    }

    // 3. Kontrol Admin Pendaftaran
    public function panggil(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');

        // Jika ada id spesifik yang dipanggil
        $id = $request->id;
        
        if ($id) {
            $queue = DB::table('klinik_kiosk_queues')
                ->where('id', $id)
                ->where('RecordOwnerID', $user->RecordOwnerID)
                ->first();
        } else {
            // Ambil info loket yang digunakan
            $loket = DB::table('klinik_lokets')->where('id', $request->loket_id)->first();
            $tipeAntrean = $loket ? ($loket->TipeAntrean ?? 'Semua') : 'Semua';

            // Panggil nomor pertama yang masih Menunggu
            $query = DB::table('klinik_kiosk_queues')
                ->where('RecordOwnerID', $user->RecordOwnerID)
                ->where('Tanggal', $today)
                ->where('Status', 'Menunggu');
            
            if ($tipeAntrean !== 'Semua') {
                $query->where('TipeAntrean', $tipeAntrean);
            }

            $queue = $query->orderBy('id', 'asc')->first();
        }

        if ($queue) {
            // Update semua yang sedang "Dipanggil" menjadi "Selesai" atau abaikan,
            // HANYA untuk loket yang sama, agar loket lain tidak terhapus angkanya
            DB::table('klinik_kiosk_queues')
                ->where('RecordOwnerID', $user->RecordOwnerID)
                ->where('Tanggal', $today)
                ->where('Status', 'Dipanggil')
                ->where('LoketID', $request->loket_id)
                ->update(['Status' => 'Selesai']);

            // Update yang baru dipanggil
            DB::table('klinik_kiosk_queues')
                ->where('id', $queue->id)
                ->update([
                    'Status' => 'Dipanggil',
                    'LoketID' => $request->loket_id,
                    'updated_at' => now()
                ]);

            return response()->json(['success' => true, 'nomor' => $queue->NomorAntrean]);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada antrean menunggu.']);
    }

    // 4. Ulangi Panggilan (memanggil ulang nomor yang sedang aktif Dipanggil)
    public function ulangiPanggil(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');

        // Cari nomor yang sedang aktif Dipanggil hari ini
        $queue = DB::table('klinik_kiosk_queues')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->where('Tanggal', $today)
            ->where('Status', 'Dipanggil')
            ->orderBy('updated_at', 'desc')
            ->first();

        if ($queue) {
            DB::table('klinik_kiosk_queues')
                ->where('id', $queue->id)
                ->update([
                    'LoketID' => $request->loket_id,
                    'updated_at' => now()
                ]);
            return response()->json(['success' => true, 'nomor' => $queue->NomorAntrean]);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada nomor yang sedang dipanggil.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $status = $request->Status; // 'Selesai', 'Batal'
        
        DB::table('klinik_kiosk_queues')
            ->where('id', $id)
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->update([
                'Status' => $status,
                'updated_at' => now()
            ]);
            
        return back()->with('success', 'Status antrean pendaftaran berhasil diperbarui.');
    }

    // 5. Loket Management (Settings)
    public function loketIndex()
    {
        $user = Auth::user();
        $lokets = DB::table('klinik_lokets')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->get();
            
        return view('klinik.loket.index', compact('lokets'));
    }

    public function loketStore(Request $request)
    {
        $user = Auth::user();
        DB::table('klinik_lokets')->insert([
            'RecordOwnerID' => $user->RecordOwnerID,
            'NamaLoket' => $request->NamaLoket,
            'TipeAntrean' => $request->TipeAntrean ?? 'Semua',
            'isActive' => $request->isActive ?? 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return back()->with('success', 'Loket berhasil ditambahkan');
    }

    public function loketUpdate(Request $request)
    {
        $user = Auth::user();
        DB::table('klinik_lokets')
            ->where('id', $request->id)
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->update([
                'NamaLoket' => $request->NamaLoket,
                'TipeAntrean' => $request->TipeAntrean ?? 'Semua',
                'isActive' => $request->isActive ?? 1,
                'updated_at' => now()
            ]);
        return back()->with('success', 'Loket berhasil diperbarui');
    }

    public function loketDestroy(Request $request)
    {
        $user = Auth::user();
        DB::table('klinik_lokets')
            ->where('id', $request->id)
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->delete();
        return back()->with('success', 'Loket berhasil dihapus');
    }
}
