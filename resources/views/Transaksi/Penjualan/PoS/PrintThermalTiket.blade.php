<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Tiket - {{ $header->NoTransaksi }}</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
        }
        .ticket {
            width: 58mm; /* atau sesuaikan dengan 80mm */
            margin: 0 auto;
            padding: 10px;
            text-align: center;
            page-break-after: always;
            box-sizing: border-box;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .company-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-bottom: 5px;
        }
        .company-contact {
            font-size: 11px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            margin: 10px 0;
            padding: 5px 0;
        }
        .ticket-name {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .barcode-container {
            margin: 10px 0;
        }
        .barcode {
            width: 100%;
            height: auto;
        }
        .info {
            font-size: 10px;
            text-align: left;
            margin-top: 10px;
        }
        .footer {
            font-size: 10px;
            margin-top: 15px;
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        @media print {
            body {
                width: 100%;
            }
            @page {
                margin: 0;
            }
        }
    </style>
</head>
<body>

    @php $ticketIndex = 0; @endphp
    @foreach($details as $detail)
        @for($i = 0; $i < $detail->Qty; $i++)
            @if(isset($tickets[$ticketIndex]))
            <div class="ticket">
                @if(!empty($company->icon))
                    <img src="{{ str_starts_with($company->icon, 'http') ? $company->icon : asset('storage/' . $company->icon) }}" alt="Logo" class="company-logo">
                @endif
                <div class="company-name">{{ $company->NamaPartner ?? 'Perusahaan' }}</div>
                <div class="company-contact">
                    {{ $company->AlamatTagihan ?? '' }}<br>
                    Telp: {{ $company->NoHP ?? $company->NoTlp ?? '-' }}
                </div>
                
                <div class="title">TIKET MASUK</div>
                
                <div class="ticket-name">{{ $detail->NamaItem }}</div>

                <div class="barcode-container">
                    <!-- SVG placeholder untuk dirender oleh JsBarcode -->
                    <svg class="barcode-svg" jsbarcode-value="{{ $tickets[$ticketIndex]->BarcodeTiket }}" jsbarcode-displayvalue="true" jsbarcode-height="50" jsbarcode-margin="0"></svg>
                </div>

                <div class="info">
                    No. Trx : {{ $header->NoTransaksi }}<br>
                    Tgl     : {{ date('d-m-Y H:i', strtotime($header->TglTransaksi)) }}<br>
                    Harga   : Rp {{ number_format($detail->Harga, 0, ',', '.') }}
                </div>

                <div class="footer">
                    Terima kasih atas kunjungan Anda.<br>
                    Tiket hanya berlaku 1 kali scan.
                </div>
            </div>
            @php $ticketIndex++; @endphp
            @endif
        @endfor
    @endforeach

    <script>
        // Inisialisasi semua elemen yang memiliki class barcode-svg
        JsBarcode(".barcode-svg").init();

        // Otomatis trigger dialog print saat halaman dimuat
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); // Beri jeda 0.5 detik agar barcode selesai di-render
        }
    </script>
</body>
</html>
