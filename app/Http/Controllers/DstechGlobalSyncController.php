<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\DstechGlobalSubscriptionLedger;
use Log;

class DstechGlobalSyncController extends Controller
{
    /**
     * Webhook endpoint for centralizing subscription reporting
     */
    public function syncSubscription(Request $request)
    {
        Log::info('DstechGlobalSyncController: Received subscription sync request.', $request->all());

        // 1. Validate Secret Token API Key
        $token = $request->input('api_token');
        $expectedToken = env('DSTECH_API_TOKEN', 'dstech_secret_token_2026');

        if (!$token || $token !== $expectedToken) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid API Token.'
            ], 401);
        }

        // 2. Validate Request Data
        $validator = Validator::make($request->all(), [
            'app_source' => 'required|string|in:pos,smartpro,masjidku,smartaccess,other',
            'client_id' => 'required|string',
            'client_name' => 'required|string',
            'client_email' => 'nullable|string|email',
            'client_phone' => 'nullable|string',
            'package_name' => 'required|string',
            'amount' => 'required|numeric',
            'payment_status' => 'required|string|in:paid,pending,expired',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
            'transaction_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $appSource = $request->input('app_source');
            $packageName = $request->input('package_name');
            $amount = floatval($request->input('amount'));

            // 3. Upsert into database
            $ledger = DstechGlobalSubscriptionLedger::updateOrCreate(
                ['dstech_transaction_id' => $request->input('transaction_id')],
                [
                    'dstech_app_source' => $appSource,
                    'dstech_client_id' => $request->input('client_id'),
                    'dstech_client_name' => $request->input('client_name'),
                    'dstech_client_email' => $request->input('client_email'),
                    'dstech_client_phone' => $request->input('client_phone'),
                    'dstech_package_name' => $packageName,
                    'dstech_amount' => $amount,
                    'dstech_payment_status' => $request->input('payment_status'),
                    'dstech_start_date' => $request->input('start_date'),
                    'dstech_end_date' => $request->input('end_date'),
                ]
            );

            // 4. Auto-register/sync the subscription package as a service/jasa product item in central POS
            $this->syncProductItem($appSource, $packageName, $amount);

            return response()->json([
                'success' => true,
                'message' => 'Subscription and product item synchronized successfully.',
                'data' => $ledger
            ], 200);

        } catch (\Exception $e) {
            Log::error('DstechGlobalSyncController Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal server error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Auto-registers/updates subscription package product items in the main itemmaster table
     */
    public function syncProductItem($appSource, $packageName, $amount)
    {
        $recordOwnerId = 'CL0007'; // PT. DSTECH SMART PERKASA (SuperAdmin)
        $kodeItem = 'PKG_' . strtoupper($appSource) . '_' . strtoupper($packageName);
        $namaItem = 'Paket ' . ucfirst($appSource) . ' - ' . ucfirst($packageName);

        try {
            // Get default gudang, satuan, jenis, and merk under CL0007 to avoid constraint violations
            $defaultGudang = DB::table('gudang')
                ->where('RecordOwnerID', $recordOwnerId)
                ->value('KodeGudang') ?? 'GDG001';

            $defaultSatuan = DB::table('satuan')
                ->where('RecordOwnerID', $recordOwnerId)
                ->value('KodeSatuan') ?? 'PCS';

            $defaultJenis = DB::table('jenisitem')
                ->where('RecordOwnerID', $recordOwnerId)
                ->value('KodeJenis') ?? 'JASA';

            $defaultMerk = DB::table('merk')
                ->where('RecordOwnerID', $recordOwnerId)
                ->value('KodeMerk') ?? 'DSTECH';

            // Check if item already exists
            $existing = DB::table('itemmaster')
                ->where('KodeItem', $kodeItem)
                ->where('RecordOwnerID', $recordOwnerId)
                ->first();

            if ($existing) {
                // Update item price
                DB::table('itemmaster')
                    ->where('KodeItem', $kodeItem)
                    ->where('RecordOwnerID', $recordOwnerId)
                    ->update([
                        'NamaItem' => $namaItem,
                        'HargaJual' => $amount,
                        'updated_at' => now(),
                    ]);
                Log::info("DstechGlobalSyncController: Updated itemmaster product for subscription package {$kodeItem} with price {$amount}.");
            } else {
                // Insert new item master product of type 'Jasa' (TypeItem = 4)
                DB::table('itemmaster')->insert([
                    'KodeItem' => $kodeItem,
                    'NamaItem' => $namaItem,
                    'KodeJenisItem' => $defaultJenis,
                    'KodeMerk' => $defaultMerk,
                    'TypeItem' => '4', // 4 = Jasa
                    'Rak' => '-',
                    'KodeGudang' => $defaultGudang,
                    'KodeSupplier' => '-',
                    'Satuan' => $defaultSatuan,
                    'Barcode' => $kodeItem,
                    'Gambar' => '',
                    'HargaPokokPenjualan' => 0.00,
                    'HargaJual' => $amount,
                    'HargaBeliTerakhir' => 0.00,
                    'Stock' => 999999.00,
                    'StockMinimum' => 0.00,
                    'isKonsinyasi' => 'N',
                    'Active' => 'Y',
                    'AcctHPP' => '',
                    'AcctPenjualan' => '',
                    'AcctPenjualanJasa' => '',
                    'AcctPersediaan' => '',
                    'VatPercent' => 0.00,
                    'RecordOwnerID' => $recordOwnerId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                Log::info("DstechGlobalSyncController: Created new itemmaster product for subscription package {$kodeItem} with price {$amount}.");
            }
        } catch (\Exception $e) {
            Log::warning("DstechGlobalSyncController: Failed to sync product item to itemmaster: " . $e->getMessage());
        }
    }

    /**
     * Dashboard View for SuperAdmin
     */
    public function viewGlobalDashboard(Request $request)
    {
        // 1. Calculate General Statistics
        $totalRevenue = DstechGlobalSubscriptionLedger::where('dstech_payment_status', 'paid')->sum('dstech_amount');
        $totalClients = DstechGlobalSubscriptionLedger::distinct('dstech_client_id')->count('dstech_client_id');
        $totalTransactions = DstechGlobalSubscriptionLedger::count();

        // 2. Calculate Breakdowns per App Source
        $apps = ['pos', 'smartpro', 'masjidku', 'smartaccess'];
        $appStats = [];
        foreach ($apps as $app) {
            $revenue = DstechGlobalSubscriptionLedger::where('dstech_app_source', $app)
                ->where('dstech_payment_status', 'paid')
                ->sum('dstech_amount');
            
            $clients = DstechGlobalSubscriptionLedger::where('dstech_app_source', $app)
                ->distinct('dstech_client_id')
                ->count('dstech_client_id');

            $appStats[$app] = [
                'revenue' => $revenue,
                'clients' => $clients
            ];
        }

        return view('Admin.DstechGlobalDashboard', compact(
            'totalRevenue',
            'totalClients',
            'totalTransactions',
            'appStats'
        ));
    }

    /**
     * AJAX endpoint returning JSON ledger data for the DevExtreme grid
     */
    public function getGlobalLedgerData(Request $request)
    {
        try {
            $tglAwal = $request->input('TglAwal');
            $tglAkhir = $request->input('TglAkhir');
            $appSource = $request->input('AppSource');

            $query = DstechGlobalSubscriptionLedger::query();

            if ($tglAwal) {
                $query->whereDate('dstech_start_date', '>=', $tglAwal);
            }
            if ($tglAkhir) {
                $query->whereDate('dstech_start_date', '<=', $tglAkhir);
            }
            if ($appSource && $appSource !== 'all') {
                $query->where('dstech_app_source', $appSource);
            }

            $data = $query->orderBy('dstech_start_date', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handles sending promo/prospect WA message blasts
     */
    public function sendBulkProspectBroadcast(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_ids' => 'required|array',
            'message_template' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $clientIds = $request->input('client_ids');
            $messageTemplate = $request->input('message_template');

            $clients = DstechGlobalSubscriptionLedger::whereIn('id', $clientIds)->get();
            $successCount = 0;

            foreach ($clients as $client) {
                if (!$client->dstech_client_phone) continue;

                // Personalize the message
                $message = str_replace(
                    ['[NamaClient]', '[AppSource]', '[Paket]'],
                    [$client->dstech_client_name, strtoupper($client->dstech_app_source), $client->dstech_package_name],
                    $messageTemplate
                );

                // Call WA Gateway (SmartPro WA Integration)
                try {
                    $whatsappService = new \App\Services\SmartProService();
                    $whatsappService->sendDirectWAMessage($client->dstech_client_phone, $message);
                    $successCount++;
                } catch (\Exception $waEx) {
                    Log::warning("DstechGlobalSyncController: Failed to send WA blast to {$client->dstech_client_phone}: " . $waEx->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil memproses broadcast. {$successCount} pesan terkirim."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
