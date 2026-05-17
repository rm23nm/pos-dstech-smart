<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Log;
use Illuminate\Support\Facades\Schema;

use App\Models\JenisItem;
use App\Models\ItemMaster;
use App\Models\Company;
use App\Models\DocumentNumbering;
use Midtrans\Config;
use Midtrans\Snap;

class KatalogController extends Controller
{
    public function View($RecordOwnerID)
    {
        $jenisitem = JenisItem::where('RecordOwnerID',$RecordOwnerID)
                        ->get(); 
        $company = Company::where("KodePartner", $RecordOwnerID)
                        ->get();

        // Check if new columns exist to prevent errors before migration
        $flashSales = [];
        $bestSellers = [];

        if (Schema::hasColumn('itemmaster', 'isFlashSale')) {
            $flashSales = ItemMaster::where('RecordOwnerID', $RecordOwnerID)
                            ->where('isFlashSale', 'Y')
                            ->where('Active', 'Y')
                            ->limit(10)
                            ->get();
        }

        if (Schema::hasColumn('itemmaster', 'isBestSeller')) {
            $bestSellers = ItemMaster::where('RecordOwnerID', $RecordOwnerID)
                            ->where('isBestSeller', 'Y')
                            ->where('Active', 'Y')
                            ->limit(12)
                            ->get();
        }

        $paymentMethods = DB::table('metodepembayaran')
            ->where('RecordOwnerID', $RecordOwnerID)
            ->where('MetodeVerifikasi', 'AUTO')
            ->get();

        $isProd = config('midtrans.is_production', false);
        $midtransclientkey = $company->first()->MidtransClientKey ?? '';
        if (empty($midtransclientkey) && $paymentMethods->count() > 0) {
            $midtransclientkey = $paymentMethods->first()->ClientKey;
        }

        if (!$isProd && (empty($midtransclientkey) || str_starts_with($midtransclientkey, 'Mid-client-'))) {
            // Auto fallback ke sandbox client key agar testing lokal lancar
            $midtransclientkey = 'SB-Mid-client-qz748Jy7S9f3_srA';
        }

        return view("catalouge.catalouge",[
            'jenisitem' => $jenisitem,
            'RecOID' => $RecordOwnerID,
            'company' => $company,
            'flashSales' => $flashSales,
            'bestSellers' => $bestSellers,
            'midtransclientkey' => $midtransclientkey
	    ]);
    }

    public function ViewItemMaster(Request $request)
    {
      $data = array('success'=>false, 'message'=>'', 'data'=>array());
      $KodeJenis = $request->input('KodeJenis');
      $Merk = $request->input('Merk');
      $TipeItem = $request->input('TipeItem');
      $Active = $request->input('Active');
      $Scan = $request->input('Scan');
      $TipeItemIN = $request->input('TipeItemIN');
      $RecordOwnerID = $request->input('RecordOwnerID');

      $oItem = new ItemMaster();
      $itemmaster = $oItem->GetItemData($RecordOwnerID,$KodeJenis, $Merk, $TipeItem,$TipeItemIN, $Active, $Scan,1);

      $data['data'] = $itemmaster->get();

      return response()->json($data);
    }

    public function CatLogin(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'RecordOwnerID' => 'required',
        ]);

        $customer = \App\Models\Pelanggan::where('RecordOwnerID', $request->RecordOwnerID)
            ->where(function($query) use ($request) {
                $query->where('NoTlp1', $request->identifier)
                      ->orWhere('Email', $request->identifier);
            })->first();

        if ($customer) {
            if (!empty($request->password) && !empty($customer->password)) {
                if (!\Illuminate\Support\Facades\Hash::check($request->password, $customer->password)) {
                    return response()->json(['success' => false, 'message' => 'Password salah!']);
                }
            }

            session(['customer_id' => $customer->KodePelanggan]);
            session(['customer_name' => $customer->NamaPelanggan]);
            session(['customer_email' => $customer->Email]);
            session(['roid' => $request->RecordOwnerID]);
            
            Auth::guard('pelanggan')->loginUsingId($customer->PelangganID ?? $customer->KodePelanggan);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'ID tidak ditemukan. Silakan daftar terlebih dahulu.']);
    }

    public function CatRegister(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'identifier' => 'required',
            'password' => 'required',
            'RecordOwnerID' => 'required',
        ]);

        $roid = $request->RecordOwnerID;
        $existing = \App\Models\Pelanggan::where('RecordOwnerID', $roid)
            ->where(function($query) use ($request) {
                $query->where('NoTlp1', $request->identifier)
                      ->orWhere('Email', $request->identifier);
            })->first();
        
        if ($existing) {
             return response()->json(['success' => false, 'message' => 'Email/No. HP sudah terdaftar.']);
        }

        $prefix = "CUST-";
        $lastNo = \App\Models\Pelanggan::where('RecordOwnerID', $roid)->count() + 1;
        $kodePelanggan = $prefix . str_pad($lastNo, 5, '0', STR_PAD_LEFT);

        $isEmail = filter_var($request->identifier, FILTER_VALIDATE_EMAIL);

        $customer = new \App\Models\Pelanggan();
        $customer->KodePelanggan = $kodePelanggan;
        $customer->NamaPelanggan = $request->nama;
        $customer->RecordOwnerID = $roid;
        $customer->password = \Illuminate\Support\Facades\Hash::make($request->password);
        if ($isEmail) {
            $customer->Email = $request->identifier;
        } else {
            $customer->NoTlp1 = $request->identifier;
        }
        $customer->save();

        session(['customer_id' => $customer->KodePelanggan]);
        session(['customer_name' => $customer->NamaPelanggan]);
        session(['customer_email' => $customer->Email]);
        session(['roid' => $roid]);
        
        Auth::guard('pelanggan')->loginUsingId($customer->PelangganID ?? $customer->KodePelanggan);

        return response()->json(['success' => true]);
    }

    public function CatCheckout(Request $request)
    {
        $cart           = $request->input('cart'); // Array of objects
        $total          = (float)$request->input('total');
        $roid           = $request->input('RecordOwnerID');
        $customerId     = session('customer_id');
        $deliveryType   = $request->input('delivery_type', 'PICKUP'); // PICKUP | DELIVERY
        $deliveryAddr   = $request->input('delivery_address', '');
        $deliveryCost   = (float)$request->input('delivery_cost', 0);
        $deliveryNotes  = $request->input('delivery_notes', '');
        $voucherCode    = strtoupper(trim($request->input('voucher_code', '')));
        $discountAmount = (float)$request->input('discount_amount', 0);
        $grandTotal     = max(0, $total - $discountAmount) + $deliveryCost;

        if (empty($cart) || empty($customerId)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong atau Anda belum login.']);
        }

        $company = Company::where('KodePartner', $roid)->first();

        DB::beginTransaction();
        try {
            // Find or create virtual table 'E-CATALOG ORDER'
            $virtualTable = DB::table('titiklampu')
                ->where('RecordOwnerID', $roid)
                ->where('NamaTitikLampu', 'E-CATALOG ORDER')
                ->first();

            if (!$virtualTable) {
                $virtualTableId = DB::table('titiklampu')->insertGetId([
                    'NamaTitikLampu' => 'E-CATALOG ORDER',
                    'RecordOwnerID' => $roid,
                    'Status' => 0,
                    'ControllerID' => 0,
                    'DigitalInput' => 0,
                    'BisaDipesan' => 0
                ]);
            } else {
                $virtualTableId = $virtualTable->id;
            }

            $numbering = new DocumentNumbering();
            $noTransaksi = $numbering->GetNewDocMobile("POS", "tableorderheader", "NoTransaksi", $roid);
            
            $now = Carbon::now('Asia/Jakarta');
            $insertData = [
                'NoTransaksi'   => $noTransaksi,
                'TglTransaksi'  => $now,
                'TglPencatatan' => $now,
                'RecordOwnerID' => $roid,
                'tableid'       => $virtualTableId,
                'KodePelanggan' => $customerId,
                'Status'        => 1,
                'DocumentStatus'=> 'O',
                'JamMulai'      => $now,
                'JamSelesai'    => $now,
                'JenisPaket'    => 'ONLINE',
                'paketid'       => 0,
                'KodeSales'     => '',
                'DurasiPaket'   => 0,
                'TaxTotal'      => 0,
                'GrossTotal'    => $total,
                'DiscTotal'     => $discountAmount,
                'TotalMakanan'  => $total,
                'NetTotal'      => $grandTotal,
                'kitchen_order_status' => 0,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];

            // Delivery fields (tambahkan jika kolom sudah ada di DB)
            if (Schema::hasColumn('tableorderheader', 'DeliveryType')) {
                $insertData['DeliveryType']    = $deliveryType;
                $insertData['DeliveryAddress'] = $deliveryAddr;
                $insertData['DeliveryCost']    = $deliveryCost;
                $insertData['DeliveryNotes']   = $deliveryNotes;
            }

            DB::table('tableorderheader')->insert($insertData);

            $hasOrderSource = Schema::hasColumn('tableorderfnb', 'OrderSource');

            $line = 0;
            foreach ($cart as $item) {
                $detailData = [
                    'NoTransaksi' => $noTransaksi,
                    'LineNumber' => $line++,
                    'KodeItem' => $item['KodeItem'],
                    'Qty' => $item['qty'],
                    'Harga' => $item['HargaJual'],
                    'Tax' => 0,
                    'Discount' => 0,
                    'LineTotal' => $item['qty'] * $item['HargaJual'],
                    'RecordOwnerID' => $roid,
                    'LineStatus' => 'O',
                    'isCompleted' => 0,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                
                if ($hasOrderSource) {
                    $detailData['OrderSource'] = 'E-CATALOG';
                }
                
                DB::table('tableorderfnb')->insert($detailData);
            }

            $serverKey = !empty($company->MidtransServerKey) ? $company->MidtransServerKey : '';
            if (empty($serverKey)) {
                $pm = DB::table('metodepembayaran')->where('RecordOwnerID', $roid)->where('MetodeVerifikasi', 'AUTO')->first();
                if ($pm) $serverKey = $pm->ServerKey;
            }

            $isProd = config('midtrans.is_production', false);
            if (!$isProd && (empty($serverKey) || str_starts_with($serverKey, 'Mid-server-'))) {
                // Auto fallback ke sandbox server key agar testing lokal lancar
                $serverKey = 'SB-Mid-server-HX4SGB3FVpxVm-CpeOCyo4MS';
            }

            if (empty($serverKey)) {
                throw new \Exception('Sistem pembayaran Midtrans belum dikonfigurasi oleh toko.');
            }

            Config::$serverKey = $serverKey;
            Config::$isProduction = $isProd;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id'     => $noTransaksi . '-' . time(),
                    'gross_amount' => (int)$grandTotal,
                ],
                'customer_details' => [
                    'first_name' => session('customer_name'),
                    'email' => session('customer_email') ?? 'customer@dstech.com',
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            DB::commit();
            return response()->json([
                'success' => true, 
                'snap_token' => $snapToken,
                'order_id' => $noTransaksi
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('E-Catalog Checkout Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function CatOrders($id)
    {
        $RecordOwnerID = $id;
        $company = Company::where('KodePartner', $RecordOwnerID)->get();
        if ($company->count() == 0) {
            return redirect('/');
        }
        
        $customerId = session('customer_id');
        if (empty($customerId)) {
            return redirect()->route('cat-catalouge', ['ID' => $id])->with('error', 'Silakan login terlebih dahulu.');
        }

        // Fetch orders for this customer
        $orders = DB::table('tableorderheader')
            ->where('RecordOwnerID', $RecordOwnerID)
            ->where('KodePelanggan', $customerId)
            ->orderBy('TglTransaksi', 'desc')
            ->orderBy('JamMulai', 'desc')
            ->get();

        // Get details for these orders
        $orderIds = $orders->pluck('NoTransaksi')->toArray();
        
        $orderDetails = [];
        if (!empty($orderIds)) {
            $orderDetails = DB::table('tableorderfnb')
                ->leftJoin('itemmaster', 'tableorderfnb.KodeItem', '=', 'itemmaster.KodeItem')
                ->where('tableorderfnb.RecordOwnerID', $RecordOwnerID)
                ->whereIn('tableorderfnb.NoTransaksi', $orderIds)
                ->select('tableorderfnb.*', 'itemmaster.NamaItem', 'itemmaster.Gambar')
                ->get()
                ->groupBy('NoTransaksi');
        }

        return view('catalouge.orders', compact('orders', 'orderDetails', 'company', 'RecordOwnerID'));
    }

    public function CatStatus($id, $orderId)
    {
        $RecordOwnerID = $id;
        $company = Company::where('KodePartner', $RecordOwnerID)->get();
        if ($company->count() == 0) {
            return redirect('/');
        }

        $order = DB::table('tableorderheader')
            ->where('NoTransaksi', $orderId)
            ->where('RecordOwnerID', $RecordOwnerID)
            ->first();

        if (!$order) {
            abort(404, 'Pesanan tidak ditemukan.');
        }

        return view('catalouge.status', compact('order', 'company', 'RecordOwnerID'));
    }

    public function CatValidateVoucher(Request $request)
    {
        $code  = strtoupper(trim($request->input('voucher_code', '')));
        $roid  = $request->input('RecordOwnerID');
        $total = (float)$request->input('total', 0);
        $today = \Carbon\Carbon::now('Asia/Jakarta')->toDateString();

        if (empty($code)) {
            return response()->json(['success' => false, 'message' => 'Kode voucher tidak boleh kosong.']);
        }

        $voucher = DB::table('discountvoucher')
            ->where('VoucherCode', $code)
            ->where('RecordOwnerID', $roid)
            ->where('StartDate', '<=', $today)
            ->where('EndDate', '>=', $today)
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Kode voucher tidak valid atau sudah kadaluarsa.']);
        }

        if ($voucher->DiscountQuota <= 0) {
            return response()->json(['success' => false, 'message' => 'Kuota voucher ini sudah habis.']);
        }

        // Hitung diskon
        $discountAmount = $total * ($voucher->DiscountPercent / 100);
        if ($voucher->MaximalDiscount > 0 && $discountAmount > $voucher->MaximalDiscount) {
            $discountAmount = $voucher->MaximalDiscount;
        }
        $discountAmount = round($discountAmount);
        $finalTotal     = max(0, $total - $discountAmount);

        return response()->json([
            'success'          => true,
            'message'          => '✅ Voucher berhasil diterapkan!',
            'voucher_code'     => $voucher->VoucherCode,
            'discount_percent' => $voucher->DiscountPercent,
            'max_discount'     => $voucher->MaximalDiscount,
            'discount_amount'  => $discountAmount,
            'final_total'      => $finalTotal,
            'description'      => $voucher->DiscountDescription,
        ]);
    }
}
