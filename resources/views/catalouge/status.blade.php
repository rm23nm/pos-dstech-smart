<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan - {{ $order->NoTransaksi ?? 'E-Catalog' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root {
            --primary-blue: #0f4c81; /* Royal Blue */
            --strong-red: #d9383a;   /* Strong Red */
            --bg-light: #f3f5f9;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .status-card {
            background: white;
            border-radius: 30px;
            padding: 45px 35px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(15, 76, 129, 0.08);
            max-width: 500px;
            width: 100%;
            border: none;
        }

        .success-icon {
            width: 90px;
            height: 90px;
            background: #22c55e;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin: 0 auto 25px;
            box-shadow: 0 10px 25px rgba(34, 197, 94, 0.25);
        }

        .order-id {
            background: #f1f3f7;
            padding: 8px 22px;
            border-radius: 50px;
            font-weight: 700;
            color: #2b2b2b;
            font-size: 0.95rem;
            display: inline-block;
            margin-bottom: 25px;
        }

        .order-id i {
            color: var(--primary-blue);
            margin-right: 6px;
        }

        h2 { 
            font-weight: 800; 
            color: var(--primary-blue); 
            margin-bottom: 10px;
        }

        p.subtitle { 
            color: #6b7280; 
            line-height: 1.6;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        /* Stepper UI */
        .stepper-container {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 35px 0;
            padding: 0 10px;
        }

        .stepper-line {
            position: absolute;
            top: 15px;
            left: 5%;
            right: 5%;
            height: 3px;
            background: #e9ecef;
            z-index: 1;
        }

        .stepper-line-progress {
            position: absolute;
            top: 15px;
            left: 5%;
            height: 3px;
            background: var(--primary-blue);
            z-index: 2;
            transition: width 0.4s ease;
        }

        .step-item {
            position: relative;
            z-index: 3;
            text-align: center;
            width: 25%;
        }

        .step-bubble {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e9ecef;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            margin: 0 auto 8px;
            transition: all 0.3s ease;
        }

        .step-item.active .step-bubble {
            background: var(--primary-blue);
            color: white;
            box-shadow: 0 0 0 6px rgba(15, 76, 129, 0.15);
        }

        .step-item.completed .step-bubble {
            background: #22c55e;
            color: white;
        }

        .step-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #6b7280;
        }

        .step-item.active .step-label {
            color: var(--primary-blue);
            font-weight: 700;
        }

        .step-item.completed .step-label {
            color: #22c55e;
        }

        /* Detail Box */
        .detail-box {
            background: #fdf5f5;
            padding: 20px;
            border-radius: 20px;
            text-align: left;
            margin-bottom: 30px;
            border: 1px solid #fcebeb;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .detail-row:last-child {
            margin-bottom: 0;
            border-top: 1px dashed #f5c2c2;
            padding-top: 8px;
            margin-top: 8px;
        }

        .detail-label {
            color: #6b7280;
            font-weight: 600;
        }

        .detail-val {
            color: #1f2937;
            font-weight: 700;
        }

        .detail-val.total-val {
            color: var(--strong-red);
            font-size: 1.1rem;
            font-weight: 800;
        }

        /* Action Buttons */
        .btn-action-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn-catalog {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(15, 76, 129, 0.2);
            text-decoration: none;
            display: block;
            font-size: 0.95rem;
        }

        .btn-catalog:hover {
            background: #0b375d;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(15, 76, 129, 0.3);
        }

        .btn-orders {
            background: transparent;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            padding: 12px;
            border-radius: 16px;
            font-weight: 700;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
            font-size: 0.95rem;
        }

        .btn-orders:hover {
            background: rgba(15, 76, 129, 0.05);
            color: #0b375d;
            transform: translateY(-2px);
        }

        .footer-note {
            color: #9ca3af;
            font-size: 0.78rem;
            margin-top: 25px;
        }
    </style>
</head>
<body>

    @php
        $statusLabel = 'Dipesan';
        $progressPercent = 15; // default created
        $stepClass = [1 => 'active', 2 => '', 3 => '', 4 => ''];

        // Basic order status mapping
        // Status = 1 is Open, Status = -1 is Completed / Lunas
        if ($order->Status == -1) {
            $statusLabel = 'Selesai';
            $progressPercent = 100;
            $stepClass = [1 => 'completed', 2 => 'completed', 3 => 'completed', 4 => 'completed'];
        } else {
            // kitchen_order_status: 0 = new, 1 = kitchen process, 2 = kitchen ready
            if (($order->kitchen_order_status ?? 0) == 1) {
                $statusLabel = 'Diproses Kasir';
                $progressPercent = 50;
                $stepClass = [1 => 'completed', 2 => 'active', 3 => '', 4 => ''];
            } elseif (($order->kitchen_order_status ?? 0) >= 2) {
                $statusLabel = 'Siap Diambil/Dikirim';
                $progressPercent = 75;
                $stepClass = [1 => 'completed', 2 => 'completed', 3 => 'active', 4 => ''];
            }
        }
    @endphp

    <div class="status-card animate__animated animate__fadeIn">
        <div class="success-icon animate__animated animate__zoomIn">
            <i class="fas fa-check"></i>
        </div>
        
        <div class="order-id">
            <i class="fas fa-receipt"></i> INV: {{ $order->NoTransaksi ?? 'N/A' }}
        </div>
        
        <h2>Pesanan Diterima!</h2>
        <p class="subtitle">Terima kasih telah berbelanja di <strong>{{ $company[0]['NamaPartner'] ?? 'E-Catalog' }}</strong>. Pesanan Anda sedang diproses oleh kasir.</p>

        <!-- Stepper Progress Tracker -->
        <div class="stepper-container">
            <div class="stepper-line"></div>
            <div class="stepper-line-progress" style="width: {{ $progressPercent }}%"></div>

            <div class="step-item {{ $stepClass[1] }}">
                <div class="step-bubble">1</div>
                <div class="step-label">Dipesan</div>
            </div>
            <div class="step-item {{ $stepClass[2] }}">
                <div class="step-bubble">2</div>
                <div class="step-label">Diproses</div>
            </div>
            <div class="step-item {{ $stepClass[3] }}">
                <div class="step-bubble">3</div>
                <div class="step-label">Siap</div>
            </div>
            <div class="step-item {{ $stepClass[4] }}">
                <div class="step-bubble">4</div>
                <div class="step-label">Selesai</div>
            </div>
        </div>
        
        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">Status Pesanan</span>
                <span class="detail-val text-primary">{{ $statusLabel }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Metode Pembayaran</span>
                <span class="detail-val">{{ $order->MetodePembayaran ?? 'Online Payment' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Pembayaran</span>
                <span class="detail-val total-val">Rp {{ number_format($order->NetTotal ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="btn-action-group">
            <a href="{{ route('cat-catalouge', ['ID' => $RecordOwnerID]) }}" class="btn-catalog">
                <i class="fas fa-shopping-bag me-2"></i> KEMBALI KE KATALOG
            </a>
            <a href="{{ route('cat-orders', ['id' => $RecordOwnerID]) }}" class="btn-orders">
                <i class="fas fa-list me-2"></i> LIHAT PESANAN SAYA
            </a>
        </div>

        <div class="footer-note">
            Silakan tunjukkan halaman ini kepada kasir jika diperlukan.
        </div>
    </div>

</body>
</html>
