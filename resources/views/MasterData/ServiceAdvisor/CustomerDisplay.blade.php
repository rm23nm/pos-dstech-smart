<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Display - Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a; /* Slate 900 */
            color: #f8fafc; /* Slate 50 */
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: #1e293b; /* Slate 800 */
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #334155;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header-logo {
            font-size: 24px;
            font-weight: bold;
            color: #38bdf8; /* Sky 400 */
        }
        .header-info {
            text-align: right;
        }
        .header-info h4 {
            margin: 0;
            font-weight: 600;
            color: #cbd5e1; /* Slate 300 */
        }
        .header-info h5 {
            margin: 5px 0 0;
            font-weight: 400;
            color: #94a3b8; /* Slate 400 */
        }
        .main-content {
            display: flex;
            flex: 1;
            overflow: hidden;
        }
        .left-pane {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
        .right-pane {
            width: 450px;
            background-color: #1e293b;
            padding: 30px;
            display: flex;
            flex-direction: column;
            border-left: 2px solid #334155;
            box-shadow: -4px 0 6px -1px rgba(0, 0, 0, 0.1);
        }
        .table {
            color: #f8fafc;
            margin-bottom: 0;
        }
        .table th {
            border-bottom-color: #475569;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        .table td {
            border-bottom-color: #334155;
            padding: 15px 10px;
            vertical-align: middle;
        }
        .item-name {
            font-size: 18px;
            font-weight: 500;
        }
        .item-qty {
            font-size: 16px;
            color: #94a3b8;
        }
        .item-price {
            font-size: 18px;
            text-align: right;
        }
        
        /* Right pane styling */
        .summary-box {
            margin-top: auto;
            background: linear-gradient(135deg, #0284c7, #2563eb);
            border-radius: 16px;
            padding: 30px;
            text-align: right;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .summary-label {
            font-size: 20px;
            color: #e0f2fe; /* Light blue */
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .summary-total {
            font-size: 48px;
            font-weight: 800;
            margin: 0;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .promo-area {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            background-color: #0f172a;
            border-radius: 12px;
            border: 1px dashed #475569;
            overflow: hidden;
            position: relative;
        }
        
        .promo-area img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .promo-text {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(15, 23, 42, 0.8);
            padding: 15px;
            border-radius: 8px;
            backdrop-filter: blur(4px);
        }
        .promo-text h3 {
            margin: 0 0 5px;
            color: #38bdf8;
            font-size: 20px;
        }
        .promo-text p {
            margin: 0;
            font-size: 14px;
            color: #cbd5e1;
        }
        
        .empty-cart {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #64748b;
        }
        .empty-cart i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        .empty-cart h3 {
            font-weight: 500;
        }

    </style>
</head>
<body>

    <div class="header">
        <div class="header-logo">
            <i class="fas fa-tools me-2"></i> {{ $company->NamaPartner ?? 'Bengkel Smart' }}
        </div>
        <div class="header-info">
            <h4 id="cd-pelanggan">Pelanggan</h4>
            <h5 id="cd-kendaraan">Pendaftaran Servis</h5>
        </div>
    </div>

    <div class="main-content">
        <div class="left-pane">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <h3 class="m-0" style="color: #f8fafc; font-weight: 600;">Rincian Estimasi Biaya</h3>
                <span class="badge bg-primary" style="font-size: 16px; padding: 8px 15px; border-radius: 20px;" id="cd-items-count">0 Item</span>
            </div>
            
            <div id="cartContainer">
                <div class="empty-cart">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Menunggu pendaftaran item servis...</h3>
                </div>
            </div>
        </div>

        <div class="right-pane">
            <div class="promo-area">
                <!-- Promo Image placeholder -->
                <div style="text-align: center; color: #475569; padding: 20px;">
                    <i class="fas fa-car-side fa-3x mb-3"></i>
                    <h4>Terima Kasih</h4>
                    <p>Telah mempercayakan kendaraan Anda pada kami</p>
                </div>
            </div>

            <div class="summary-box">
                <div class="summary-label">Total Estimasi</div>
                <h1 class="summary-total" id="cd-total">Rp 0</h1>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateDisplay() {
            const displayData = JSON.parse(localStorage.getItem('SADisplayData'));
            
            if (!displayData || (displayData.platNomor === '' && (!displayData.items || displayData.items.length === 0))) {
                $('#cartContainer').html(`
                    <div class="empty-cart">
                        <i class="fas fa-clipboard-list"></i>
                        <h3>Menunggu pendaftaran item servis...</h3>
                    </div>
                `);
                $('#cd-total').text('Rp 0');
                $('#cd-items-count').text('0 Item');
                $('#cd-pelanggan').text('Pelanggan');
                $('#cd-kendaraan').text('Pendaftaran Servis');
                return;
            }

            // Update Header info
            $('#cd-pelanggan').text(displayData.pelanggan || 'Pelanggan');
            $('#cd-kendaraan').text(displayData.platNomor || 'Pendaftaran Servis');
            
            let items = displayData.items || [];
            $('#cd-items-count').text(items.length + ' Item');

            if (items.length === 0) {
                $('#cartContainer').html(`
                    <div class="empty-cart">
                        <i class="fas fa-clipboard-list"></i>
                        <h3>Menunggu pendaftaran item servis...</h3>
                    </div>
                `);
                $('#cd-total').text('Rp 0');
            } else {
                // Update Items
                let html = `
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th width="50%">Deskripsi / Sparepart</th>
                                <th width="15%" class="text-center">Kuantitas</th>
                                <th width="35%" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                let grandTotal = 0;

                items.forEach(item => {
                    const subtotal = item.Qty * item.Harga;
                    grandTotal += subtotal;

                    html += `
                        <tr>
                            <td>
                                <div class="item-name">${item.NamaItem}</div>
                                <div class="item-qty text-muted">@ ${item.Harga.toLocaleString('id-ID')}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary" style="font-size: 16px;">${item.Qty}</span>
                            </td>
                            <td class="item-price fw-bold">Rp ${subtotal.toLocaleString('id-ID')}</td>
                        </tr>
                    `;
                });

                html += `
                        </tbody>
                    </table>
                `;

                $('#cartContainer').html(html);
                $('#cd-total').text('Rp ' + grandTotal.toLocaleString('id-ID'));
            }
        }

        // Run on load
        $(document).ready(function() {
            updateDisplay();
        });

        // Listen for storage events from the main window
        window.addEventListener('storage', function(e) {
            if (e.key === 'SADisplayData') {
                updateDisplay();
            }
        });
    </script>
</body>
</html>
