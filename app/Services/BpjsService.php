<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BpjsService
{
    private $consId;
    private $secretKey;
    private $userKey;
    private $baseUrl;
    private $isSandbox;
    private $isActive;

    public function __construct($recordOwnerID = null)
    {
        if (!$recordOwnerID) {
            $recordOwnerID = Auth::user()->RecordOwnerID ?? null;
        }

        $setting = DB::table('klinik_bpjs_settings')
            ->where('RecordOwnerID', $recordOwnerID)
            ->first();

        if ($setting) {
            $this->consId    = $setting->ConsID;
            $this->secretKey = $setting->SecretKey;
            $this->userKey   = $setting->UserKey;
            $this->baseUrl   = $setting->BaseUrl;
            $this->isSandbox = $setting->isSandbox;
            $this->isActive  = $setting->isActive;
        }
    }

    public function generateSignature()
    {
        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $this->consId . "&" . $tStamp, $this->secretKey, true);
        $encodedSignature = base64_encode($signature);

        return [
            'X-cons-id'   => $this->consId,
            'X-timestamp' => $tStamp,
            'X-signature' => $encodedSignature,
            'user_key'    => $this->userKey
        ];
    }

    /**
     * Contoh fungsi dekripsi LZString & AES-256-CBC (Standar BPJS)
     * Untuk sekarang dikosongkan logic kompleksnya karena sandbox.
     */
    public function decryptResponse($string, $timestamp)
    {
        $key = $this->consId . $this->secretKey . $timestamp;
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
        // decompress LZString normally goes here
        return $output;
    }

    public function cekKartu($noKartu, $tanggal = null)
    {
        if (!$this->isActive) {
            return ['success' => false, 'message' => 'Integrasi BPJS belum diaktifkan oleh klinik.'];
        }

        if ($this->isSandbox) {
            // Jika Sandbox aktif, abaikan API BPJS dan kembalikan response berhasil buatan (Dummy)
            if (strlen($noKartu) < 13) {
                return ['success' => false, 'message' => 'No Kartu BPJS minimal 13 digit.'];
            }
            
            return [
                'success' => true,
                'data' => [
                    'nama' => 'PASIEN DUMMY SANDBOX',
                    'noKartu' => $noKartu,
                    'statusPeserta' => [
                        'keterangan' => 'AKTIF'
                    ],
                    'jenisPeserta' => [
                        'keterangan' => 'PEGAWAI SWASTA'
                    ],
                    'kelasRawat' => [
                        'keterangan' => 'KELAS 2'
                    ],
                    'provUmum' => [
                        'nmProvider' => 'KLINIK SANDBOX DUMMY'
                    ]
                ],
                'message' => 'Berhasil mengambil data dari Sandbox BPJS.'
            ];
        }

        // Logic pemanggilan HTTP/cURL ke BPJS asli menggunakan Guzzle
        // Endpoint: $this->baseUrl . "/Peserta/nokartu/" . $noKartu . "/tglSEP/" . $tanggal
        // ... (Belum diimplementasi utuh sampai klien memberikan kredensial)
        
        return ['success' => false, 'message' => 'API BPJS Asli belum dikonfigurasi sepenuhnya. Gunakan Mode Sandbox.'];
    }
}
