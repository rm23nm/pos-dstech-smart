<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionInvoiceMail;
use Carbon\Carbon;

class ProcessSubscriptionStatus extends Command
{
    protected $signature = 'subscription:process-status';

    protected $description = 'Suspend expired subscriptions and generate invoices for subscriptions expiring within 7 days.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $reportData = [];
        $this->processSuspensions($reportData);
        $this->generateInvoices($reportData);

        // Jika ada tagihan yang dibuat hari ini, kirim laporan ke Superadmin
        if (count($reportData) > 0) {
            $adminEmail = env('MAIL_FROM_ADDRESS');
            if ($adminEmail) {
                try {
                    Mail::to($adminEmail)->send(new \App\Mail\SuperadminExpiryReportMail($reportData));
                    $this->info("Email laporan harian tagihan berhasil dikirim ke {$adminEmail}");
                } catch (\Exception $e) {
                    $this->error("Gagal mengirim email laporan harian ke Superadmin: " . $e->getMessage());
                    Log::error('ProcessSubscriptionStatus ReportMail: ' . $e->getMessage());
                }
            }
        }

        return 0;
    }

    // -------------------------------------------------------------------------
    // Task 1: suspend companies past their grace period, then invoice if needed
    // -------------------------------------------------------------------------
    private function processSuspensions(&$reportData): void
    {
        $this->info('Mengecek langganan yang sudah expired...');

        $expired = DB::table('company')
            ->leftJoin('terminpembayaran', 'company.TerminBayarPoS', '=', 'terminpembayaran.id')
            ->where('company.isActive', 1)
            ->where(function ($q) {
                $q->where('company.isSuspended', '!=', 1)->orWhereNull('company.isSuspended');
            })
            ->whereRaw('DATE_ADD(company.EndSubs, INTERVAL COALESCE(terminpembayaran.ExtraDays, 0) DAY) < CURDATE()')
            ->select('company.*', DB::raw('COALESCE(terminpembayaran.ExtraDays, 0) as ExtraDays'), DB::raw('DATEDIFF(company.EndSubs, CURDATE()) as SisaHari'))
            ->get();

        $count = 0;
        foreach ($expired as $company) {
            DB::table('company')
                ->where('KodePartner', $company->KodePartner)
                ->update([
                    'isSuspended'   => 1,
                    'SuspendReason' => 'Expired',
                ]);
            $this->info("Suspended: {$company->NamaPartner} ({$company->KodePartner})");
            $count++;

            // Generate invoice for suspended company if no unpaid invoice exists
            $subscription = DB::table('subscriptionheader')
                ->where('NoTransaksi', $company->KodePaketLangganan)
                ->first();

            if (!$subscription) {
                $this->error("Paket {$company->KodePaketLangganan} tidak ditemukan untuk {$company->NamaPartner}. Skip invoice.");
                continue;
            }

            $noTransaksi = $this->createInvoiceIfNeeded($company, $subscription, isSuspended: true);
            if ($noTransaksi) {
                $this->sendInvoiceEmail($company, $subscription, $noTransaksi, isSuspended: true);
                
                // Tambahkan ke laporan rekap admin
                $reportData[] = [
                    'companyName' => $company->NamaPartner,
                    'subscriptionName' => $subscription->NamaSubscription ?? 'Paket Tidak Diketahui',
                    'sisaHari' => $company->SisaHari,
                    'dueDate' => Carbon::parse($company->EndSubs)->format('d/m/Y'),
                    'isSuspended' => true
                ];
            }
        }

        $this->info("Total suspended: {$count} perusahaan.");
    }

    // -------------------------------------------------------------------------
    // Task 2: generate invoices for companies expiring within 7 days
    // -------------------------------------------------------------------------
    private function generateInvoices(&$reportData): void
    {
        $this->info('Mengecek langganan yang akan habis (30 hari tahunan, 10 hari bulanan)...');

        $companies = DB::table('company')
            ->leftJoin('terminpembayaran', 'company.TerminBayarPoS', '=', 'terminpembayaran.id')
            ->leftJoin('subscriptionheader', 'company.KodePaketLangganan', '=', 'subscriptionheader.NoTransaksi')
            ->where('company.isActive', 1)
            ->where(function ($q) {
                $q->where('company.isSuspended', '!=', 1)->orWhereNull('company.isSuspended');
            })
            // still within grace period
            ->whereRaw('DATE_ADD(company.EndSubs, INTERVAL COALESCE(terminpembayaran.ExtraDays, 0) DAY) >= CURDATE()')
            // Cek kondisi 30 hari (tahunan) atau 10 hari (bulanan)
            ->where(function ($query) {
                // Tahunan: LamaSubsription >= 365, tagihan 30 hari sebelum
                $query->where(function ($q) {
                    $q->where('subscriptionheader.LamaSubsription', '>=', 365)
                      ->whereRaw('company.EndSubs <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)');
                })
                // Bulanan: LamaSubsription < 365, tagihan 10 hari sebelum
                ->orWhere(function ($q) {
                    $q->where(function ($q2) {
                        $q2->whereNull('subscriptionheader.LamaSubsription')
                           ->orWhere('subscriptionheader.LamaSubsription', '<', 365);
                    })
                    ->whereRaw('company.EndSubs <= DATE_ADD(CURDATE(), INTERVAL 10 DAY)');
                });
            })
            ->select('company.*', 'subscriptionheader.LamaSubsription', 'subscriptionheader.NamaSubscription', DB::raw('COALESCE(terminpembayaran.ExtraDays, 0) as ExtraDays'), DB::raw('DATEDIFF(company.EndSubs, CURDATE()) as SisaHari'))
            ->get();

        if ($companies->isEmpty()) {
            $this->info('Tidak ada langganan yang akan habis dalam rentang waktu yang ditentukan.');
            return;
        }

        $count = 0;
        foreach ($companies as $company) {
            $this->info("Mengecek: {$company->NamaPartner} ({$company->KodePartner})");

            $subscription = DB::table('subscriptionheader')
                ->where('NoTransaksi', $company->KodePaketLangganan)
                ->first();

            if (!$subscription) {
                $this->error("Paket {$company->KodePaketLangganan} tidak ditemukan untuk {$company->NamaPartner}.");
                continue;
            }

            $noTransaksi = $this->createInvoiceIfNeeded($company, $subscription, isSuspended: false);
            if ($noTransaksi) {
                $this->sendInvoiceEmail($company, $subscription, $noTransaksi, isSuspended: false);
                $count++;
                
                // Tambahkan ke laporan rekap admin
                $reportData[] = [
                    'companyName' => $company->NamaPartner,
                    'subscriptionName' => $company->NamaSubscription ?? 'Paket Tidak Diketahui',
                    'sisaHari' => $company->SisaHari,
                    'dueDate' => Carbon::parse($company->EndSubs)->format('d/m/Y'),
                    'isSuspended' => false
                ];
            }
        }

        $this->info("Total invoice dibuat: {$count}.");
    }

    // -------------------------------------------------------------------------
    // Shared: insert header + detail if no unpaid invoice exists; returns NoTransaksi or null
    // -------------------------------------------------------------------------
    private function createInvoiceIfNeeded(object $company, object $subscription, bool $isSuspended): ?string
    {
        $existing = DB::table('tagihanpenggunaheader')
            ->where('KodePelanggan', $company->KodePartner)
            ->where('Status', '<>', 'D')
            ->whereRaw('TotalTagihan > TotalBayar')
            ->first();

        if ($existing) {
            $this->warn("Skip invoice {$company->NamaPartner}: sudah ada tagihan belum lunas ({$existing->NoTransaksi}).");
            return null;
        }

        $prefix      = 'INV';
        $lastCount   = DB::table('tagihanpenggunaheader')
            ->whereRaw('LEFT(NoTransaksi, 3) = ?', [$prefix])
            ->count() + 1;
        $noTransaksi = $prefix . str_pad($lastCount, 4, '0', STR_PAD_LEFT);
        while (DB::table('tagihanpenggunaheader')->where('NoTransaksi', $noTransaksi)->exists()) {
            $lastCount++;
            $noTransaksi = $prefix . str_pad($lastCount, 4, '0', STR_PAD_LEFT);
        }

        $totalTagihan = floatval($subscription->Harga) - floatval($subscription->Potongan ?? 0);
        $startSubs    = Carbon::parse($company->EndSubs)->addDay();
        $endSubs      = (clone $startSubs)->addDays($subscription->LamaSubsription ?? 30);
        $dueDate      = Carbon::parse($company->EndSubs)->format('Y-m-d');

        DB::beginTransaction();
        try {
            DB::table('tagihanpenggunaheader')->insert([
                'NoTransaksi'        => $noTransaksi,
                'TglTransaksi'       => Carbon::now()->format('Y-m-d'),
                'TglJatuhTempo'      => $dueDate,
                'KodePaketLangganan' => $company->KodePaketLangganan,
                'Catatan'            => 'Tagihan Otomatis: ' . $subscription->NamaSubscription
                                        . ' (Periode ' . $startSubs->format('d/m/Y')
                                        . ' - ' . $endSubs->format('d/m/Y') . ')',
                'KodePelanggan'      => $company->KodePartner,
                'TotalTagihan'       => $totalTagihan,
                'TotalBayar'         => 0,
                'Status'             => 'O',
                'StartSubs'          => $startSubs->format('Y-m-d'),
                'EndSubs'            => $endSubs->format('Y-m-d'),
            ]);

            DB::table('tagihanpenggunadetail')->insert([
                'NoTransaksi'   => $noTransaksi,
                'NoUrut'        => 0,
                'Harga'         => $totalTagihan,
                'Catatan'       => 'Biaya Langganan ' . $subscription->NamaSubscription,
                'KodePelanggan' => $company->KodePartner,
            ]);

            DB::commit();
            $label = $isSuspended ? 'suspended' : 'reminder';
            $this->info("Invoice ({$label}) dibuat: {$noTransaksi} untuk {$company->NamaPartner}");
            return $noTransaksi;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Gagal buat invoice untuk {$company->NamaPartner}: " . $e->getMessage());
            Log::error('ProcessSubscriptionStatus createInvoiceIfNeeded: ' . $e->getMessage());
            return null;
        }
    }

    // -------------------------------------------------------------------------
    // Shared: send email to the company's registered user
    // -------------------------------------------------------------------------
    private function sendInvoiceEmail(object $company, object $subscription, string $noTransaksi, bool $isSuspended): void
    {
        $user = DB::table('users as a')
            ->leftJoin('userrole as b', function ($join) {
                $join->on('a.id', '=', 'b.userid')
                     ->on('a.RecordOwnerID', '=', 'b.RecordOwnerID');
            })
            ->leftJoin('roles as c', function ($join) {
                $join->on('b.roleid', '=', 'c.id')
                     ->on('b.RecordOwnerID', '=', 'c.RecordOwnerID');
            })
            ->where('c.RoleName', 'SuperAdmin')
            ->where('a.RecordOwnerID', $company->KodePartner)
            ->select('a.name', 'a.email')
            ->orderBy('a.id', 'asc')
            ->first();

        if (!$user || empty($user->email)) {
            $this->warn("Email tidak ditemukan untuk {$company->NamaPartner}. Skip kirim email.");
            return;
        }

        $totalTagihan = floatval($subscription->Harga) - floatval($subscription->Potongan ?? 0);
        $startSubs    = Carbon::parse($company->EndSubs)->addDay();
        $endSubs      = (clone $startSubs)->addDays($subscription->LamaSubsription ?? 30);

        $payload = [
            'isSuspended'      => $isSuspended,
            'companyName'      => $company->NamaPartner,
            'noTransaksi'      => $noTransaksi,
            'subscriptionName' => $subscription->NamaSubscription,
            'totalTagihan'     => $totalTagihan,
            'startSubs'        => $startSubs->format('d/m/Y'),
            'endSubs'          => $endSubs->format('d/m/Y'),
            'dueDate'          => Carbon::parse($company->EndSubs)->format('d/m/Y'),
        ];

        try {
            Mail::to($user->email)->send(new SubscriptionInvoiceMail($payload));
            $this->info("Email terkirim ke {$user->email} ({$company->NamaPartner})");
        } catch (\Exception $e) {
            $this->error("Gagal kirim email ke {$user->email}: " . $e->getMessage());
            Log::error('ProcessSubscriptionStatus sendInvoiceEmail: ' . $e->getMessage());
        }
    }
}
