<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Self Service POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ asset('css/style.css?v=1.0')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('api/pace/pace-theme-flat-top.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('api/select2/select2.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('css/sweetalert.css')}}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico')}}" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <!-- Midtrans Snap -->
    @if(env('MIDTRANS_IS_PRODUCTION', false))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY', '') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY', '') }}"></script>
    @endif

    <style>
        /* ========== LAYOUT ========== */
        * { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: #f4f6fb;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        /* Header POS */
        .pos-header {
            background: #1a1a2e;
            color: #eee;
            padding: 8px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            height: 68px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.4);
        }
        .pos-header .brand {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #00e5ff;
        }

        /* Clock */
        .clock-display {
            font-size: 1.8rem;
            font-weight: 700;
            color: #00e5ff;
            letter-spacing: 4px;
            text-shadow: 0 0 10px rgba(0,229,255,0.5);
        }

        /* Main wrapper */
        .pos-main {
            margin-top: 68px; /* header height */
            display: flex;
            height: calc(100vh - 68px);
            overflow: hidden;
        }

        /* ========== LEFT PANEL ========== */
        .left-panel {
            flex: 0 0 70%;
            width: 70%;
            overflow-y: auto;
            padding: 24px;
            @if(count($company) > 0 && $company[0]->TypeBackgraund == 'Color' && !empty($company[0]->Backgraund))
                background-color: {{ $company[0]->Backgraund }};
            @elseif(count($company) > 0 && $company[0]->TypeBackgraund == 'Image' && !empty($company[0]->Backgraund))
                background: linear-gradient(rgba(244, 246, 251, 0.3), rgba(244, 246, 251, 0.3)), url('{{ $company[0]->Backgraund }}') no-repeat center center;
                background-size: 100% 100%;
            @else
                background: #f4f6fb;
            @endif
        }

        /* Kelompok section - Transparent Card */
        .kelompok-section {
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .kelompok-title {
            display: inline-block;
            background: linear-gradient(135deg, #1a237e, #283593);
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            padding: 8px 24px 8px 16px;
            border-radius: 4px 20px 20px 4px;
            margin-bottom: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
        }

        /* Titik lampu grid */
        .titik-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* Each titik lampu box */
        .titik-box {
            width: 90px;
            height: 90px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            border: 3px solid transparent;
            transition: transform 0.15s, box-shadow 0.15s, border-color 0.15s;
            box-shadow: 0 3px 7px rgba(0,0,0,0.22);
            position: relative;
            user-select: none;
        }
        .titik-box:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .titik-box.active-selected {
            border-color: #fff;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.7), 0 4px 12px rgba(0,0,0,0.35);
            transform: scale(1.12);
        }

        /* Status Colors */
        .status-0  { background: linear-gradient(135deg, #2e7d32, #43a047); } /* Hijau - Kosong */
        .status-1  { background: linear-gradient(135deg, #b71c1c, #e53935); } /* Merah - Aktif */
        .status-2  { background: linear-gradient(135deg, #e65100, #fb8c00); } /* Kuning/Orange */
        .status-99 { background: linear-gradient(135deg, #e65100, #fb8c00); } /* Kuning/Orange - Hampir Habis */
        .status-n1 { background: linear-gradient(135deg, #f57f17, #fdd835); filter: brightness(0.9); } /* Kuning keemasan - Checkout */

        .table-timer {
            font-size: 0.85rem;
            margin-top: 5px;
            font-family: monospace;
            background: rgba(0,0,0,0.2);
            padding: 2px 5px;
            border-radius: 4px;
        }

        /* ========== RIGHT PANEL ========== */
        .right-panel {
            flex: 0 0 30%;
            width: 30%;
            background: #fff;
            border-left: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            box-shadow: -4px 0 16px rgba(0,0,0,0.08);
            overflow-y: auto;
        }

        /* Placeholder state */
        .right-placeholder {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #bdbdbd;
            padding: 32px;
            text-align: center;
        }
        .right-placeholder i { font-size: 4rem; margin-bottom: 16px; }
        .right-placeholder p { font-size: 1rem; }

        /* Detail content */
        .detail-content { display: none; flex-direction: column; flex: 1; }

        /* Titik name banner */
        .detail-name-banner {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: #fff;
            padding: 14px 16px;
            font-size: 1.2rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 1px;
        }
        .detail-status-badge {
            display: block;
            width: 100%;
            padding: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            text-align: center;
            color: #fff;
            border: none;
            cursor: default;
        }

        /* Image */
        .detail-img-wrap {
            width: 100%;
            height: 250px;
            overflow: hidden;
            background: #e9ecef;
            border-bottom: 1px solid #eee;
        }
        .detail-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Info list */
        .detail-info-list {
            list-style: none;
            margin: 0;
            padding: 0;
            border-bottom: 1px solid #eee;
        }
        .detail-info-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            border-bottom: 1px dotted #e0e0e0;
            font-size: 0.9rem;
        }
        .detail-info-list li .info-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #37474f;
        }
        .detail-info-list li .info-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.8rem;
        }
        .detail-info-list li .info-value {
            font-weight: 600;
            color: #263238;
            font-size: 0.85rem;
            text-align: right;
        }

        /* Action buttons area */
        .detail-actions {
            padding: 12px 12px 8px;
        }
        .detail-actions .btn-row {
            display: grid;
            grid-template-columns: 1fr; /* Only one button: Paket */
            gap: 6px;
            margin-bottom: 6px;
        }
        .detail-actions .btn-row-2 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }
        .detail-actions .btn-action {
            padding: 12px 4px;
            font-size: 0.85rem;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: opacity 0.2s, transform 0.15s;
        }
        .detail-actions .btn-action i { font-size: 1.4rem; }
        .detail-actions .btn-action:hover:not(:disabled) {
            opacity: 0.88;
            transform: translateY(-1px);
        }
        .detail-actions .btn-action:disabled {
            opacity: 0.38;
            cursor: not-allowed;
        }
        .btn-pilih-paket { background: linear-gradient(135deg, #2e7d32, #43a047); }
        .btn-tambah-makan { background: linear-gradient(135deg, #1565c0, #1976d2); }
        .btn-tambah-jam   { background: linear-gradient(135deg, #6a1b9a, #8e24aa); }
        .btn-tambah-layanan { background: linear-gradient(135deg, #00695c, #00897b); }

        /* Legend */
        .legend {
            display: flex;
            gap: 20px;
            padding: 12px 16px 8px;
            flex-wrap: wrap;
            font-size: 1rem;
            font-weight: 600;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #37474f;
        }
        .legend-dot {
            width: 22px; height: 22px;
            border-radius: 5px;
            display: inline-block;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }

        /* Modal Styles */
        .pp-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }
        .pp-overlay.open { display: flex; }
        .pp-modal {
            background: #fff;
            width: 90%;
            max-width: 600px;
            border-radius: 16px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.4);
            overflow: hidden;
            animation: modalIn 0.3s ease-out;
        }
        @keyframes modalIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .pp-header {
            background: #1a237e;
            color: #fff;
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .pp-header-label { font-size: 1rem; opacity: 0.8; text-transform: uppercase; }
        .pp-header-titik { font-size: 1.4rem; font-weight: 700; }
        .pp-close { background: none; border: none; color: #fff; font-size: 1.8rem; cursor: pointer; }

        .pp-body { padding: 24px; max-height: 75vh; overflow-y: auto; }
        .pp-row { margin-bottom: 15px; }
        .pp-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .pp-label { display: block; font-size: 0.85rem; font-weight: 700; color: #455a64; margin-bottom: 6px; }
        .pp-input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }
        .pp-input:focus { border-color: #1a237e; }

        .pp-durasi-wrap { display: flex; align-items: center; gap: 10px; }
        .pp-dur-btn {
            background: #f5f5f5;
            border: 2px solid #e0e0e0;
            width: 40px; height: 40px;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
        }
        .pp-dur-btn:hover { background: #e0e0e0; }
        .pp-dur-input { text-align: center; }

        .pp-footer {
            padding: 18px 24px;
            background: #f8f9fa;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            border-top: 1px solid #eee;
        }
        .pp-btn-cancel {
            background: #e0e0e0;
            color: #424242;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }
        .pp-btn-confirm {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }

        .detail-calc-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 0.9rem;
            color: #455a64;
        }

        /* Paid Badge */
        .paid-badge {
            position: absolute;
            top: 5px; right: 5px;
            background: #ffd600;
            color: #000;
            font-size: 0.55rem;
            font-weight: 900;
            padding: 1px 4px;
            border-radius: 3px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }

        /* Receipt Preview */
        .receipt-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.8);
            z-index: 9000;
            align-items: center;
            justify-content: center;
        }
        .receipt-overlay.open { display: flex; }
        .receipt-container {
            background: #fff;
            width: 380px;
            max-height: 90vh;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .receipt-paper { padding: 20px; flex: 1; overflow-y: auto; font-family: monospace; }
        .receipt-actions { padding: 15px; background: #f5f5f5; display: flex; gap: 10px; justify-content: center; }
        .btn-receipt-close { background: #1a237e; color:#fff; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                background: #fff;
            }
            /* Hide everything that is not the receipt */
            .pos-header, .pos-main, .receipt-actions, .btn-receipt-close, .modal-close, .swal2-container, .swal-overlay {
                display: none !important;
            }
            
            /* Show the modal overlay as a simple block */
            #modalReceiptPreview {
                display: block !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 80mm !important;
                background: white !important;
                z-index: 9999 !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .receipt-container {
                box-shadow: none !important;
                border: none !important;
                width: 80mm !important;
                margin: 0 !important;
                padding: 0 !important;
                display: block !important;
            }
            
            .receipt-paper {
                padding: 5mm !important;
                width: 80mm !important;
                overflow: visible !important;
                box-sizing: border-box !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            /* Ensure text is black for printing */
            #modalReceiptPreview * {
                color: #000 !important;
                background: transparent !important;
            }
        }
    </style>
</head>

<body>
    <!-- ===== HEADER ===== -->
    <header class="pos-header">
        <div class="brand d-flex align-items-center gap-2">
            @if(!empty($company[0]['icon']))
                <img src="{{ $company[0]['icon'] }}" alt="Logo" style="height: 40px; width: auto; border-radius: 4px; background: white; padding: 2px;">
            @else
                <i class="fas fa-bolt"></i>
            @endif
            <span>{{ $company[0]['NamaPartner'] }} - SELF SERVICE</span>
        </div>
        <div class="clock-display" id="posHeaderClock">--:--:--</div>
        <div class="d-flex align-items-center gap-3">
            <div class="refresh-config" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; padding: 4px 8px; color: #eee; font-size: 0.8rem; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-sync-alt"></i>
                <span>Auto Refresh:</span>
                <select id="autoRefreshInterval" onchange="onRefreshIntervalChange()" style="background: #1a1a2e; color: #00e5ff; border: none; outline: none; font-size: 0.8rem; font-weight: 700; cursor: pointer;">
                    <option value="0">OFF</option>
                    <option value="10000" selected>10s</option>
                    <option value="30000">30s</option>
                    <option value="60000">1m</option>
                </select>
            </div>
            <a href="javascript:void(0)" onclick="openJualFnbModal()" style="background: linear-gradient(135deg,#e65100,#ff8f00); color:#fff; padding:5px 12px; border-radius:6px; font-weight:600; text-decoration:none; font-size:0.85rem;"><i class="fas fa-utensils"></i> Jual FnB</a>
        </div>
    </header>

    <!-- ===== MAIN SPLIT LAYOUT ===== -->
    <div class="pos-main">

        <!-- ===== LEFT PANEL : Titik Lampu Grid ===== -->
        <div class="left-panel">
            <div class="legend mb-1">
                <span class="legend-item"><span class="legend-dot" style="background:#43a047;"></span> Kosong</span>
                <span class="legend-item"><span class="legend-dot" style="background:#e53935;"></span> Aktif</span>
                <span class="legend-item"><span class="legend-dot" style="background:#fb8c00;"></span> Hampir Habis</span>
            </div>

            @if (count($kelompoklampu) > 0)
                @foreach ($kelompoklampu as $tl)
                    @php
                        $itemInGroup = $titiklampu->filter(fn($item) => $item->KelompokLampu == $tl->KodeKelompok)->sortBy('DigitalInput');
                    @endphp
                    @if ($itemInGroup->count() > 0)
                        <div class="kelompok-section">
                            <div class="kelompok-title"><i class="fas fa-layer-group me-1"></i> {{ $tl->NamaKelompok }}</div>
                            <div class="titik-grid">
                                @foreach ($itemInGroup as $item)
                                    @php
                                        $statusClass = 'status-0';
                                        if ($item->Status == 1)  $statusClass = 'status-1';
                                        elseif ($item->Status == 99) $statusClass = 'status-99';
                                        elseif ($item->Status == -1) $statusClass = 'status-n1';
                                        elseif ($item->Status == 2)  $statusClass = 'status-2';

                                        $statusLabel = 'KOSONG';
                                        if ($item->Status == 1)  $statusLabel = 'AKTIF';
                                        elseif ($item->Status == 99) $statusLabel = 'HABIS';
                                        elseif ($item->Status == -1) $statusLabel = 'CHECKOUT';
                                    @endphp
                                    <div class="titik-box {{ $statusClass }}"
                                         data-id="{{ $item->id }}"
                                         data-namatitiklampu="{{ $item->NamaTitikLampu }}"
                                         data-notransaksi="{{ $item->NoTransaksi }}"
                                         data-jenispaket="{{ $item->JenisPaket }}"
                                         data-status="{{ $item->Status }}"
                                         data-namapaket="{{ $item->NamaPaket ?? '' }}"
                                         data-jammulai="{{ $item->JamMulai ? \Carbon\Carbon::parse($item->JamMulai)->format('d/m/Y H:i') : '-' }}"
                                         data-jamselesai="{{ $item->JamSelesai ? \Carbon\Carbon::parse($item->JamSelesai)->format('d/m/Y H:i') : '-' }}"
                                         data-gambar="{{ $item->Gambar ?? '' }}"
                                         data-statuslabel="{{ $statusLabel }}"
                                         data-totalPembayaran="{{ $item->TotalPembayaran ?? 0 }}"
                                         data-rawjammulai="{{ $item->JamMulai ?? '' }}"
                                         data-rawjamselesai="{{ $item->JamSelesai ?? '' }}"
                                         data-namakelompok="{{ $tl->NamaKelompok }}"
                                         onclick="selectTitikLampu(this)"
                                         title="{{ $item->NamaTitikLampu }}">
                                        {{ $item->DigitalInput }}
                                        @if(($item->TotalPembayaran ?? 0) > 0)
                                            <div class="paid-badge">PAID</div>
                                        @endif
                                        <div class="table-timer">--:--:--</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>

        <!-- ===== RIGHT PANEL : Detail ===== -->
        <div class="right-panel" id="rightPanel">
            <div class="right-placeholder" id="rightPlaceholder">
                <i class="fas fa-hand-pointer"></i>
                <p>Klik Meja<br>untuk mulai / tambah pesanan</p>
            </div>

            <div class="detail-content" id="detailContent">
                <div class="detail-name-banner" id="detailNamaTitikLampu">--</div>
                <div class="detail-status-badge" id="detailStatusBadge" style="background:#9e9e9e;">--</div>

                <div class="detail-img-wrap">
                    <img id="detailGambar" src="" alt="Meja" />
                </div>

                <ul class="detail-info-list">
                    <li>
                        <div class="info-label"><span class="info-icon bg-success"><i class="fas fa-box"></i></span> Paket</div>
                        <span class="info-value" id="detailPaket">-</span>
                    </li>
                    <li>
                        <div class="info-label"><span class="info-icon bg-primary"><i class="fas fa-play"></i></span> Jam Mulai</div>
                        <span class="info-value" id="detailJamMulai">-</span>
                    </li>
                    <li>
                        <div class="info-label"><span class="info-icon bg-warning"><i class="fas fa-stop"></i></span> Jam Selesai</div>
                        <span class="info-value" id="detailJamSelesai">-</span>
                    </li>
                </ul>

                <div class="detail-actions">
                    <div class="btn-row">
                        <button class="btn-action btn-pilih-paket" id="btnPilihPaket" onclick="onPilihPaket()">
                            <i class="fas fa-play"></i>
                            <span>Mulai Sekarang</span>
                        </button>
                    </div>
                    <div class="btn-row-2">
                        <button class="btn-action btn-tambah-makan" id="btnTambahMakan" onclick="onTambahMakan()">
                            <i class="fas fa-utensils"></i>
                            <span>+ Makan</span>
                        </button>
                        <button class="btn-action btn-tambah-jam" id="btnTambahJam" onclick="onTambahJam()">
                            <i class="fas fa-clock"></i>
                            <span>+ Durasi</span>
                        </button>
                        <button class="btn-action btn-tambah-layanan" id="btnTambahLayanan" onclick="onTambahLayanan()">
                            <i class="fas fa-concierge-bell"></i>
                            <span>+ Layanan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MODALS ===== -->
    
    <!-- MODAL PILIH PAKET -->
    <div id="modalPilihPaket" class="pp-overlay">
        <div class="pp-modal">
            <div class="pp-header">
                <div>
                    <div class="pp-header-label">Mulai Transaksi</div>
                    <div class="pp-header-titik" id="modalPaketTitikNama">--</div>
                </div>
                <button class="pp-close" onclick="closePilihPaketModal()">&times;</button>
            </div>
            <div class="pp-body">
                <form id="frmPilihPaket" autocomplete="off">
                    <input type="hidden" id="ppTglTransaksi" value="{{ date('Y-m-d') }}">
                    
                    <div class="pp-row pp-row-2">
                        <div class="pp-field">
                            <label class="pp-label">Jenis Paket</label>
                            <select class="pp-input" id="ppJenisPaket" onchange="onJenisPaketChange(this.value)">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($jenisLangganan as $jl)
                                    @php
                                        $val = is_array($jl) ? $jl['Kode'] : $jl;
                                        $lbl = is_array($jl) ? $jl['Nama'] : $jl;
                                    @endphp
                                    <option value="{{ $val }}">{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pp-field">
                            <label class="pp-label">Paket</label>
                            <select class="pp-input" id="ppPaketId">
                                <option value="">-- Pilih Paket --</option>
                            </select>
                        </div>
                    </div>

                    <div class="pp-row pp-row-2">
                        <div class="pp-field">
                            <label class="pp-label">Harga</label>
                            <input type="text" class="pp-input" id="ppHargaNormal" readonly style="background-color: #f5f5f5; cursor: not-allowed;" placeholder="Rp 0">
                        </div>
                        <div class="pp-field">
                            <label class="pp-label">Durasi</label>
                            <div class="pp-durasi-wrap">
                                <button type="button" class="pp-dur-btn" onclick="changeDurasi(-1)">-</button>
                                <input type="number" class="pp-input pp-dur-input" id="ppDurasi" value="1" min="1">
                                <button type="button" class="pp-dur-btn" onclick="changeDurasi(1)">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="pp-row pp-row-2">
                        <div class="pp-field">
                            <label class="pp-label">Jam Mulai</label>
                            <input type="text" class="pp-input" id="ppJamMulai" readonly>
                        </div>
                        <div class="pp-field">
                            <label class="pp-label">Estimasi Selesai</label>
                            <input type="text" class="pp-input" id="ppJamSelesai" readonly placeholder="-">
                        </div>
                    </div>

                    <!-- Force Payment Info -->
                    <div style="background: #f1f8e9; border: 1px solid #c5e1a5; border-radius: 8px; padding: 15px; margin-top: 15px;">
                        <div class="detail-calc-row">
                            <span>Grand Total:</span>
                            <span id="calcGrandTotal" style="font-weight:700; color:#2e7d32;">Rp 0</span>
                        </div>
                        <div class="pp-row mt-2">
                            <label class="pp-label">Metode Pembayaran (Midtrans/E-Wallet)</label>
                            <select class="pp-input" id="ppMetodePembayaran" onchange="calculateTotal()">
                                @foreach($metodepembayaran as $mp)
                                    @if($mp->TipePembayaran == 'NON TUNAI' || $mp->MetodeVerifikasi == 'AUTO')
                                        <option value="{{ $mp->id }}" 
                                            data-tipe="{{ $mp->TipePembayaran }}"
                                            data-percent="{{ $mp->BiayaAdminPercent ?? 0 }}"
                                            data-rupiah="{{ $mp->BiayaAdminRupiah ?? 0 }}">
                                            {{ $mp->NamaMetodePembayaran }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="pp-footer">
                <button class="pp-btn-cancel" onclick="closePilihPaketModal()">Batal</button>
                <button class="pp-btn-confirm" id="ppBtnConfirm" onclick="onKonfirmasiPaket()">Bayar & Mulai</button>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH MAKAN -->
    <div id="modalTambahMakanan" class="pp-overlay">
        <div class="pp-modal" style="max-width: 800px;">
            <div class="pp-header">
                <div>
                    <div class="pp-header-label">Tambah Pesanan FnB</div>
                    <div class="pp-header-titik" id="mdFnbTitikNama">--</div>
                </div>
                <button class="pp-close" onclick="closeTambahMakananModal()">&times;</button>
            </div>
            <div class="pp-body">
                <div class="pp-field mb-3">
                    <label class="pp-label">Cari Menu</label>
                    <div style="position:relative;">
                        <input type="text" class="pp-input" id="fnbSearchInput" placeholder="Ketik nama makanan..." onkeyup="searchFnbItems(this.value)">
                        <div id="fnbSearchResults" style="position:absolute; width:100%; z-index:100; background:white; border:1px solid #ccc; max-height:200px; overflow-y:auto; display:none;"></div>
                    </div>
                </div>
                <div style="border: 1px solid #eee; border-radius:8px; overflow:hidden;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead style="background:#f5f5f5;">
                            <tr>
                                <th style="padding:10px; text-align:left;">Item</th>
                                <th style="padding:10px; width:100px;">Qty</th>
                                <th style="padding:10px; text-align:right;">Subtotal</th>
                                <th style="padding:10px;"></th>
                            </tr>
                        </thead>
                        <tbody id="fnbCartItems"></tbody>
                    </table>
                </div>
                <div style="background: #e3f2fd; padding:15px; border-radius:8px; margin-top:15px;">
                    <div class="detail-calc-row">
                        <span>Grand Total FnB:</span>
                        <span id="fnbCalcGrandTotal" style="font-weight:700; color:#1565c0;">Rp 0</span>
                    </div>
                    <div class="pp-row mt-2">
                        <label class="pp-label">Metode Pembayaran</label>
                        <select class="pp-input" id="fnbMetodePembayaran" onchange="calculateFnbTotal()">
                            @foreach($metodepembayaran as $mp)
                                @if($mp->TipePembayaran == 'NON TUNAI' || $mp->MetodeVerifikasi == 'AUTO')
                                    <option value="{{ $mp->id }}" data-percent="{{ $mp->BiayaAdminPercent ?? 0 }}" data-rupiah="{{ $mp->BiayaAdminRupiah ?? 0 }}">
                                        {{ $mp->NamaMetodePembayaran }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="pp-footer">
                <button class="pp-btn-cancel" onclick="closeTambahMakananModal()">Batal</button>
                <button class="pp-btn-confirm" id="btnConfirmFnb" onclick="submitFnbOrder()">Bayar & Pesan</button>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH DURASI -->
    <div id="modalTambahDurasi" class="pp-overlay">
        <div class="pp-modal" style="max-width: 450px;">
            <div class="pp-header">
                <div>
                    <div class="pp-header-label">Tambah Durasi</div>
                    <div class="pp-header-titik" id="mdDurasiTitikNama">--</div>
                </div>
                <button class="pp-close" onclick="closeTambahDurasiModal()">&times;</button>
            </div>
            <div class="pp-body">
                <div class="pp-row">
                    <label class="pp-label">Pilih Paket Ekstensi</label>
                    <select class="pp-input" id="tdPaketId" onchange="calculateTambahDurasi()"></select>
                </div>
                <div class="pp-row">
                    <label class="pp-label">Jumlah</label>
                    <div class="pp-durasi-wrap">
                        <button type="button" class="pp-dur-btn" onclick="adjTambahDurasi(-1)">-</button>
                        <input type="number" id="tdDurasi" class="pp-input pp-dur-input" value="1" min="1" onchange="calculateTambahDurasi()">
                        <button type="button" class="pp-dur-btn" onclick="adjTambahDurasi(1)">+</button>
                    </div>
                </div>
                <div style="background: #f1f8e9; padding:15px; border-radius:8px; margin-top:15px;">
                    <div class="detail-calc-row">
                        <span>Grand Total Ekstensi:</span>
                        <span id="tdCalcGrandTotal" style="font-weight:700; color:#2e7d32;">Rp 0</span>
                    </div>
                    <div class="pp-row mt-2">
                        <label class="pp-label">Metode Pembayaran</label>
                        <select class="pp-input" id="tdMetodePembayaran" onchange="calculateTambahDurasi()">
                            @foreach($metodepembayaran as $mp)
                                @if($mp->TipePembayaran == 'NON TUNAI' || $mp->MetodeVerifikasi == 'AUTO')
                                    <option value="{{ $mp->id }}" data-percent="{{ $mp->BiayaAdminPercent ?? 0 }}" data-rupiah="{{ $mp->BiayaAdminRupiah ?? 0 }}">
                                        {{ $mp->NamaMetodePembayaran }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="pp-footer">
                <button class="pp-btn-cancel" onclick="closeTambahDurasiModal()">Batal</button>
                <button class="pp-btn-confirm" id="btnConfirmTambahDurasi" onclick="submitTambahDurasi()">Bayar & Tambah</button>
            </div>
        </div>
    </div>

    <!-- RECEIPT MODAL -->
    <div id="modalReceiptPreview" class="receipt-overlay">
        <div class="receipt-container">
            <div class="receipt-paper" id="receiptContent">
                <!-- Populated via JS -->
            </div>
            <div class="receipt-actions">
                <button class="btn-receipt-close" onclick="closeReceiptModal()">TUTUP</button>
            </div>
        </div>
    </div>

    <!-- MODAL JUAL FNB STANDALONE -->
    <div id="modalJualFnb" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9000; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:16px; width:96%; max-width:820px; max-height:90vh; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden;">
            <!-- Header -->
            <div style="background:linear-gradient(135deg,#e65100,#ff8f00); color:#fff; padding:16px 24px; display:flex; justify-content:space-between; align-items:center;">
                <h2 style="margin:0; font-size:1.2rem;"><i class="fas fa-utensils"></i> Jual FnB Langsung</h2>
                <button onclick="closeJualFnbModal()" style="background:none; border:none; color:#fff; font-size:1.5rem; cursor:pointer; line-height:1;">&times;</button>
            </div>
            <!-- Body -->
            <div style="flex:1; overflow-y:auto; padding:20px; display:flex; gap:16px;">
                <!-- Left: Item Search -->
                <div style="flex:1; min-width:0;">
                    <div style="margin-bottom:12px;">
                        <label style="font-size:0.8rem; color:#555; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Cari Item</label>
                        <div style="position:relative; margin-top:4px;">
                            <input type="text" id="jualFnbSearchInput" placeholder="Ketik nama atau kode item..." oninput="searchJualFnbItems(this.value)"
                                style="width:100%; padding:10px 12px; border:1.5px solid #e0e0e0; border-radius:8px; font-size:0.9rem; box-sizing:border-box;">
                            <div id="jualFnbSearchResults" style="display:none; position:absolute; top:100%; left:0; right:0; background:#fff; border:1px solid #e0e0e0; border-radius:0 0 8px 8px; max-height:200px; overflow-y:auto; z-index:100; box-shadow:0 4px 12px rgba(0,0,0,0.1);"></div>
                        </div>
                    </div>
                    <!-- Cart Table -->
                    <div style="border:1px solid #eee; border-radius:8px; overflow:hidden;">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead style="background:#f5f7fa;">
                                <tr>
                                    <th style="padding:10px 12px; text-align:left; font-size:0.8rem; color:#666;">Item</th>
                                    <th style="padding:10px 8px; text-align:center; font-size:0.8rem; color:#666; width:110px;">Qty</th>
                                    <th style="padding:10px 8px; text-align:right; font-size:0.8rem; color:#666;">Harga</th>
                                    <th style="padding:10px 8px; text-align:right; font-size:0.8rem; color:#666;">Subtotal</th>
                                    <th style="padding:10px 8px; width:36px;"></th>
                                </tr>
                            </thead>
                            <tbody id="jualFnbCartItems">
                                <tr><td colspan="5" style="text-align:center; padding:20px; color:#90a4ae;">Belum ada item.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Right: Payment -->
                <div style="width:260px; flex-shrink:0;">
                    <!-- Summary -->
                    <div style="background:#f8f9ff; border:1px solid #e8eaf6; border-radius:10px; padding:16px; margin-bottom:16px;">
                        <div style="font-weight:700; color:#1a237e; margin-bottom:12px; font-size:0.95rem;">Ringkasan</div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px; font-size:0.88rem; color:#555;"><span>Subtotal</span><span id="jualFnbSubtotal">Rp 0</span></div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px; font-size:0.88rem; color:#555;"><span>PPN</span><span id="jualFnbPpn">Rp 0</span></div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px; font-size:0.88rem; color:#555;"><span>Layanan</span><span id="jualFnbLayanan">Rp 0</span></div>
                        <div id="jualFnbAdminRow" style="display:none; justify-content:space-between; margin-bottom:6px; font-size:0.88rem; color:#555;"><span>Admin Fee</span><span id="jualFnbAdminFee">Rp 0</span></div>
                        <hr style="border:none; border-top:2px solid #3f51b5; margin:10px 0;">
                        <div style="display:flex; justify-content:space-between; font-size:1.1rem; font-weight:800; color:#1a237e;"><span>TOTAL</span><span id="jualFnbGrandTotal">Rp 0</span></div>
                    </div>
                    <!-- Customer Selection (Self Service usually Umum) -->
                    <div style="margin-bottom:12px; display: none;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                            <label style="font-size:0.8rem; color:#555; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Pelanggan</label>
                            <div style="font-size:0.75rem;">
                                <input type="checkbox" id="jualFnbIsNewCustomer" onchange="toggleJualFnbNewCustomer()">
                                <label for="jualFnbIsNewCustomer" style="color:#1a237e; font-weight:600; cursor:pointer;">Baru?</label>
                            </div>
                        </div>
                        
                        <div id="jualFnbExistingCustomerRow">
                            <select id="jualFnbPelanggan" style="width:100%;">
                                @foreach($pelanggan as $p)
                                    <option value="{{ $p->KodePelanggan }}">{{ $p->NamaPelanggan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="jualFnbNewCustomerRow" style="display:none; background:#fff3e0; padding:10px; border-radius:8px; border:1px solid #ffe0b2;">
                            <input type="text" id="jualFnbNewNama" placeholder="Nama Pelanggan *" style="width:100%; padding:8px; margin-bottom:6px; border:1px solid #ddd; border-radius:4px; font-size:0.85rem;">
                            <input type="text" id="jualFnbNewTlp" placeholder="No Tlp / WA *" style="width:100%; padding:8px; margin-bottom:6px; border:1px solid #ddd; border-radius:4px; font-size:0.85rem;">
                            <input type="email" id="jualFnbNewEmail" placeholder="Email (Opsional)" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; font-size:0.85rem;">
                        </div>
                    </div>
                    <!-- Payment Method -->
                    <div style="margin-bottom:12px;">
                        <label style="font-size:0.8rem; color:#555; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:4px;">Metode Bayar <span style="color:red;">*</span></label>
                        <select id="jualFnbMetode" onchange="calculateJualFnbTotal()" style="width:100%; padding:9px 10px; border:1.5px solid #e0e0e0; border-radius:8px; font-size:0.9rem;">
                            @foreach($metodepembayaran as $mp)
                                @if($mp->TipePembayaran == 'NON TUNAI' || $mp->MetodeVerifikasi == 'AUTO')
                                    <option value="{{ $mp->id }}"
                                        data-percent="{{ $mp->AdminFeePercent ?? 0 }}"
                                        data-rupiah="{{ $mp->AdminFeeRupiah ?? 0 }}"
                                        data-tipe="{{ $mp->TipePembayaran ?? '' }}"
                                        data-nama="{{ $mp->NamaMetodePembayaran }}">{{ $mp->NamaMetodePembayaran }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <!-- Nominal Bayar -->
                    <div style="margin-bottom:8px;">
                        <label style="font-size:0.8rem; color:#555; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:4px;">Nominal Bayar</label>
                        <input type="text" id="jualFnbNominal" placeholder="0" oninput="onJualFnbNominalChange()" style="width:100%; padding:9px 10px; border:1.5px solid #e0e0e0; border-radius:8px; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                    <!-- Kembalian -->
                    <div style="display:flex; justify-content:space-between; font-size:0.88rem; font-weight:600; margin-bottom:16px;">
                        <span style="color:#555;">Kembalian:</span>
                        <span id="jualFnbKembalian" style="color:#2e7d32;">Rp 0</span>
                    </div>
                    <small style="color:#888; display:block; margin-bottom:16px;">PPN: <span id="jualFnbPpnPersen">{{ $company[0]['PPN'] ?? 0 }}</span>% | Layanan: <span id="jualFnbServicePersen">{{ $company[0]['ServiceCharge'] ?? 0 }}</span>%</small>
                    <!-- Submit -->
                    <button id="jualFnbBtnSubmit" onclick="submitJualFnb()" style="width:100%; background:linear-gradient(135deg,#e65100,#ff8f00); color:#fff; border:none; border-radius:8px; padding:12px; font-size:1rem; font-weight:700; cursor:pointer;">
                        <i class="fas fa-check-circle"></i> Bayar & Pesan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('devexpress/jquery.min.js') }}"></script>
    <script src="{{ asset('css/sweetalert.min.js') }}"></script>
    
    <script>
    var selectedTitik = null;
    var dataPaketAll = @json($paket);
    var dataPelangganAll = @json($pelanggan);
    var confCompany = @json($company[0] ?? null);

    function updateClock() {
        var now = new Date();
        var h = String(now.getHours()).padStart(2, '0');
        var m = String(now.getMinutes()).padStart(2, '0');
        var s = String(now.getSeconds()).padStart(2, '0');
        $('#posHeaderClock').text(h + ':' + m + ':' + s);
    }
    setInterval(updateClock, 1000);
    updateClock();

    var refreshTimer = null;
    function onRefreshIntervalChange() {
        var interval = parseInt($('#autoRefreshInterval').val());
        if(refreshTimer) clearInterval(refreshTimer);
        if(interval > 0) {
            refreshTimer = setInterval(refreshTableStatuses, interval);
        }
    }

    function refreshTableStatuses() {
        fetch('{{ route("billing-get-table-statuses") }}')
            .then(res => res.json())
            .then(res => { if(res.success) updateUI(res.data); });
    }
    // Start default
    onRefreshIntervalChange();

    function updateUI(data) {
        data.forEach(item => {
            var el = document.querySelector('.titik-box[data-id="' + item.id + '"]');
            if(el) {
                el.dataset.notransaksi = item.NoTransaksi || '';
                el.dataset.status = item.Status || 0;
                el.dataset.rawjammulai = item.JamMulai || '';
                el.dataset.rawjamselesai = item.JamSelesai || '';
                el.dataset.jammulai = item.JamMulaiParsed || '-';
                el.dataset.jamselesai = item.JamSelesaiParsed || '-';
                el.dataset.namapaket = item.NamaPaket || '';
                el.dataset.totalPembayaran = item.TotalPembayaran || 0;

                el.className = 'titik-box';
                if(selectedTitik && selectedTitik.id == item.id) el.classList.add('active-selected');
                
                var s = parseInt(item.Status);
                if(s === 0) el.classList.add('status-0');
                else if(s === 1) el.classList.add('status-1');
                else if(s === 99) el.classList.add('status-99');
                else if(s === -1) el.classList.add('status-n1');

                var badge = el.querySelector('.paid-badge');
                if(parseFloat(item.TotalPembayaran || 0) > 0) {
                    if(!badge) $(el).append('<div class="paid-badge">PAID</div>');
                } else { if(badge) badge.remove(); }
            }
        });
        if(selectedTitik) {
            var updated = data.find(x => x.id == selectedTitik.id);
            if(updated) {
                selectedTitik.status = parseInt(updated.Status);
                selectedTitik.notransaksi = updated.NoTransaksi;
                renderRightPanel(selectedTitik);
            }
        }
    }

    // Timers
    setInterval(() => {
        $('.titik-box').each(function() {
            var status = parseInt(this.dataset.status);
            if(status === 0) return;
            var start = this.dataset.rawjammulai;
            var end = this.dataset.rawjamselesai;
            var now = new Date();
            var diff = 0;
            if(end && end !== 'null' && end !== '') {
                diff = new Date(end.replace(' ', 'T')) - now;
                $(this).find('.table-timer').text(diff < 0 ? "TIME UP" : formatDur(diff));
            } else if(start) {
                diff = now - new Date(start.replace(' ', 'T'));
                $(this).find('.table-timer').text(formatDur(diff));
            }
        });
    }, 1000);

    function formatDur(ms) {
        var s = Math.floor(Math.abs(ms) / 1000);
        var h = Math.floor(s / 3600);
        var m = Math.floor((s % 3600) / 60);
        var sec = s % 60;
        return [h,m,sec].map(v => String(v).padStart(2,'0')).join(':');
    }

    function selectTitikLampu(el) {
        $('.titik-box').removeClass('active-selected');
        $(el).addClass('active-selected');
        selectedTitik = {
            id: el.dataset.id,
            namatitiklampu: el.dataset.namatitiklampu,
            status: parseInt(el.dataset.status),
            notransaksi: el.dataset.notransaksi,
            namapaket: el.dataset.namapaket,
            jammulai: el.dataset.jammulai,
            jamselesai: el.dataset.jamselesai,
            rawjammulai: el.dataset.rawjammulai,
            rawjamselesai: el.dataset.rawjamselesai,
            namakelompok: el.dataset.namakelompok,
            gambar: el.dataset.gambar,
            statuslabel: el.dataset.statuslabel,
            jenispaket: el.dataset.jenispaket
        };
        renderRightPanel(selectedTitik);
    }

    function renderRightPanel(d) {
        $('#rightPlaceholder').hide();
        $('#detailContent').css('display', 'flex');
        $('#detailNamaTitikLampu').text(d.namatitiklampu);
        $('#detailStatusBadge').text(d.statuslabel).css('background', d.status == 0 ? '#43a047' : '#e53935');
        $('#detailGambar').attr('src', d.gambar || 'https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty.jpg');
        $('#detailPaket').text(d.namapaket || '-');
        $('#detailJamMulai').text(d.jammulai);
        $('#detailJamSelesai').text(d.jamselesai);

        $('#btnPilihPaket').prop('disabled', d.status !== 0);
        $('#btnTambahMakan, #btnTambahJam, #btnTambahLayanan').prop('disabled', d.status === 0 || !d.notransaksi);
    }

    // Modal Pilih Paket Logic
    function onPilihPaket() {
        $('#modalPaketTitikNama').text(selectedTitik.namatitiklampu);
        var now = new Date();
        $('#ppJamMulai').val(String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0'));
        
        // Populate options first
        onJenisPaketChange('JAM');
        $('#ppJenisPaket').val('JAM');

        // Auto select first valid option
        var $sel = $('#ppPaketId');
        if($sel.find('option[value!=""]').length > 0) {
            $sel.val($sel.find('option[value!=""]').first().val()).trigger('change');
        }

        $('#modalPilihPaket').addClass('open');
    }

    function onJenisPaketChange(val) {
        var $p = $('#ppPaketId').html('<option value="">-- Pilih Paket --</option>');
        var cat = selectedTitik.namakelompok ? selectedTitik.namakelompok.toUpperCase() : "";
        
        dataPaketAll.forEach(pk => {
            if(pk.JenisPaket == val) {
                // Filter berdasarkan kategori meja
                if(cat === "" || pk.NamaPaket.toUpperCase().includes(cat)) {
                    $p.append(`<option value="${pk.id}" data-harga="${pk.HargaNormal}" data-durasi="${pk.DurasiPaket}">${pk.NamaPaket}</option>`);
                }
            }
        });
        // Listener defined globally to avoid duplication
    }

    function changeDurasi(d) {
        var $i = $('#ppDurasi');
        var v = parseInt($i.val()) + d;
        if(v < 1) v = 1;
        $i.val(v);
        calculateTotal();
    }

    function calculateTotal() {
        var $opt = $('#ppPaketId option:selected');
        var harga = parseFloat($opt.data('harga') || 0);
        var dur = parseInt($('#ppDurasi').val()) || 1;
        var sub = harga * dur;
        
        var ppn = confCompany ? (confCompany.PPN || 0) : 0;
        var total = sub + (sub * ppn / 100);
        
        // Admin fee
        var $mp = $('#ppMetodePembayaran option:selected');
        var pAdmin = parseFloat($mp.data('percent') || 0);
        var rAdmin = parseFloat($mp.data('rupiah') || 0);
        var admin = (total * pAdmin / 100) + rAdmin;
        
        var gt = Math.round(total + admin);
        $('#calcGrandTotal').text(formatRp(gt));

        // Jam Selesai
        var start = $('#ppJamMulai').val().split(':');
        var d = new Date(); d.setHours(start[0], start[1]);
        var jp = $('#ppJenisPaket').val();
        if(jp == 'JAM') d.setMinutes(d.getMinutes() + (dur * 60));
        else if(jp == 'MENIT') d.setMinutes(d.getMinutes() + dur);
        $('#ppJamSelesai').val(String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0'));
    }

    function onKonfirmasiPaket() {
        var payload = {
            tableid: selectedTitik.id,
            TglTransaksi: $('#ppTglTransaksi').val(),
            JenisPaket: $('#ppJenisPaket').val(),
            paketid: $('#ppPaketId').val(),
            DurasiPaket: $('#ppDurasi').val(),
            JamMulai: $('#ppJamMulai').val(),
            JamSelesai: $('#ppJamSelesai').val(),
            OpsiBayar: 'LANGSUNG',
            MetodePembayaran: $('#ppMetodePembayaran').val(),
            NominalBayar: parseFormattedRp($('#calcGrandTotal').text()),
            KodePelanggan: '', // Self service default to Umum
            KodeSales: ''
        };

        if(!payload.paketid) { swal("Error", "Pilih paket dahulu", "error"); return; }

        var $btn = $('#ppBtnConfirm');
        $btn.prop('disabled', true).text('Memproses...');

        fetch('{{ route("billing-store-paket") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(res => {
            if(res.success && res.snap_token) {
                window.snap.pay(res.snap_token, {
                    onSuccess: function(result) {
                        fetch('{{ route("billing-midtrans-success") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            body: JSON.stringify({ NoTransaksi: res.NoTransaksi, payment_type: 'POS' })
                        }).then(() => { closePilihPaketModal(); showReceiptPreview(res.NoTransaksi); });
                    },
                    onClose: function() { $btn.prop('disabled', false).text('Bayar & Mulai'); }
                });
            } else { swal("Gagal", res.message, "error"); $btn.prop('disabled', false).text('Bayar & Mulai'); }
        });
    }

    // Fnb Logic
    var fnbCart = [];
    function searchFnbItems(q) {
        if(q.length < 2) { $('#fnbSearchResults').hide(); return; }
        $.ajax({
            url: "{{ route('itemmaster-ViewJson') }}",
            method: 'POST',
            data: { Scan: q, Active: 'Y', TipeItemIN: '1,2,3,5' },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                var h = '';
                res.data.forEach(it => {
                    h += `<div onclick='addFnb(${JSON.stringify(it)})' style='padding:8px; border-bottom:1px solid #eee; cursor:pointer;'>${it.NamaItem} - ${formatRp(it.HargaJual)}</div>`;
                });
                $('#fnbSearchResults').html(h).show();
            }
        });
    }
    function addFnb(it) {
        var ex = fnbCart.find(c => c.KodeItem == it.KodeItem);
        if(ex) ex.Qty++; else fnbCart.push({ KodeItem: it.KodeItem, NamaItem: it.NamaItem, Harga: it.HargaJual, Qty: 1 });
        $('#fnbSearchInput').val(''); $('#fnbSearchResults').hide();
        renderFnbCart();
    }
    function renderFnbCart() {
        var h = ''; var total = 0;
        fnbCart.forEach((c, i) => {
            var sub = c.Qty * c.Harga; total += sub;
            h += `<tr><td style='padding:8px;'>${c.NamaItem}</td><td style='padding:8px;'>${c.Qty}</td><td style='padding:8px; text-align:right;'>${formatRp(sub)}</td><td style='padding:8px;'><button onclick='fnbCart.splice(${i},1);renderFnbCart();'>&times;</button></td></tr>`;
        });
        $('#fnbCartItems').html(h);
        calculateFnbTotal();
    }
    function calculateFnbTotal() {
        var sub = fnbCart.reduce((s, c) => s + (c.Qty * c.Harga), 0);
        var ppn = confCompany ? (confCompany.PPN || 0) : 0;
        var svc = confCompany ? (confCompany.ServiceCharge || 0) : 0;
        var tax = sub * (ppn + svc) / 100;
        var $mp = $('#fnbMetodePembayaran option:selected');
        var adm = (sub + tax) * (parseFloat($mp.data('percent') || 0) / 100) + parseFloat($mp.data('rupiah') || 0);
        $('#fnbCalcGrandTotal').text(formatRp(Math.round(sub + tax + adm)));
    }
    function submitFnbOrder() {
        if(!fnbCart.length) return;
        var gt = parseFormattedRp($('#fnbCalcGrandTotal').text());
        var payload = { NoTransaksi: selectedTitik.notransaksi, items: fnbCart, OpsiBayar: 'LANGSUNG', MetodePembayaran: $('#fnbMetodePembayaran').val(), NominalBayar: gt };
        fetch("{{ route('billing-store-fnb') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            body: JSON.stringify(payload)
        }).then(res => res.json()).then(res => {
            if(res.snap_token) {
                window.snap.pay(res.snap_token, {
                    onSuccess: function() {
                        fetch('{{ route("billing-midtrans-success") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            body: JSON.stringify({ NoTransaksi: res.NoTransaksi, payment_type: 'ADD_FNB', NominalBayar: gt })
                        }).then(() => { closeTambahMakananModal(); showReceiptPreview(res.NoTransaksi); });
                    }
                });
            }
        });
    }

    // Durasi Logic
    function onTambahJam() {
        $('#mdDurasiTitikNama').text(selectedTitik.namatitiklampu);
        var $s = $('#tdPaketId').html('');
        var cat = selectedTitik.namakelompok ? selectedTitik.namakelompok.toUpperCase() : "";

        dataPaketAll.forEach(p => {
            if(p.JenisPaket == selectedTitik.jenispaket) {
                if(cat === "" || p.NamaPaket.toUpperCase().includes(cat)) {
                    $s.append(`<option value="${p.id}" data-harga="${p.HargaNormal}">${p.NamaPaket}</option>`);
                }
            }
        });
        calculateTambahDurasi();
        $('#modalTambahDurasi').addClass('open');
    }
    function adjTambahDurasi(v) { var $i = $('#tdDurasi'); var val = parseInt($i.val()) + v; if(val < 1) val = 1; $i.val(val); calculateTambahDurasi(); }
    function calculateTambahDurasi() {
        var $o = $('#tdPaketId option:selected');
        var sub = parseFloat($o.data('harga') || 0) * (parseInt($('#tdDurasi').val()) || 1);
        var tax = sub * ((confCompany.PPN || 0) + (confCompany.ServiceCharge || 0)) / 100;
        var $mp = $('#tdMetodePembayaran option:selected');
        var adm = (sub + tax) * (parseFloat($mp.data('percent') || 0) / 100) + parseFloat($mp.data('rupiah') || 0);
        $('#tdCalcGrandTotal').text(formatRp(Math.round(sub + tax + adm)));
    }
    function submitTambahDurasi() {
        var gt = parseFormattedRp($('#tdCalcGrandTotal').text());
        fetch('{{ route("billing-store-tambah-durasi") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            body: JSON.stringify({ NoTransaksi: selectedTitik.notransaksi, PaketId: $('#tdPaketId').val(), Durasi: $('#tdDurasi').val(), OpsiBayar: 'LANGSUNG', MetodePembayaran: $('#tdMetodePembayaran').val(), NominalBayar: gt })
        }).then(res => res.json()).then(res => {
            if(res.snap_token) {
                window.snap.pay(res.snap_token, {
                    onSuccess: function() {
                        fetch('{{ route("billing-midtrans-success") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            body: JSON.stringify({ NoTransaksi: res.NoTransaksi, payment_type: 'ADD_DURATION', NominalBayar: gt })
                        }).then(() => { closeTambahDurasiModal(); showReceiptPreview(res.NoTransaksi); });
                    }
                });
            }
        });
    }

    // Layanan Logic
    function onTambahLayanan() {
        if(!selectedTitik.rawjamselesai) { swal("Error", "Meja realtime tidak bisa tambah layanan berurutan", "warning"); return; }
        var dt = new Date(selectedTitik.rawjamselesai.replace(' ', 'T'));
        dt.setMinutes(dt.getMinutes() + 1);
        onPilihPaket();
        $('#ppJamMulai').val(String(dt.getHours()).padStart(2,'0') + ':' + String(dt.getMinutes()).padStart(2,'0'));
    }

    function showReceiptPreview(no) {
        $.ajax({
            url: '{{ route("billing-get-faktur-detail") }}',
            method: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content'), NoTransaksi: no },
            success: function(res) {
                var h = res.header;
                var html = `<div style='text-align:center;'><h3>${res.company.NamaPartner}</h3><p>${res.company.Alamat}</p><hr></div>`;
                html += `<p>No: ${h.NoTransaksi}<br>Meja: ${h.NamaTitikLampu}<br>Total: ${formatRp(h.TotalPembelian)}</p><hr><p style='text-align:center;'>TERIMA KASIH</p>`;
                $('#receiptContent').html(html);
                $('#modalReceiptPreview').addClass('open');
                
                // Otomatis cetak
                setTimeout(() => {
                    window.print();
                }, 500);
            }
        });
    }

    function formatRp(v) { return 'Rp ' + parseFloat(v).toLocaleString('id-ID'); }
    function parseFormattedRp(s) { return parseFloat(s.replace(/[^0-9]/g, '')) || 0; }
    
    $(document).ready(function() {
        $('#ppPaketId').on('change', calculateTotal);
    });

    function closePilihPaketModal() { $('#modalPilihPaket').removeClass('open'); }
    function closeTambahMakananModal() { $('#modalTambahMakanan').removeClass('open'); }
    function closeTambahDurasiModal() { $('#modalTambahDurasi').removeClass('open'); }
    function closeReceiptModal() { $('#modalReceiptPreview').removeClass('open'); location.reload(); }

    // ===== JUAL FnB STANDALONE =====
    let jualFnbCart = [];

    function openJualFnbModal() {
        jualFnbCart = [];
        updateJualFnbCartTable();
        $('#jualFnbSearchInput').val('');
        $('#jualFnbSearchResults').hide();
        calculateJualFnbTotal();
        document.getElementById('modalJualFnb').style.display = 'flex';
    }

    function closeJualFnbModal() {
        document.getElementById('modalJualFnb').style.display = 'none';
    }

    function toggleJualFnbNewCustomer() {
        const isNew = $('#jualFnbIsNewCustomer').is(':checked');
        if (isNew) {
            $('#jualFnbExistingCustomerRow').hide();
            $('#jualFnbNewCustomerRow').show();
        } else {
            $('#jualFnbExistingCustomerRow').show();
            $('#jualFnbNewCustomerRow').hide();
        }
    }

    function searchJualFnbItems(query) {
        let $results = $('#jualFnbSearchResults');
        if (query.length < 2) { $results.hide(); return; }
        $.ajax({
            url: "{{ route('itemmaster-ViewJson') }}",
            method: 'POST',
            data: { Scan: query, Active: 'Y', TipeItemIN: '1,2,3,5' },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                if (res.data && res.data.length > 0) {
                    let html = res.data.map(item => `
                        <div onclick='addJualFnbToCart(${JSON.stringify(item).replace(/"/g, "&quot;")})'
                            style="padding:10px 12px; cursor:pointer; border-bottom:1px solid #f0f0f0; display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <div style="font-weight:600; font-size:0.88rem;">${item.NamaItem}</div>
                                <div style="font-size:0.75rem; color:#999;">${item.KodeItem}</div>
                            </div>
                            <div style="font-weight:700; color:#2e7d32; font-size:0.88rem;">${formatRp(item.HargaJual)}</div>
                        </div>`).join('');
                    $results.html(html).show();
                } else {
                    $results.html('<div style="padding:10px; color:#999; font-size:0.88rem;">Tidak ditemukan.</div>').show();
                }
            }
        });
    }

    function addJualFnbToCart(item) {
        let existing = jualFnbCart.find(c => c.KodeItem === item.KodeItem);
        if (existing) { existing.Qty += 1; }
        else { jualFnbCart.push({ KodeItem: item.KodeItem, NamaItem: item.NamaItem, Harga: item.HargaJual, Satuan: item.Satuan || 'PCS', Qty: 1 }); }
        $('#jualFnbSearchInput').val('');
        $('#jualFnbSearchResults').hide();
        updateJualFnbCartTable();
    }

    function changeJualFnbQty(index, delta) {
        jualFnbCart[index].Qty = Math.max(1, jualFnbCart[index].Qty + delta);
        updateJualFnbCartTable();
    }

    function setJualFnbQty(index, val) {
        let v = parseInt(val);
        if (v < 1 || isNaN(v)) v = 1;
        jualFnbCart[index].Qty = v;
        updateJualFnbCartTable();
    }

    function removeJualFnbItem(index) {
        jualFnbCart.splice(index, 1);
        updateJualFnbCartTable();
    }

    function updateJualFnbCartTable() {
        let $body = $('#jualFnbCartItems');
        if (jualFnbCart.length === 0) {
            $body.html('<tr><td colspan="5" style="text-align:center; padding:20px; color:#90a4ae;">Belum ada item.</td></tr>');
        } else {
            let html = jualFnbCart.map((item, i) => {
                let sub = item.Qty * item.Harga;
                return `<tr>
                    <td style="padding:10px 12px; font-size:0.88rem;">${item.NamaItem}</td>
                    <td style="padding:8px;">
                        <div style="display:flex; align-items:center; gap:4px; justify-content:center;">
                            <button type="button" class="pp-dur-btn" style="padding:2px 7px;" onclick="changeJualFnbQty(${i}, -1)">−</button>
                            <input type="number" class="pp-input" style="width:46px; text-align:center; padding:4px;" value="${item.Qty}" onchange="setJualFnbQty(${i}, this.value)">
                            <button type="button" class="pp-dur-btn" style="padding:2px 7px;" onclick="changeJualFnbQty(${i}, 1)">+</button>
                        </div>
                    </td>
                    <td style="padding:8px; text-align:right; font-size:0.88rem;">${formatRp(item.Harga)}</td>
                    <td style="padding:8px; text-align:right; font-size:0.88rem; font-weight:600;">${formatRp(sub)}</td>
                    <td style="padding:8px; text-align:center;"><button type="button" onclick="removeJualFnbItem(${i})" style="background:none; border:none; color:#e53935; cursor:pointer;"><i class="fas fa-trash"></i></button></td>
                </tr>`;
            }).join('');
            $body.html(html);
        }
        calculateJualFnbTotal();
    }

    function calculateJualFnbTotal(isFromInput = false) {
        let subtotal = jualFnbCart.reduce((s, i) => s + i.Qty * i.Harga, 0);
        let ppnPersen = parseFloat($('#jualFnbPpnPersen').text()) || 0;
        let servicePersen = parseFloat($('#jualFnbServicePersen').text()) || 0;

        let ppnRp = subtotal * (ppnPersen / 100);
        let serviceRp = subtotal * (servicePersen / 100);

        let $opt = $('#jualFnbMetode option:selected');
        let adminPercent = parseFloat($opt.data('percent')) || 0;
        let adminRupiah = parseFloat($opt.data('rupiah')) || 0;
        let tipe = $opt.data('tipe') || '';

        let subtotalWithTax = subtotal + ppnRp + serviceRp;
        let adminFee = adminPercent > 0 ? subtotalWithTax * (adminPercent / 100) : (adminRupiah > 0 ? adminRupiah : 0);
        let grandTotal = Math.round(subtotalWithTax + adminFee);

        $('#jualFnbSubtotal').text(formatRp(Math.round(subtotal)));
        $('#jualFnbPpn').text(formatRp(Math.round(ppnRp)));
        $('#jualFnbLayanan').text(formatRp(Math.round(serviceRp)));
        $('#jualFnbGrandTotal').text(formatRp(grandTotal));

        if (adminFee > 0) {
            $('#jualFnbAdminFee').text(formatRp(Math.round(adminFee)));
            $('#jualFnbAdminRow').css('display', 'flex');
        } else {
            $('#jualFnbAdminRow').hide();
        }

        const nominalInp = document.getElementById('jualFnbNominal');
        if (tipe === 'NON TUNAI' || tipe === 'NONTUNAI') {
            nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
            nominalInp.readOnly = true;
            nominalInp.style.backgroundColor = '#f3f6f9';
        } else {
            nominalInp.readOnly = false;
            nominalInp.style.backgroundColor = '';
            if (!isFromInput) nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
        }

        let nominal = parseFormattedRp(nominalInp.value || '0');
        let kembalian = nominal - grandTotal;
        if (kembalian < 0) {
            $('#jualFnbKembalian').text('Kurang: ' + formatRp(Math.abs(kembalian))).css('color', '#c62828');
        } else {
            $('#jualFnbKembalian').text(formatRp(kembalian)).css('color', '#2e7d32');
        }
    }

    function onJualFnbNominalChange() {
        calculateJualFnbTotal(true);
    }

    function submitJualFnb() {
        if (jualFnbCart.length === 0) {
            swal("Perhatian", "Tidak ada item di keranjang.", "warning");
            return;
        }

        let grandTotalText = $('#jualFnbGrandTotal').text();
        let grandTotal = parseFormattedRp(grandTotalText);
        let nominal = parseFormattedRp($('#jualFnbNominal').val() || '0');

        if (nominal < grandTotal) {
            swal("Perhatian", "Nominal bayar kurang dari Grand Total.", "warning");
            return;
        }

        swal({
            title: "Konfirmasi",
            text: "Simpan pesanan FnB sebesar " + formatRp(grandTotal) + "?",
            type: "question",
            showCancelButton: true,
            confirmButtonColor: "#e65100",
            confirmButtonText: "Ya, Pesan",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (!result.value) return;

            const $btn = $('#jualFnbBtnSubmit');
            const oldHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

            fetch('{{ route("billing-jual-fnb-standalone") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    items: jualFnbCart,
                    MetodePembayaranId: $('#jualFnbMetode').val(),
                    NominalBayar: nominal,
                    isNewCustomer: $('#jualFnbIsNewCustomer').is(':checked'),
                    KodePelanggan: $('#jualFnbPelanggan').val(),
                    NamaPelanggan: $('#jualFnbNewNama').val(),
                    NoTlp1: $('#jualFnbNewTlp').val(),
                    Email: $('#jualFnbNewEmail').val()
                })
            })
            .then(r => r.json())
            .then(r => {
                if (r.success) {
                    if (r.snap_token) {
                        $btn.html('<i class="fas fa-spinner fa-spin"></i> Menunggu Pembayaran...');
                        window.snap.pay(r.snap_token, {
                            onSuccess: function (result) {
                                fetch('{{ route("billing-midtrans-success") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    body: JSON.stringify({ 
                                        NoTransaksi: r.invoiceNo,
                                        payment_type: 'JUAL_FNB'
                                    })
                                })
                                .then(res => res.json())
                                .then(res => {
                                    if (res.success) {
                                        swal({ title: "Berhasil!", text: "Pesanan berhasil disimpan.\nNo Faktur: " + res.invoiceNo, type: "success", timer: 2000, showConfirmButton: true })
                                            .then(() => {
                                                closeJualFnbModal();
                                                showReceiptPreview(res.invoiceNo);
                                            });
                                    } else {
                                        swal("Gagal", res.message || "Gagal finalisasi data.", "error");
                                    }
                                });
                            },
                            onPending: function (result) {
                                swal("Info", "Selesaikan pembayaran Anda.", "info").then(() => closeJualFnbModal());
                            },
                            onError: function (result) {
                                $btn.prop('disabled', false).html(oldHtml);
                                swal("Gagal", "Pembayaran gagal.", "error");
                            },
                            onClose: function () {
                                $btn.prop('disabled', false).html(oldHtml);
                                swal("Batal", "Pembayaran dibatalkan.", "warning");
                            }
                        });
                    } else {
                        $btn.prop('disabled', false).html(oldHtml);
                        swal({ title: "Berhasil!", text: "Pesanan berhasil disimpan.", type: "success", timer: 2000, showConfirmButton: true })
                            .then(() => {
                                closeJualFnbModal();
                                showReceiptPreview(r.invoiceNo);
                            });
                    }
                } else {
                    $btn.prop('disabled', false).html(oldHtml);
                    swal("Gagal", r.message || "Terjadi kesalahan.", "error");
                }
            })
            .catch(err => {
                $btn.prop('disabled', false).html(oldHtml);
                swal("Error", "Gagal menghubungi server.", "error");
            });
        });
    }

    // Modal close backdrop
    document.getElementById('modalJualFnb').addEventListener('click', function(e) {
        if (e.target === this) closeJualFnbModal();
    });
    </script>
</body>
</html>