<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\SmartProService;
use Illuminate\Support\Facades\Mail;
use App\Mail\BlastMail; // Reusing BlastMail or create a specific Mailable if needed

class CleanupDormantClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:cleanup-dormant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup client data and send warning if they have been inactive/expired for exactly 1 year (365 days).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('[CleanupDormantClients] Command started.');

        // 1. WARNING: H-10 (Exactly 355 days after EndSubs)
        $dateWarning = Carbon::now()->subDays(355)->format('Y-m-d');
        $this->processWarnings($dateWarning);

        // 2. DELETE: H-0 (Exactly 365 days after EndSubs, or more)
        $dateDelete = Carbon::now()->subDays(365)->format('Y-m-d');
        $this->processDeletions($dateDelete);

        Log::info('[CleanupDormantClients] Command finished.');
        $this->info('Cleanup Dormant Clients finished successfully.');
    }

    private function processWarnings($dateWarning)
    {
        $companies = DB::table('company')
            ->whereDate('EndSubs', '=', $dateWarning)
            ->where('isActive', '!=', 1)
            ->get();

        foreach ($companies as $company) {
            $RecordOwnerID = $company->KodePartner;
            
            // Get SuperAdmin email
            $user = DB::table('users')
                ->join('userrole', function ($j) {
                    $j->on('userrole.userid', '=', 'users.id')
                      ->on('userrole.RecordOwnerID', '=', 'users.RecordOwnerID');
                })
                ->join('roles', function ($j) {
                    $j->on('roles.id', '=', 'userrole.roleid')
                      ->on('roles.RecordOwnerID', '=', 'userrole.RecordOwnerID');
                })
                ->where('roles.RoleName', 'SuperAdmin')
                ->where('userrole.RecordOwnerID', $RecordOwnerID)
                ->select('users.email', 'users.name')
                ->first();

            $warningMessage = "Halo *" . ($company->NamaPartner ?? 'Pelanggan') . "*,\n\n"
                            . "Akun DSMS POS Anda telah berstatus tidak aktif (expired) selama hampir 1 tahun.\n"
                            . "Sesuai kebijakan layanan kami, dalam *10 hari ke depan* seluruh data master, transaksi, dan pengaturan "
                            . "toko Anda akan *dihapus secara permanen* dari sistem kami guna menghemat ruang penyimpanan.\n\n"
                            . "Jika Anda ingin tetap menggunakan layanan dan menyelamatkan data Anda, segera lakukan perpanjangan langganan di aplikasi atau hubungi Customer Service kami.\n\n"
                            . "Salam,\n*Tim DSMS POS*";

            // Send WA
            if (!empty($company->NoHP)) {
                try {
                    $smartpro = new SmartProService();
                    $smartpro->sendDirectWAMessage($company->NoHP, $warningMessage);
                    Log::info("[CleanupDormantClients] Warning WA sent to $RecordOwnerID.");
                } catch (\Exception $e) {
                    Log::error("[CleanupDormantClients] Failed to send WA warning to $RecordOwnerID: " . $e->getMessage());
                }
            }

            // Send Email
            if ($user && !empty($user->email)) {
                try {
                    $subject = "Peringatan Penghapusan Akun DSMS POS (H-10)";
                    // We remove Markdown formatting for email body
                    $emailMsg = str_replace('*', '', $warningMessage);
                    Mail::to($user->email)->send(new BlastMail($subject, nl2br($emailMsg)));
                    Log::info("[CleanupDormantClients] Warning Email sent to $RecordOwnerID.");
                } catch (\Exception $e) {
                    Log::error("[CleanupDormantClients] Failed to send Email warning to $RecordOwnerID: " . $e->getMessage());
                }
            }
        }
    }

    private function processDeletions($dateDelete)
    {
        // Find clients expired 365 days ago or more
        $companies = DB::table('company')
            ->whereDate('EndSubs', '<=', $dateDelete)
            ->where('isActive', '!=', 1)
            ->get();

        if ($companies->isEmpty()) {
            return;
        }

        // Get all tables dynamically that contain 'RecordOwnerID'
        $databaseName = config('database.connections.mysql.database');
        
        $columns = DB::select("
            SELECT TABLE_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE COLUMN_NAME = 'RecordOwnerID' 
            AND TABLE_SCHEMA = ?
        ", [$databaseName]);

        $tablesWithRecordOwnerID = array_column(json_decode(json_encode($columns), true), 'TABLE_NAME');

        foreach ($companies as $company) {
            $RecordOwnerID = $company->KodePartner;
            
            DB::beginTransaction();
            try {
                // Delete from all tables having RecordOwnerID
                foreach ($tablesWithRecordOwnerID as $tableName) {
                    // Avoid deleting from 'company' right away, handle it last
                    if (strtolower($tableName) !== 'company') {
                        DB::table($tableName)->where('RecordOwnerID', $RecordOwnerID)->delete();
                    }
                }
                
                // Delete from company
                DB::table('company')->where('KodePartner', $RecordOwnerID)->delete();
                
                DB::commit();
                Log::info("[CleanupDormantClients] Hard deleted client data for: $RecordOwnerID.");

                // Send final notification
                $finalMessage = "Halo *" . ($company->NamaPartner ?? 'Pelanggan') . "*,\n\n"
                              . "Data toko Anda telah dihapus secara permanen dari sistem DSMS POS karena tidak ada perpanjangan masa aktif selama 1 tahun terakhir.\n\n"
                              . "Terima kasih atas partisipasi Anda sebelumnya.\n\n"
                              . "Salam,\n*Tim DSMS POS*";

                if (!empty($company->NoHP)) {
                    try {
                        $smartpro = new SmartProService();
                        $smartpro->sendDirectWAMessage($company->NoHP, $finalMessage);
                    } catch (\Exception $e) {
                        Log::error("[CleanupDormantClients] Failed to send final WA to $RecordOwnerID.");
                    }
                }
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("[CleanupDormantClients] Failed to delete data for $RecordOwnerID: " . $e->getMessage());
            }
        }
    }
}
