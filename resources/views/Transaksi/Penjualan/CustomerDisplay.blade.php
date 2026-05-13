<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Order Display - xPOS</title>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        :root {
            --bg-color: #0f172a;
            --column-bg: rgba(30, 41, 59, 0.7);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-in: #38bdf8;
            --accent-process: #fbbf24;
            --accent-ready: #22c55e;
            --card-bg: #1e293b;
            --border-radius: 16px;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .brand {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(45deg, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .clock {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .display-container {
            display: flex;
            gap: 20px;
            flex: 1;
            overflow: hidden;
        }

        .status-column {
            flex: 1;
            background: var(--column-bg);
            border-radius: var(--border-radius);
            display: flex;
            flex-direction: column;
            padding: 15px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .column-header {
            text-align: center;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 12px;
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .header-in { background: var(--accent-in); color: #000; }
        .header-process { background: var(--accent-process); color: #000; }
        .header-ready { background: var(--accent-ready); color: #000; }

        .order-list {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding-right: 5px;
        }

        .order-list::-webkit-scrollbar {
            width: 6px;
        }
        .order-list::-webkit-scrollbar-thumb {
            background: var(--text-muted);
            border-radius: 10px;
        }

        .order-card {
            background: var(--card-bg);
            padding: 15px 20px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            animation: slideIn 0.4s ease-out forwards;
            border: 1px solid rgba(255,255,255,0.05);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .order-card.new-ready {
            animation: pulseReady 2s infinite;
            border: 2px solid var(--accent-ready);
        }

        @keyframes pulseReady {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
            70% { box-shadow: 0 0 0 20px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        .order-info {
            display: flex;
            flex-direction: column;
        }

        .order-number {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
        }

        .customer-name {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .table-badge {
            background: rgba(255,255,255,0.1);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--accent-in);
        }

        .ready-text {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--accent-ready);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .display-container { flex-direction: column; overflow-y: auto; }
            .status-column { min-height: 300px; }
            body { overflow-y: auto; height: auto; }
        }
    </style>
</head>
<body>

    <header>
        <div class="brand">xPOS ORDER STATUS</div>
        <div class="clock" id="clock">--:--:--</div>
    </header>

    <div class="display-container">
        <!-- MASUK -->
        <div class="status-column">
            <div class="column-header header-in">
                <i class="bi bi-box-arrow-in-right"></i> PESANAN MASUK
            </div>
            <div class="order-list" id="list-masuk">
                <!-- Data will be loaded here -->
            </div>
        </div>

        <!-- DIPROSES -->
        <div class="status-column">
            <div class="column-header header-process">
                <i class="bi bi-fire"></i> SEDANG DIPROSES
            </div>
            <div class="order-list" id="list-proses">
                <!-- Data will be loaded here -->
            </div>
        </div>

        <!-- SIAP DIAMBIL -->
        <div class="status-column">
            <div class="column-header header-ready">
                <i class="bi bi-bell-fill"></i> SIAP DIAMBIL
            </div>
            <div class="order-list" id="list-siap">
                <!-- Data will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Audio for notification -->
    <audio id="notif-sound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3"></audio>

    <script>
        function updateClock(){
            const n=new Date();
            document.getElementById('clock').innerText = 
                `${String(n.getHours()).padStart(2,'0')}:${String(n.getMinutes()).padStart(2,'0')}:${String(n.getSeconds()).padStart(2,'0')}`;
        }
        setInterval(updateClock,1000); updateClock();

        let lastSiapIds = [];
        let isFirstLoad = true;

        function fetchData() {
            $.ajax({
                url: "{{ route('customerdisplay-data') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    renderColumn('list-masuk', data.masuk);
                    renderColumn('list-proses', data.proses);
                    renderColumn('list-siap', data.siap, true);
                    
                    checkNewReady(data.siap);
                    isFirstLoad = false;
                }
            });
        }

        function renderColumn(elementId, items, isReady = false) {
            const container = $('#' + elementId);
            let html = '';
            
            if (items.length === 0) {
                html = `<div class="text-center py-5 text-muted">Belum ada data</div>`;
            } else {
                items.forEach(item => {
                    html += `
                        <div class="order-card ${isReady ? 'new-ready' : ''}">
                            <div class="order-info">
                                <div class="order-number">${item.NoTransaksi}</div>
                                <div class="customer-name">${item.NamaPelanggan || 'Customer'}</div>
                            </div>
                            <div class="table-badge">${item.TableName || 'Ambil Sendiri'}</div>
                        </div>
                    `;
                });
            }
            container.html(html);
        }

        function checkNewReady(siapItems) {
            const currentIds = siapItems.map(i => i.NoTransaksi);
            
            if (!isFirstLoad) {
                const newItems = siapItems.filter(i => !lastSiapIds.includes(i.NoTransaksi));
                if (newItems.length > 0) {
                    playNotif();
                    announceOrders(newItems);
                }
            }
            
            lastSiapIds = currentIds;
        }

        function playNotif() {
            const audio = document.getElementById('notif-sound');
            audio.play().catch(e => console.log("Audio play failed:", e));
        }

        function announceOrders(items) {
            if (!('speechSynthesis' in window)) return;

            items.forEach(item => {
                const text = `Pesanan nomor ${item.NoTransaksi} atas nama ${item.NamaPelanggan || 'Pelanggan'} telah siap. Silahkan diambil.`;
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = 0.9;
                window.speechSynthesis.speak(utterance);
            });
        }

        // Initial fetch
        fetchData();
        // Refresh every 5 seconds
        setInterval(fetchData, 5000);
    </script>
</body>
</html>
