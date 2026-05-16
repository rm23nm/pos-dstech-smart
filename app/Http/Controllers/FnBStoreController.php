<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\ItemMaster;
use App\Models\Pelanggan;
use App\Models\DocumentNumbering;
use Midtrans\Config;
use Midtrans\Snap;

class FnBStoreController extends Controller
{
    private function decodeId($id = null) {
        if (!$id) {
            return request()->attributes->get('detected_roid');
        }
        // Try to decode base64, if it fails or looks like plain text, use as is
        $decoded = base64_decode($id, true);
        if ($decoded === false || !preg_match('/^[a-zA-Z0-9]+$/', $decoded)) {
            return $id;
        }
        return $decoded;
    }

    public function indexCustomDomain(Request $request)
    {
        $roid = $request->attributes->get('detected_roid');
        $context = $request->attributes->get('domain_context');
        
        if (!$roid) {
            return redirect(config('app.url'));
        }

        $encodedRoid = base64_encode($roid);

        switch ($context) {
            case 'BOOKING':
                return redirect()->route('booking-index', ['id' => $encodedRoid]);
            case 'QUEUE':
                return redirect()->route('queue-management', ['id' => $encodedRoid]);
            case 'KDS':
                return redirect()->route('infokitchen');
            default:
                return $this->index(null);
        }
    }

    public function index($id)
    {
        $roid = $this->decodeId($id);
        if (session()->has('customer_id') && session('roid') == $roid) {
            return redirect()->route($id ? 'fnb-store.menu' : 'fnb-store.menu.custom', $id ? ['id' => $id] : []);
        }
        return redirect()->route($id ? 'fnb-store.login' : 'fnb-store.login.custom', $id ? ['id' => $id] : []);
    }

    // Custom Domain Wrappers
    public function showLoginCustom() { return $this->showLogin(null); }
    public function loginCustom(Request $request) { return $this->login($request, null); }
    public function logoutCustom() { return $this->logout(null); }
    public function menuCustom() { return $this->menu(null); }
    public function checkoutCustom(Request $request) { return $this->checkout($request, null); }
    public function statusCustom($orderId) { return $this->status(null, $orderId); }
    public function showRegisterCustom(Request $request) { return $this->showRegister(null, $request); }
    public function registerCustom(Request $request) { return $this->register($request, null); }

    public function showLogin($id)
    {
        $roid = $this->decodeId($id);
        $company = Company::where('KodePartner', $roid)->first();
        if (!$company) abort(404, 'Client not found');
        
        return view('fnb_store.login', compact('company', 'id'));
    }

    public function login(Request $request, $id)
    {
        $roid = $this->decodeId($id);
        $request->validate([
            'identifier' => 'required',
        ]);

        $customer = Pelanggan::where('RecordOwnerID', $roid)
            ->where(function($query) use ($request) {
                $query->where('NoTlp1', $request->identifier)
                      ->orWhere('Email', $request->identifier);
            })->first();

        if ($customer) {
            session(['customer_id' => $customer->KodePelanggan]);
            session(['customer_name' => $customer->NamaPelanggan]);
            session(['customer_email' => $customer->Email]);
            session(['roid' => $roid]);
            return redirect()->route($id ? 'fnb-store.menu' : 'fnb-store.menu.custom', $id ? ['id' => $id] : []);
        }

        // Redirect to register if not found
        return redirect()->route($id ? 'fnb-store.register' : 'fnb-store.register.custom', $id ? ['id' => $id, 'identifier' => $request->identifier] : ['identifier' => $request->identifier])
            ->with('info', 'Data Anda belum terdaftar. Silakan lengkapi data berikut.');
    }

    public function showRegister($id, Request $request)
    {
        $roid = $this->decodeId($id);
        $company = Company::where('KodePartner', $roid)->first();
        $identifier = $request->input('identifier');
        
        return view('fnb_store.register', compact('company', 'id', 'identifier'));
    }

    public function register(Request $request, $id)
    {
        $roid = $this->decodeId($id);
        $request->validate([
            'nama' => 'required|string|max:255',
            'identifier' => 'required',
        ]);

        // Generate KodePelanggan
        $prefix = "CUST-";
        $lastNo = Pelanggan::where('RecordOwnerID', $roid)->count() + 1;
        $kodePelanggan = $prefix . str_pad($lastNo, 5, '0', STR_PAD_LEFT);

        $isEmail = filter_var($request->identifier, FILTER_VALIDATE_EMAIL);

        $customer = new Pelanggan();
        $customer->KodePelanggan = $kodePelanggan;
        $customer->NamaPelanggan = $request->nama;
        $customer->RecordOwnerID = $roid;
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

        return redirect()->route($id ? 'fnb-store.menu' : 'fnb-store.menu.custom', $id ? ['id' => $id] : []);
    }

    public function logout($id)
    {
        session()->forget(['customer_id', 'customer_name', 'customer_email', 'roid']);
        return redirect()->route('fnb-store.login', ['id' => $id]);
    }

    public function menu($id)
    {
        $roid = $this->decodeId($id);
        $company = Company::where('KodePartner', $roid)->first();
        
        $categories = DB::table('jenisitem')
            ->where('RecordOwnerID', $roid)
            ->get();

        $items = ItemMaster::where('RecordOwnerID', $roid)
            ->where('Active', 'Y')
            ->whereIn('TypeItem', [1, 2])
            ->where('TampilkanEMenu', 1)
            ->get();

        $menus = [];
        foreach ($items as $item) {
            $catName = 'Lainnya';
            $cat = $categories->where('KodeJenis', $item->KodeJenisItem)->first();
            if ($cat) $catName = $cat->NamaJenis;

            $imageSrc = "https://i.pinimg.com/474x/8c/c0/30/8cc030fe28355bb3c6dc38fdbd449bc9.jpg";
            if (!empty($item->Gambar)) {
                $imageSrc = "data:image/jpeg;base64," . base64_encode($item->Gambar);
            }

            $menus[] = [
                'id' => $item->KodeItem,
                'name' => $item->NamaItem,
                'price' => $item->HargaJual,
                'image' => $imageSrc,
                'category' => $catName,
                'description' => $item->NamaItem
            ];
        }

        $paymentMethods = DB::table('metodepembayaran')
            ->where('RecordOwnerID', $roid)
            ->where('MetodeVerifikasi', 'AUTO')
            ->get();

        // Use key from company first, then fallback to first AUTO payment method
        $midtransclientkey = $company->MidtransClientKey;
        if (empty($midtransclientkey) && $paymentMethods->count() > 0) {
            $midtransclientkey = $paymentMethods->first()->ClientKey;
        }

        return view('fnb_store.menu', compact('company', 'menus', 'paymentMethods', 'midtransclientkey', 'id'));
    }

    public function checkout(Request $request, $id)
    {
        $cart = $request->input('cart');
        $total = $request->input('total');
        $paymentId = $request->input('payment_id');
        $roid = $this->decodeId($id);
        $company = Company::where('KodePartner', $roid)->first();
        $customerId = session('customer_id');

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong']);
        }

        DB::beginTransaction();
        try {
            $virtualTable = DB::table('titiklampu')
                ->where('RecordOwnerID', $roid)
                ->where('NamaTitikLampu', 'ONLINE ORDER')
                ->first();

            if (!$virtualTable) {
                $virtualTableId = DB::table('titiklampu')->insertGetId([
                    'NamaTitikLampu' => 'ONLINE ORDER',
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
            
            $now = Carbon::now();
            DB::table('tableorderheader')->insert([
                'NoTransaksi' => $noTransaksi,
                'TglTransaksi' => $now,
                'RecordOwnerID' => $roid,
                'tableid' => $virtualTableId,
                'KodePelanggan' => $customerId,
                'Status' => 1,
                'DocumentStatus' => 'O',
                'JamMulai' => $now,
                'TotalMakanan' => $total,
                'NetTotal' => $total,
                'kitchen_order_status' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ]);

            $line = 0;
            foreach ($cart as $itemId => $item) {
                DB::table('tableorderfnb')->insert([
                    'NoTransaksi' => $noTransaksi,
                    'LineNumber' => $line++,
                    'KodeItem' => $itemId,
                    'Qty' => $item['qty'],
                    'Harga' => $item['price'],
                    'LineTotal' => $item['qty'] * $item['price'],
                    'RecordOwnerID' => $roid,
                    'LineStatus' => 'O',
                    'isCompleted' => 0,
                    'OrderSource' => 'FNB-STORE',
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }

            $pm = DB::table('metodepembayaran')->where('id', $paymentId)->first();
            
            // Prioritize keys from company table if available
            $serverKey = !empty($company->MidtransServerKey) ? $company->MidtransServerKey : ($pm->ServerKey ?? '');
            
            Config::$serverKey = $serverKey;
            Config::$isProduction = config('midtrans.is_production', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $noTransaksi . '-' . time(),
                    'gross_amount' => (int)$total,
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
            Log::error('FnB Store Checkout Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function status($id, $orderId)
    {
        $roid = $this->decodeId($id);
        $order = DB::table('tableorderheader')
            ->where('NoTransaksi', $orderId)
            ->where('RecordOwnerID', $roid)
            ->first();
            
        return view('fnb_store.status', compact('order', 'id'));
    }
}
