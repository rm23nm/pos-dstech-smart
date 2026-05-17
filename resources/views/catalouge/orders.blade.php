<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - {{ $company[0]['NamaPartner'] ?? 'E-Catalog' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0f4c81; /* Royal Blue */
            --strong-red: #d9383a;   /* Strong Red */
            --accent-gray: #f8f9fa;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f3f5f9;
            color: #333;
            min-height: 100vh;
            padding-bottom: 50px;
        }

        /* Nav */
        .top-navbar {
            background-color: var(--primary-blue);
            color: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(15, 76, 129, 0.15);
        }

        .back-btn {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            color: rgba(255, 255, 255, 0.8);
            transform: translateX(-3px);
        }

        /* Header Title */
        .section-header {
            margin: 35px 0 20px;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-blue);
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            width: 50px;
            height: 4px;
            background-color: var(--strong-red);
            position: absolute;
            bottom: -6px;
            left: 0;
            border-radius: 20px;
        }

        /* Order Cards */
        .order-card {
            background: white;
            border-radius: 24px;
            border: none;
            box-shadow: 0 8px 24px rgba(0,0,0,0.03);
            margin-bottom: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.06);
        }

        .card-header-custom {
            padding: 20px 24px;
            background-color: #fff;
            border-bottom: 1px dashed #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .invoice-number {
            font-weight: 700;
            color: #2b2b2b;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-copy {
            background: none;
            border: none;
            color: #9c9c9c;
            cursor: pointer;
            transition: color 0.2s;
        }

        .btn-copy:hover {
            color: var(--primary-blue);
        }

        .order-date {
            font-size: 0.85rem;
            color: #7d8a99;
        }

        .card-body-custom {
            padding: 24px;
        }

        /* Stepper UI */
        .stepper-container {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 25px;
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

        /* Products Row */
        .products-preview {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .thumb-img {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            object-fit: contain;
            border: 1px solid #e9ecef;
            background-color: #fff;
            padding: 3px;
        }

        .more-badge {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            background-color: #f1f3f5;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            border: 1px solid #e9ecef;
        }

        .total-payment-box {
            background-color: #fdf5f5;
            padding: 15px 20px;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .total-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .total-amount {
            color: var(--strong-red);
            font-size: 1.25rem;
            font-weight: 800;
        }

        .btn-track {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 12px 24px;
            font-weight: 700;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(15, 76, 129, 0.2);
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-track:hover {
            background-color: #0b375d;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(15, 76, 129, 0.3);
            color: white;
        }

        /* Empty State */
        .empty-orders {
            background: white;
            border-radius: 30px;
            padding: 60px 30px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.03);
            margin-top: 40px;
        }

        .empty-icon {
            font-size: 5rem;
            color: var(--primary-blue);
            opacity: 0.15;
            margin-bottom: 25px;
        }

        .btn-shop {
            background-color: var(--primary-blue);
            color: white;
            font-weight: 700;
            border-radius: 50px;
            padding: 12px 35px;
            border: none;
            margin-top: 20px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-shop:hover {
            background-color: #0b375d;
            transform: scale(1.05);
            color: white;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="top-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('cat-catalouge', ['ID' => $RecordOwnerID]) }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali Belanja
            </a>
            <div class="fw-bold fs-5">{{ $company[0]['NamaPartner'] ?? 'E-Catalog' }}</div>
            <div style="width: 100px;"></div> <!-- Spacer -->
        </div>
    </nav>

    <!-- CONTENT -->
    <main class="container">
        <div class="section-header">
            <h1 class="section-title">Pesanan Saya</h1>
        </div>

        @if(count($orders) == 0)
            <!-- EMPTY STATE -->
            <div class="empty-orders text-center animate__animated animate__fadeIn">
                <div class="empty-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="fw-bold mb-2">Belum ada pesanan</h3>
                <p class="text-muted mb-4">Yuk, cari produk impianmu dan buat transaksi pertamamu sekarang juga!</p>
                <a href="{{ route('cat-catalouge', ['ID' => $RecordOwnerID]) }}" class="btn btn-shop shadow-sm">Mulai Belanja</a>
            </div>
        @else
            <!-- ORDERS LIST -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @foreach($orders as $order)
                        @php
                            $details = $orderDetails[$order->NoTransaksi] ?? collect();
                            $statusLabel = 'Menunggu';
                            $progressPercent = 15; // default created
                            $stepClass = [1 => 'active', 2 => '', 3 => '', 4 => ''];

                            // Basic order status mapping
                            // Status = 1 is Open, Status = -1 is Completed / Lunas
                            if ($order->Status == -1) {
                                $statusLabel = 'Transaksi Selesai';
                                $progressPercent = 100;
                                $stepClass = [1 => 'completed', 2 => 'completed', 3 => 'completed', 4 => 'completed'];
                            } else {
                                // If kitchen status or order status is being processed
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

                        <div class="order-card card animate__animated animate__fadeInUp">
                            <div class="card-header-custom">
                                <div>
                                    <div class="invoice-number">
                                        {{ $order->NoTransaksi }}
                                        <button class="btn-copy" onclick="copyInvoice('{{ $order->NoTransaksi }}')" title="Salin No Transaksi">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                    <div class="order-date">
                                        <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($order->TglTransaksi)->format('d M Y') }} - {{ \Carbon\Carbon::parse($order->JamMulai)->format('H:i') }}
                                    </div>
                                </div>
                                <span class="badge rounded-pill px-3 py-2 text-white" style="background-color: {{ $order->Status == -1 ? '#22c55e' : 'var(--primary-blue)' }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <div class="card-body-custom">
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

                                <!-- Product Thumbnails -->
                                <div class="products-preview">
                                    @php $count = 0; @endphp
                                    @foreach($details as $det)
                                        @if($count < 4)
                                            <img src="{{ $det->Gambar ?: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80' }}" class="thumb-img" alt="{{ $det->NamaItem ?? 'Produk' }}" onerror="this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                                        @endif
                                        @php $count++; @endphp
                                    @endforeach
                                    
                                    @if(count($details) > 4)
                                        <div class="more-badge">+{{ count($details) - 4 }}</div>
                                    @endif
                                </div>

                                <!-- Total Payment -->
                                <div class="total-payment-box">
                                    <span class="total-label">Total Belanja</span>
                                    <span class="total-amount">Rp {{ number_format($order->NetTotal, 0, ',', '.') }}</span>
                                </div>

                                <!-- Action Buttons -->
                                <a href="{{ route('cat-status', ['id' => $RecordOwnerID, 'orderId' => $order->NoTransaksi]) }}" class="btn btn-track">
                                    <i class="fas fa-eye me-2"></i> Lacak Status Pesanan
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function copyInvoice(inv) {
            navigator.clipboard.writeText(inv);
            Swal.fire({
                title: 'Disalin!',
                text: 'Nomor transaksi berhasil disalin ke clipboard.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });
        }
    </script>
</body>
</html>
