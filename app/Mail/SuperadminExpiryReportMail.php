<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuperadminExpiryReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reportData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Laporan Harian Tagihan Otomatis Langganan POS')
                    ->view('emails.superadmin_expiry_report');
    }
}
