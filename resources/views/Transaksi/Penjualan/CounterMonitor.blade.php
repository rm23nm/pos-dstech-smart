<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counter Monitor - xPOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --accent-ready: #22c55e;
            --accent-recall: #ef4444;
            --border-radius: 16px;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #1e293b;
            color: #fff;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .brand { font-size: 1.8rem; font-weight: 800; }
        .clock { font-size: 1.4rem; font-weight: 600; opacity: 0.8; }

        .monitor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .order-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 20px;
            border-left: 8px solid var(--accent-ready);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            gap: 15px;
            transition: transform 0.2s;
        }
        .order-card:hover { transform: translateY(-5px); }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .queue-num {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
            color: #1e293b;
        }

        .table-info {
            background: #f1f5f9;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
        }

        .customer-info { font-weight: 600; font-size: 1.1rem; color: #475569; }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 5px;
        }

        .btn-recall {
            flex: 1;
            background: var(--accent-recall);
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-recall:hover { background: #dc2626; transform: scale(1.02); }
        .btn-recall:active { transform: scale(0.98); }

        .btn-finish {
            flex: 1;
            background: #1e293b;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-finish:hover { background: #0f172a; }

        .service-badge { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }

        #empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 100px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <header>
        <div class="brand"><i class="bi bi-display"></i> MONITOR COUNTER</div>
        <div class="clock" id="clock">00:00:00</div>
    </header>

    <div class="monitor-grid" id="monitor-grid">
        <!-- Data Loaded via AJAX -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function updateClock(){
            const n=new Date();
            document.getElementById('clock').innerText = 
                `${String(n.getHours()).padStart(2,'0')}:${String(n.getMinutes()).padStart(2,'0')}:${String(n.getSeconds()).padStart(2,'0')}`;
        }
        setInterval(updateClock,1000); updateClock();

        function fetchData() {
            $.ajax({
                url: "{{ route('customerdisplay-data') }}",
                method: "POST",
                data: { _token: "{{ csrf_token() }}" },
                success: function(data) {
                    renderGrid(data.siap);
                }
            });
        }

        function renderGrid(items) {
            const container = $('#monitor-grid');
            if (items.length === 0) {
                container.html('<div id="empty-state"><i class="bi bi-inbox" style="font-size: 4rem;"></i><h3>Tidak ada pesanan siap diambil</h3></div>');
                return;
            }

            let html = '';
            items.forEach(item => {
                const qNum = item.QueueNumber ? String(item.QueueNumber).padStart(3, '0') : item.NoTransaksi;
                const service = item.ServiceType === 'TAKE_AWAY' ? 'Bawa Pulang' : 'Makan di Tempat';
                const serviceColor = item.ServiceType === 'TAKE_AWAY' ? 'text-danger' : 'text-primary';
                
                html += `
                    <div class="order-card" id="card-${item.NoTransaksi}">
                        <div class="order-header">
                            <div class="queue-num">${qNum}</div>
                            <div class="table-info">${item.TableName || 'TA'}</div>
                        </div>
                        <div class="customer-info">
                            <div>${item.NamaPelanggan || 'Customer'}</div>
                            <div class="service-badge ${serviceColor}"><i class="bi bi-bag"></i> ${service}</div>
                        </div>
                        <div class="actions">
                            <button class="btn-recall" onclick="recallOrder('${item.NoTransaksi}')">
                                <i class="bi bi-megaphone-fill"></i> PANGGIL LAGI
                            </button>
                            <button class="btn-finish" onclick="finishOrder('${item.NoTransaksi}')">
                                <i class="bi bi-check-circle-fill"></i> SELESAI
                            </button>
                        </div>
                    </div>
                `;
            });
            container.html(html);
        }

        function recallOrder(noTrx) {
            $.ajax({
                url: "{{ route('recall-order') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    NoTransaksi: noTrx
                },
                success: function(res) {
                    if(res.success) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: "success",
                            title: "Berhasil mengirim sinyal panggil ulang"
                        });
                    }
                }
            });
        }

        function finishOrder(noTrx) {
            Swal.fire({
                title: "Pesanan Selesai?",
                text: "Order akan dihapus dari layar antrian.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#1e293b",
                confirmButtonText: "Ya, Selesai"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('infokitchen-updatestatus') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            NoTransaksi: noTrx,
                            Status: 3 // Selesai / Diambil
                        },
                        success: function(res) {
                            if(res.success) fetchData();
                        }
                    });
                }
            });
        }

        fetchData();
        setInterval(fetchData, 5000);
    </script>
</body>
</html>
