<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KonfirmasiPembayaranMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $emailPelanggan;
    public $company;
    public $fnbItems;

    public function __construct($booking, $emailPelanggan, $company = null, $fnbItems = [])
    {
        $this->booking = $booking;
        $this->emailPelanggan = $emailPelanggan;
        $this->company = $company;
        $this->fnbItems = $fnbItems;
    }

    public function build()
    {
        return $this->subject('E-Receipt Booking Online - ' . ($this->company->NamaPartner ?? 'DStech'))
                    ->view('emails.konfirmasiPembayaran')
                    ->with([
                        'booking' => $this->booking,
                        'emailPelanggan' => $this->emailPelanggan,
                        'company' => $this->company,
                        'fnbItems' => $this->fnbItems,
                    ]);
    }
}


