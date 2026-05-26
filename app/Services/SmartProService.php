<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SmartProService
 * 
 * Service untuk integrasi POS DStech Smart ↔ SmartPro WA Gateway.
 * Digunakan untuk:
 * - Mengirim notifikasi WA saat transaksi E-Catalog
 * - Mengaktifkan akun SmartPro saat client berlangganan POS
 * - Komunikasi dua arah antara kedua sistem
 */
class SmartProService
{
    protected string $baseUrl;
    protected string $secretKey;
    protected string $waUserId;

    public function __construct()
    {
        $this->baseUrl   = rtrim(env('SMARTPRO_BASE_URL', 'https://smartpro.dstechsmart.com'), '/');
        $this->secretKey = env('SMARTPRO_SECRET_KEY', 'DSTECH-MASTER-PRO-2026');
        $this->waUserId  = env('SMARTPRO_WA_USER_ID', 'admin');
    }

    // =====================================================================
    // NOTIFIKASI TRANSAKSI E-CATALOG (Tahap 6)
    // =====================================================================

    /**
     * Kirim notifikasi WA ke customer setelah checkout E-Catalog berhasil.
     *
     * @param array  $order    Data order (NoTransaksi, NetTotal, DeliveryType, dll)
     * @param array  $customer Data pelanggan (NamaPelanggan, NoTlp1, Email)
     * @param array  $items    Detail item yang dipesan [{NamaItem, qty, Harga}]
     * @param object $company  Data perusahaan/toko
     */
    public function notifyECatalogOrderToCustomer(array $order, array $customer, array $items, $company): bool
    {
        $phone = $this->formatPhone($customer['NoTlp1'] ?? $customer['Email'] ?? '');
        if (empty($phone)) {
            Log::warning('[SmartPro] Notif customer gagal: nomor HP kosong.', ['order' => $order['NoTransaksi'] ?? '']);
            return false;
        }

        $namaCustomer = $customer['NamaPelanggan'] ?? 'Pelanggan';
        $namaToko     = $company->NamaPartner ?? 'Toko';
        $noTransaksi  = $order['NoTransaksi'] ?? '-';
        $total        = $order['NetTotal'] ?? 0;
        $deliveryType = $order['DeliveryType'] ?? 'PICKUP';
        $alamat       = $order['DeliveryAddress'] ?? '';
        $catatan      = $order['DeliveryNotes'] ?? '';

        // Susun baris detail item
        $itemLines = '';
        foreach ($items as $item) {
            $subtotal   = ($item['Harga'] ?? 0) * ($item['qty'] ?? 1);
            $itemLines .= "• {$item['NamaItem']} x{$item['qty']} = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }

        $pengirimanInfo = $deliveryType === 'DELIVERY'
            ? "🚚 Dikirim ke: {$alamat}"
            : "🏪 Ambil Sendiri di Toko";

        $catatanInfo = !empty($catatan) ? "\n📝 Catatan: {$catatan}" : '';

        $message = "*PESANAN DITERIMA! ✅*\n\n"
            . "Halo *{$namaCustomer}*,\n"
            . "Pesanan Anda dari *{$namaToko}* telah kami terima!\n\n"
            . "📋 *Detail Pesanan:*\n"
            . "No. Order: `{$noTransaksi}`\n\n"
            . $itemLines . "\n"
            . "💰 *Total: Rp " . number_format($total, 0, ',', '.') . "*\n"
            . $pengirimanInfo
            . $catatanInfo . "\n\n"
            . "Status pesanan akan segera diproses. Terima kasih sudah berbelanja! 🙏\n"
            . "_— {$namaToko}_";

        return $this->sendWaMessage($phone, $message);
    }

    /**
     * Kirim notifikasi WA ke pemilik toko saat ada pesanan baru E-Catalog.
     *
     * @param array  $order    Data order
     * @param array  $customer Data pelanggan
     * @param array  $items    Detail item
     * @param object $company  Data perusahaan (harus ada NoTlp1 sebagai nomor owner)
     */
    public function notifyECatalogOrderToOwner(array $order, array $customer, array $items, $company): bool
    {
        $ownerPhone = $this->formatPhone($company->NoTlp1 ?? '');
        if (empty($ownerPhone)) {
            Log::warning('[SmartPro] Notif owner gagal: nomor HP pemilik toko kosong.');
            return false;
        }

        $namaCustomer = $customer['NamaPelanggan'] ?? 'Customer';
        $noTransaksi  = $order['NoTransaksi'] ?? '-';
        $total        = $order['NetTotal'] ?? 0;
        $deliveryType = $order['DeliveryType'] ?? 'PICKUP';
        $alamat       = $order['DeliveryAddress'] ?? '-';

        $itemLines = '';
        foreach ($items as $item) {
            $subtotal   = ($item['Harga'] ?? 0) * ($item['qty'] ?? 1);
            $itemLines .= "• {$item['NamaItem']} x{$item['qty']} = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }

        $deliveryLabel = $deliveryType === 'DELIVERY' ? "🚚 DELIVERY ke: {$alamat}" : "🏪 PICKUP di Toko";

        $message = "*🛒 PESANAN BARU E-CATALOG!*\n\n"
            . "Ada pesanan masuk dari *{$namaCustomer}*\n"
            . "No. Order: `{$noTransaksi}`\n\n"
            . "📦 *Item Dipesan:*\n"
            . $itemLines . "\n"
            . "💰 *Total: Rp " . number_format($total, 0, ',', '.') . "*\n"
            . $deliveryLabel . "\n\n"
            . "Silakan cek dashboard POS untuk proses pesanan. ✨";

        return $this->sendWaMessage($ownerPhone, $message);
    }

    // =====================================================================
    // AUTO-AKTIVASI AKUN SMARTPRO
    // =====================================================================

    /**
     * Aktifkan / buat akun SmartPro untuk client POS yang berlangganan.
     *
     * @param string $email      Email client (dijadikan username SmartPro)
     * @param string $name       Nama lengkap
     * @param string $phone      Nomor HP
     * @param string $plan       Nama plan (basic/pro/premium)
     * @param string $expiresAt  Tanggal kadaluarsa format Y-m-d
     */
    public function activateClientSmartPro(string $email, string $name, string $phone, string $plan = 'basic', string $expiresAt = ''): array
    {
        try {
            $response = Http::timeout(15)->post("{$this->baseUrl}/api/external/activate-client", [
                'secret'     => $this->secretKey,
                'username'   => $email,
                'name'       => $name,
                'phone'      => $this->formatPhone($phone),
                'plan'       => $plan,
                'expires_at' => $expiresAt ?: now()->addDays(30)->format('Y-m-d'),
            ]);

            $data = $response->json();
            if ($response->successful() && ($data['success'] ?? false)) {
                Log::info("[SmartPro] Akun berhasil diaktifkan: {$email} (Plan: {$plan})");
                return ['success' => true, 'user_id' => $data['user_id'] ?? null];
            }

            Log::warning("[SmartPro] Aktivasi akun gagal: {$email}", $data);
            return ['success' => false, 'message' => $data['error'] ?? 'Aktivasi gagal'];

        } catch (\Exception $e) {
            Log::error("[SmartPro] Koneksi ke SmartPro gagal: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Public helper to send direct WA messages using the internal gateway
     */
    public function sendDirectWAMessage(string $phone, string $message): bool
    {
        $formattedPhone = $this->formatPhone($phone);
        if (empty($formattedPhone)) {
            return false;
        }
        return $this->sendWaMessage($formattedPhone, $message);
    }

    /**
     * Kirim notifikasi expired barang.
     * Mendukung per-client API Key & Sender.
     * Jika apiKey / sender null, fallback ke master secret key.
     *
     * @param string      $phone   Nomor tujuan
     * @param string      $message Isi pesan
     * @param string|null $apiKey  API Key milik client (dari kolom SmartproApiKey di company)
     * @param string|null $sender  Nomor/device pengirim client (dari kolom SmartproSender di company)
     */
    public function sendExpiredNotification(string $phone, string $message, ?string $apiKey = null, ?string $sender = null): bool
    {
        $formattedPhone = $this->formatPhone($phone);
        if (empty($formattedPhone)) {
            Log::warning('[SmartPro] sendExpiredNotification: nomor tidak valid: ' . $phone);
            return false;
        }

        try {
            if (!empty($apiKey)) {
                // Gunakan API Key per client langsung ke endpoint /api/send
                $response = Http::timeout(20)
                    ->withHeaders(['Authorization' => 'Bearer ' . $apiKey])
                    ->post("{$this->baseUrl}/api/send", [
                        'number'  => $formattedPhone,
                        'message' => $message,
                        'sender'  => $sender ?? '',
                    ]);
            } else {
                // Fallback: gunakan master secret key (endpoint eksternal)
                $response = Http::timeout(20)->post("{$this->baseUrl}/api/external/send-notification", [
                    'secret'  => $this->secretKey,
                    'user_id' => $this->waUserId,
                    'number'  => $formattedPhone,
                    'message' => $message,
                ]);
            }

            if ($response->successful()) {
                Log::info("[SmartPro] Notif Expired terkirim ke: {$formattedPhone}");
                return true;
            }

            Log::warning("[SmartPro] Gagal kirim Notif Expired ke {$formattedPhone}: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("[SmartPro] Error sendExpiredNotification: " . $e->getMessage());
            return false;
        }
    }

    // =====================================================================
    // HELPER INTERNAL
    // =====================================================================

    /**
     * Kirim pesan WA via SmartPro gateway menggunakan endpoint /send.
     * Menggunakan Secret Key (bukan login user) lewat header X-API-Key.
     */
    private function sendWaMessage(string $phone, string $message): bool
    {
        try {
            // SmartPro /send endpoint membutuhkan token user yang sudah login.
            // Kita gunakan endpoint khusus eksternal yang pakai secret key.
            $response = Http::timeout(20)->post("{$this->baseUrl}/api/external/send-notification", [
                'secret'  => $this->secretKey,
                'user_id' => $this->waUserId,
                'number'  => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("[SmartPro] WA terkirim ke: {$phone}");
                return true;
            }

            Log::warning("[SmartPro] Gagal kirim WA ke {$phone}: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("[SmartPro] Error kirim WA: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format nomor HP ke format internasional (62xxx).
     * Mendukung format: 08xxx, +628xxx, 628xxx
     */
    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (empty($phone)) return '';

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
