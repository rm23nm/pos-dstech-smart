<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan - {{ $order->NoTransaksi ?? 'FnB Store' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .status-card {
            background: white;
            border-radius: 30px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            max-width: 400px;
            width: 100%;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #2ed573;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 25px;
            box-shadow: 0 10px 20px rgba(46, 213, 115, 0.2);
        }

        .order-id {
            background: #f1f2f6;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 700;
            color: #2f3542;
            font-size: 0.9rem;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-home {
            background: #2f3542;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 15px;
            font-weight: 700;
            margin-top: 20px;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-home:hover {
            background: #000;
            color: white;
        }

        h2 { font-weight: 800; color: #2f3542; }
        p { color: #747d8c; line-height: 1.6; }
    </style>
</head>
<body>

    <div class="status-card">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        
        <div class="order-id">INV: {{ $order->NoTransaksi ?? 'N/A' }}</div>
        
        <h2>Pesanan Diterima!</h2>
        <p>Terima kasih <strong>{{ session('customer_name') }}</strong>. Pesanan Anda sedang kami proses dan segera diantar.</p>
        
        <div class="mt-4 p-3 bg-light rounded-4 text-start">
            <div class="d-flex justify-content-between mb-1">
                <span class="small text-muted">Status</span>
                <span class="badge bg-success">PROSES DAPUR</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="small text-muted">Total Pembayaran</span>
                <span class="fw-bold">Rp {{ number_format($order->NetTotal ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <a href="{{ $id ? route('fnb-store.menu', ['id' => $id]) : route('fnb-store.menu.custom') }}" class="btn btn-home">KEMBALI KE MENU</a>
        <p class="mt-4 small">Silakan tunjukkan halaman ini jika diperlukan.</p>
    </div>

</body>
</html>
