<?php

namespace App\Http\Controllers;

use App\Models\MemberPackage;
use App\Models\ItemMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberPackageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $packages = MemberPackage::where('RecordOwnerID', $user->RecordOwnerID)->get();
        $kelompokLampu = DB::table('tkelompoklampu')->where('RecordOwnerID', $user->RecordOwnerID)->get();
        return view('master.memberpackage.index', compact('packages', 'kelompokLampu'));
    }

    public function create()
    {
        return view('master.memberpackage.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        DB::beginTransaction();
        try {
            // Save to member_packages
            $package = new MemberPackage();
            $package->KodePaket = $request->input('KodePaket');
            $package->NamaPaket = $request->input('NamaPaket');
            $package->Harga = $request->input('Harga', 0);
            $package->Tipe = $request->input('Tipe', 'UNLIMITED');
            $package->KategoriPaket = $request->input('KategoriPaket', 'HIBURAN');
            $package->KelompokLampu = $request->input('KelompokLampu');
            $package->ValidDays = $request->input('ValidDays', 30);
            $package->MaxPlay = $request->input('MaxPlay', 0);
            $package->maxTimePerPlay = $request->input('maxTimePerPlay', 0);
            $package->MemberPrice = $request->input('MemberPrice', 0);
            $package->DiskonPersen = $request->input('DiskonPersen', 0);
            $package->MaxGratisOngkir = $request->input('MaxGratisOngkir', 0);
            $package->RecordOwnerID = $user->RecordOwnerID;
            $package->save();

            // Sync to ItemMaster
            $item = new ItemMaster();
            $item->KodeItem = $package->KodePaket;
            $item->NamaItem = $package->NamaPaket;
            $item->HargaJual = $package->Harga;
            $item->KodeJenisItem = 'MEMBER';
            $item->TypeItem = 4; // Service / Non-stock
            $item->Active = 'Y';
            $item->Satuan = 'PCS';
            $item->RecordOwnerID = $user->RecordOwnerID;
            
            // Default required fields
            $item->KodeMerk = '';
            $item->Rak = '';
            $item->KodeGudang = '';
            $item->KodeSupplier = '';
            $item->Barcode = '';
            $item->Stock = 0;
            $item->StockMinimum = 0;
            $item->isKonsinyasi = 'N';
            $item->Gambar = '';
            $item->HargaPokokPenjualan = 0;
            $item->HargaBeliTerakhir = 0;
            $item->VatPercent = 0;
            $item->AcctHPP = '';
            $item->AcctPenjualan = '';
            $item->AcctPenjualanJasa = '';
            $item->AcctPersediaan = '';
            $item->TampilkanEMenu = 0;
            
            $item->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Paket berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $package = MemberPackage::where('RecordOwnerID', $user->RecordOwnerID)->where('id', $id)->first();
        return response()->json($package);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        DB::beginTransaction();
        try {
            $package = MemberPackage::where('RecordOwnerID', $user->RecordOwnerID)->where('id', $id)->firstOrFail();
            
            $oldKodePaket = $package->KodePaket;
            
            $package->KodePaket = $request->input('KodePaket');
            $package->NamaPaket = $request->input('NamaPaket');
            $package->Harga = $request->input('Harga', 0);
            $package->Tipe = $request->input('Tipe', 'UNLIMITED');
            $package->KategoriPaket = $request->input('KategoriPaket', 'HIBURAN');
            $package->KelompokLampu = $request->input('KelompokLampu');
            $package->ValidDays = $request->input('ValidDays', 30);
            $package->MaxPlay = $request->input('MaxPlay', 0);
            $package->maxTimePerPlay = $request->input('maxTimePerPlay', 0);
            $package->MemberPrice = $request->input('MemberPrice', 0);
            $package->DiskonPersen = $request->input('DiskonPersen', 0);
            $package->MaxGratisOngkir = $request->input('MaxGratisOngkir', 0);
            $package->save();

            // Sync to ItemMaster
            $item = ItemMaster::where('RecordOwnerID', $user->RecordOwnerID)->where('KodeItem', $oldKodePaket)->first();
            if ($item) {
                $item->KodeItem = $package->KodePaket;
                $item->NamaItem = $package->NamaPaket;
                $item->HargaJual = $package->Harga;
                $item->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Paket berhasil diupdate']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $package = MemberPackage::where('RecordOwnerID', $user->RecordOwnerID)->where('id', $id)->firstOrFail();
            
            // Hapus dari ItemMaster
            ItemMaster::where('RecordOwnerID', $user->RecordOwnerID)->where('KodeItem', $package->KodePaket)->delete();
            
            $package->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Paket berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
