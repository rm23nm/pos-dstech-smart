<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Log;

use App\Models\Company;
use App\Models\Mekanik;
use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\ItemMaster;
use App\Models\Diskon;
use App\Models\MetodePembayaran;
use App\Models\Sales;
use App\Models\GrupPelanggan;
use App\Models\Provinsi;
use App\Models\Printer;
use App\Models\TipeOrderResto;
use App\Models\KelompokMeja;
use App\Models\Meja;
use App\Models\MenuRestoHeader;
use App\Models\MenuRestoDetail;
use App\Models\MenuRestoVariant;
use App\Models\VariantMenuHeader;
use App\Models\VariantMenuDetail;
use App\Models\JenisItem;
use App\Models\MenuRestoAddon;
// require_once(app_path('Libraries/phpserial/src/PhpSerial.php'));
class PoSController extends Controller
{
    public function ViewBengkel(Request $request)
    {
        $sql = "pelanggan.*, CONCAT(COALESCE(NoTlp1,''),CASE WHEN COALESCE(NoTlp2,'') != '' THEN ' / ' ELSE '' END , COALESCE(NoTlp2,'')) NoTlpConcat ";
        $pelanggan = Pelanggan::selectRaw($sql)
                    ->where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                    ->where('Status','=',1)
                    ->get();
        $sales = Sales::Where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                    ->where('Status','=',1)
                    ->get();
        $company = Company::Where('KodePartner','=',Auth::user()->RecordOwnerID)->get();
        $mekanik = Mekanik::Where('RecordOwnerID','=',Auth::user()->RecordOwnerID)->where('Status','=',1)->get();
        
        $itemmaster = ItemMaster::Where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                    ->where('Active','=','Y')
                    ->get();
        
        $itemServices = ItemMaster::Where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                    ->where('Active','=','Y')
                    ->where('TypeItem','=',4)
                    ->get();
        
        $diskon = Diskon::Where('RecordOwnerID','=',Auth::user()->RecordOwnerID)->get();
        $metodePembayaran = MetodePembayaran::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)->get();

        $gruppelanggan = GrupPelanggan::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)->get();
        $provinsi = Provinsi::all();

        $printer = Printer::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                    ->where('DeviceAddress','=', $company[0]['NamaPosPrinter'])->first();
        if ($printer == null) {
            $printer = "[]";
        }

        return view('Transaksi.Penjualan.PoS.BengkelPoS',[
            'pelanggan' => $pelanggan,
            'itemmaster' => $itemmaster,
            'itemServices' => $itemServices,
            'diskon' => $diskon,
            'metodepembayaran' => $metodePembayaran,
            'company' => $company,
            'sales' => $sales,
            'mekanik' => $mekanik,
            'gruppelanggan' => $gruppelanggan,
            'provinsi' => $provinsi,
            'printer' => $printer
        ]);
    }

    public function View(Request $request)
    {
        $sql = "pelanggan.*, CONCAT(COALESCE(NoTlp1,''),CASE WHEN COALESCE(NoTlp2,'') != '' THEN ' / ' ELSE '' END , COALESCE(NoTlp2,'')) NoTlpConcat ";
        $pelanggan = Pelanggan::selectRaw($sql)
                    ->where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                    ->where('Status','=',1)
                    ->get();
        $sales = Sales::Where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                    ->where('Status','=',1)
                    ->get();
        $company = Company::Where('KodePartner','=',Auth::user()->RecordOwnerID)->get();

        $kategoriTujuan = 'HIBURAN';
        if ($company[0]["JenisUsaha"] == 'Retail') {
            $kategoriTujuan = 'RETAIL';
        } elseif ($company[0]["JenisUsaha"] == 'FnB') {
            $kategoriTujuan = 'FNB';
        }

        $excludedPackages = \DB::table('member_packages')
            ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
            ->where('KategoriPaket', '!=', $kategoriTujuan)
            ->pluck('KodePaket')->toArray();

        $itemServices = ItemMaster::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                            ->where('Active','=','Y')
                            ->where('TypeItem','=',4)
                            ->whereNotIn('KodeItem', $excludedPackages)
                            ->get();
        $metodepembayaran = MetodePembayaran::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)->get();

        $gruppelanggan = GrupPelanggan::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)->get();
        $provinsi = Provinsi::all();

        $printer = Printer::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                    ->where('DeviceAddress','=', $company[0]['NamaPosPrinter'])->first();


        if ($printer == null) {
            $printer = "[]";
        }
        // var_dump($company[0]["JenisUsaha"]);
        switch ($company[0]["JenisUsaha"]) {
            case 'Retail':
                $viewName = "Transaksi.Penjualan.PoS.NormalPoS_Premium";
                if (!empty($company[0]["PosTemplate"]) && $company[0]["PosTemplate"] === 'NormalPoS_Legacy') {
                    $viewName = "Transaksi.Penjualan.PoS.NormalPoS";
                }
                return view($viewName,[
                    'pelanggan' => $pelanggan,
                    'company' => $company,
                    'itemServices' =>$itemServices,
                    'metodepembayaran' => $metodepembayaran,
                    'sales' => $sales,
                    'gruppelanggan' => $gruppelanggan,
                    'provinsi' => $provinsi,
                    'printer' => $printer
                ]);
                break;
            case 'Apotek':
                $viewName = "Transaksi.Penjualan.PoS.ApotekPoS";
                return view($viewName,[
                    'pelanggan' => $pelanggan,
                    'company' => $company,
                    'itemServices' =>$itemServices,
                    'metodepembayaran' => $metodepembayaran,
                    'sales' => $sales,
                    'gruppelanggan' => $gruppelanggan,
                    'provinsi' => $provinsi,
                    'printer' => $printer
                ]);
                break;
            case 'FnB':
                // alert()->error('Error','Fitur PoS untuk Bisnis FnB Belum Tersedia');
                // return redirect()->back();
                $kelompokmeja = KelompokMeja::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)->get();
                $meja = Meja::select('meja.*', 'titiklampu.Status as LampuStatus', 'titiklampu.ControllerID as LampuControllerID', 'titiklampu.id as LampuID')
                            ->leftJoin('titiklampu', function($join) {
                                $join->on('meja.NamaMeja', '=', 'titiklampu.NamaTitikLampu')
                                     ->on('meja.RecordOwnerID', '=', 'titiklampu.RecordOwnerID');
                            })
                            ->where('meja.RecordOwnerID','=',Auth::user()->RecordOwnerID)
                            ->get();

                $tipeorder = TipeOrderResto::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)->get();
                $jenisitem = JenisItem::where('RecordOwnerID','=',Auth::user()->RecordOwnerID)->get();

                $sql = "itemmaster.KodeItem, itemmaster.NamaItem, menuheader.HargaPokokStandar, menuheader.HargaJual, 
                        menuheader.Gambar";
                $itemmenu = ItemMaster::selectRaw($sql)
                            ->Join('menuheader', function ($value){
                                $value->on('menuheader.KodeItemHasil','=','itemmaster.KodeItem')
                                ->on('menuheader.RecordOwnerID','=','itemmaster.RecordOwnerID');
                            })
                            ->where('Active','Y')
                            ->where('itemmaster.RecordOwnerID','=',Auth::user()->RecordOwnerID)
                            ->get();
                
                $sql = "menuvarian.Father, variantdetail.variant_id AS VariantGrupID, variantheader.NamaGrup, 
                variantdetail.id AS VariantID, variantdetail.NamaVariant, variantheader.OpsiPilihan, variantdetail.ExtraPrice ";
                $variantData = MenuRestoVariant::selectRaw($sql)
                                ->join('variantdetail', function ($value)  {
                                    $value->on('menuvarian.VariantGrupID','=','variantdetail.id')
                                    ->on('menuvarian.RecordOwnerID','=','variantdetail.RecordOwnerID');
                                })
                                ->join('variantheader',function ($value) {
                                    $value->on('variantheader.id','=','variantdetail.variant_id')
                                    ->on('variantheader.RecordOwnerID','=','variantdetail.RecordOwnerID');
                                })
                                ->where('menuvarian.RecordOwnerID', Auth::user()->RecordOwnerID)
                                ->get();
                
                $menuaddon = MenuRestoAddon::selectRaw("addonmenudata.*, menuaddon.NamaAddon, menuaddon.HargaAddon")
                        ->leftJoin('menuaddon', function ($value) {
                            $value->on('menuaddon.id','=','addonmenudata.AddonMenuID')
                            ->on('menuaddon.RecordOwnerID','=','addonmenudata.RecordOwnerID');
                        })
                        ->where('addonmenudata.RecordOwnerID', Auth::user()->RecordOwnerID)
                        ->get();

                return view("Transaksi.Penjualan.PoS.FnBPoS",[
                    'pelanggan' => $pelanggan,
                    'company' => $company,
                    'itemServices' =>$itemServices,
                    'metodepembayaran' => $metodepembayaran,
                    'sales' => $sales,
                    'gruppelanggan' => $gruppelanggan,
                    'provinsi' => $provinsi,
                    'printer' => $printer,
                    'itemmenu' => $itemmenu,
                    'kelompokmeja' => $kelompokmeja,
                    'meja' => $meja,
                    'tipeorder' => $tipeorder,
                    'jenisitem' => $jenisitem,
                    'variantmenu'=> $variantData,
                    'menuaddon' => $menuaddon
                ]);
                break;
            case 'Services':
                alert()->error('Error','Fitur PoS untuk Bisnis Services Belum Tersedia');
                return redirect()->back();
                break;
            case 'Hiburan' :
                return redirect()->route('billing-new');
                break;
            case 'TiketGate':
                return redirect()->route('ticketing-pos');
                break;
            default:
                alert()->error('Error','Jenis Usaha belum ada');
                break;
        }
    }

    public function GetDiscount(Request $request)
    {
        $data = array('success'=>false, 'message'=>'', 'data'=>array(), 'Diskon' => 0, 'TipeDiskon'=>'');

        $KodeItem = $request->input('KodeItem');
        $Qty = $request->input('Qty');
        $RecordOwnerID = Auth::user()->RecordOwnerID;

        $odiskon = Diskon::where('RecordOwnerID','=', $RecordOwnerID)
                    ->where('KodeItem','=', $KodeItem)->get();

        $diskon = 0;
        $diskonType = '';
        // var_dump($diskon);
        if ($odiskon) {
            foreach ($odiskon as $key) {

                if ($Qty >= $key['Minimal']) {
                    $diskon = $key['Diskon'];
                    $diskonType = $key['TipeDiskon'];
                    $data['data'] = $key;
                    // break;
                }
            }

            $data['Diskon']=$diskon;
            $data['TipeDiskon']=$diskonType;
        }

        return response()->json($data);
    }

    public function getTarikPKB(Request $request)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        $pkb = DB::table('bengkel_work_orders')
            ->where('RecordOwnerID', $recordOwnerID)
            ->where('StatusServis', 2)
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                      ->from('fakturpenjualanheader')
                      ->whereColumn('fakturpenjualanheader.NoPKB', 'bengkel_work_orders.NoPKB')
                      ->where('fakturpenjualanheader.Status', '!=', 'D');
            })
            ->get();

        return response()->json(['data' => $pkb]);
    }

    public function storeTarikPKB(Request $request, $noPkb)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        $pkb = DB::table('bengkel_work_orders')
            ->where('NoPKB', $noPkb)
            ->where('RecordOwnerID', $recordOwnerID)
            ->first();

        if (!$pkb) {
            return response()->json(['success' => false, 'message' => 'PKB tidak ditemukan']);
        }

        $details = DB::table('bengkel_work_order_details')
            ->leftJoin('itemmaster', function ($join) use ($recordOwnerID) {
                $join->on('bengkel_work_order_details.KodeItem', '=', 'itemmaster.KodeItem')
                     ->where('itemmaster.RecordOwnerID', '=', $recordOwnerID);
            })
            ->select('bengkel_work_order_details.*', 'itemmaster.NamaItem')
            ->where('bengkel_work_order_details.NoPKB', $noPkb)
            ->where('bengkel_work_order_details.RecordOwnerID', $recordOwnerID)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'PKB ditarik',
            'KodePelanggan' => $pkb->KodePelanggan,
            'PlatNomor' => $pkb->PlatNomor,
            'KodeMekanik' => $pkb->KodeMekanik,
            'data' => $details
        ]);
    }

    public function FunctionName(Request $request)
    {
    	// Initialize the class
        $serial = new \PhpSerial();

        // Specify the serial port to use (e.g., /dev/rfcomm0 for Linux or COM3 for Windows)
        $serial->deviceSet("/dev/rfcomm0"); // Update this to match your system's configuration

        // Set the serial port parameters
        $serial->confBaudRate(9600); // Adjust baud rate as needed
        $serial->confParity("none");
        $serial->confCharacterLength(8);
        $serial->confStopBits(1);
        $serial->confFlowControl("none");

        // Open the serial port
        $serial->deviceOpen();

        // Send data to the printer
        $data = "Hello, Bluetooth Printer!\n";
        $serial->sendMessage($data);

        // Close the serial port
        $serial->deviceClose();

        return response()->json(['message' => 'Printed successfully']);
    }
}
