<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Receipt Booking Online</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; color: #333; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); padding: 30px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .content { padding: 30px; }
        .receipt-card { background: #f8f9fa; border-left: 4px solid #0d6efd; padding: 20px; border-radius: 8px; margin-bottom: 25px; }
        .receipt-card h3 { margin-top: 0; color: #0d6efd; }
        .info-row { margin-bottom: 10px; border-bottom: 1px dashed #ddd; padding-bottom: 5px; overflow: hidden; }
        .info-label { font-weight: bold; color: #666; float: left; }
        .info-value { color: #333; float: right; }
        .instructions { background: #fff4e5; border: 1px solid #ffc107; padding: 20px; border-radius: 8px; margin-bottom: 25px; }
        .instructions h4 { margin-top: 0; color: #856404; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #888; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>E-RECEIPT</h1>
            <p>{{ $company->NamaPartner ?? 'DStech Smart POS' }}</p>
        </div>
        <div class="content">
            <h2>Konfirmasi Booking Berhasil</h2>
            <p>Halo, <strong>{{ $emailPelanggan->NamaPelanggan ?? $emailPelanggan->Email }}</strong>,</p>
            <p>Terima kasih telah melakukan reservasi online. Berikut adalah rincian pesanan Anda:</p>
            
            <div class="receipt-card">
                <h3>Rincian Jadwal</h3>
                <div class="info-row">
                    <span class="info-label">Kode Booking</span>
                    <span class="info-value"><strong>{{ $booking['NoTransaksi'] }}</strong></span>
                    <div class="clear"></div>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($booking['TglBooking'])->format('d M Y') }}</span>
                    <div class="clear"></div>
                </div>
                <div class="info-row">
                    <span class="info-label">Waktu</span>
                    <span class="info-value">{{ $booking['JamMulai'] }} - {{ $booking['JamSelesai'] }}</span>
                    <div class="clear"></div>
                </div>
            </div>

            <div class="instructions">
                <h4>📌 Tata Cara Kedatangan:</h4>
                <ol>
                    <li>Harap datang <strong>1 jam sebelum</strong> waktu booking dimulai.</li>
                    <li>Tunjukkan <strong>Kode Booking</strong> atau E-Receipt ini kepada staf front office kami.</li>
                    <li>Lakukan konfirmasi ulang di meja resepsionis untuk aktivasi meja/lapangan Anda.</li>
                    <li>Jika terlambat lebih dari 15 menit tanpa konfirmasi, slot waktu Anda mungkin akan dilepaskan atau dikurangi durasinya.</li>
                </ol>
            </div>

            <p style="text-align: center; font-style: italic;">
                "Pastikan Anda menjaga kebersihan dan kenyamanan bersama selama di area fasilitas kami."
            </p>

            <div style="text-align: center; margin-top: 30px;">
                <p>Ada pertanyaan? Silakan hubungi kami atau datang langsung ke lokasi:</p>
                <p><strong>{{ $company->Alamat ?? '' }}</strong></p>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $company->NamaPartner }}. Powered by DStech Smart POS.<br>
            Email ini dikirimkan secara otomatis, mohon tidak membalas email ini.
        </div>
    </div>
</body>
</html>
