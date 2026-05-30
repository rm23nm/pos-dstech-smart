<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrean Apotek</title>
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
            margin-bottom: 10px;
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

        .running-text-container {
            background-color: #000;
            color: #ffeb3b;
            font-weight: bold;
            padding: 5px 0;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
            white-space: nowrap;
            height: 40px;
            display: flex;
            align-items: center;
            font-size: 1.2rem;
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
            text-align: center;
        }

        .order-number {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
        }

        .customer-name {
            font-size: 1.2rem;
            color: var(--text-muted);
            font-weight: 500;
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
        <div style="display: flex; align-items: center;">
            @if(!empty($company->icon))
                <img src="{{ $company->icon }}" alt="Logo" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px; object-fit: cover;">
            @else
                <img src="https://via.placeholder.com/50" alt="Logo" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px; object-fit: cover;">
            @endif
            <div class="brand">{{ $company->NamaPartner ?? 'Apotek' }}</div>
        </div>
        <div class="clock" id="clock">--:--:--</div>
        <button onclick="toggleFullscreen()" class="btn btn-outline-secondary ms-3" title="Fullscreen" style="color: white; border-color: white;">
            ⛶
        </button>
    </header>

    <div class="running-text-container">  
        <marquee>{{ $company->RunningTextSelfServices ?? 'Selamat Datang di Apotek Kami' }}</marquee>
    </div>

    <div class="display-container">
        <!-- MASUK -->
        <div class="status-column">
            <div class="column-header header-in">
                <i class="bi bi-box-arrow-in-right"></i> ANTREAN MASUK
            </div>
            <div class="order-list" id="list-masuk">
                <!-- Data will be loaded here -->
            </div>
        </div>

        <!-- DIRAMU -->
        <div class="status-column">
            <div class="column-header header-process">
                <i class="bi bi-capsule"></i> SEDANG DIRAMU
            </div>
            <div class="order-list" id="list-diramu">
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
    <audio id="audio-bell" src="{{ asset('assets/audio/airport_bell.mp3') }}" preload="auto"></audio>

    <!-- Overlay for browser autoplay policy -->
    <div id="audio-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.95); z-index: 9999; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; cursor: pointer;" onclick="enableAudio()">
        <div style="font-size: 5rem; color: var(--accent-ready); margin-bottom: 20px;">
            <i class="bi bi-volume-up-fill"></i>
        </div>
        <h1 style="color: #fff; font-weight: 700;">KLIK UNTUK MENGAKTIFKAN SUARA & TAMPILAN FULLSCREEN</h1>
        <p style="color: var(--text-muted); font-size: 1.2rem;">Browser memerlukan interaksi pengguna untuk memulai notifikasi suara.</p>
    </div>

    <script src="https://code.responsivevoice.org/responsivevoice.js?key=jn7RFIYJ"></script>
    <script>
        function updateClock(){
            const n=new Date();
            document.getElementById('clock').innerText = 
                `${String(n.getHours()).padStart(2,'0')}:${String(n.getMinutes()).padStart(2,'0')}:${String(n.getSeconds()).padStart(2,'0')}`;
        }
        setInterval(updateClock,1000); updateClock();

        let lastSiapIds = [];
        let lastTriggerMap = {}; // Map NoTransaksi -> last call_trigger
        let isFirstLoad = true;
        let audioEnabled = false;

        function enableAudio() {
            audioEnabled = true;
            document.getElementById('audio-overlay').style.display = 'none';
            toggleFullscreen();
            
            // Test sound
            let bell = document.getElementById('audio-bell');
            if(bell) {
                bell.play().catch(e => console.log('Audio error:', e));
            }
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        function fetchData() {
            const RecordOwnerID = "<?php echo $idE ?>";
            $.ajax({
                url: "{{ route('queue-apotek-getData') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    RecordOwnerID: RecordOwnerID
                },
                success: function(data) {
                    renderColumn('list-masuk', data.antreanMasuk);
                    renderColumn('list-diramu', data.sedangDiramu);
                    renderColumn('list-siap', data.siapDiambil, true);
                    
                    checkNewReady(data.siapDiambil);
                    isFirstLoad = false;
                },
                error: function(xhr) {
                    console.error("Gagal ambil data antrian apotek:", xhr);
                }
            });
        }

        function renderColumn(elementId, items, isReady = false) {
            const container = $('#' + elementId);
            let html = '';
            
            if (!items || items.length === 0) {
                html = `<div class="text-center py-5 text-muted">Belum ada data</div>`;
            } else {
                items.forEach(item => {
                    const qNum = item.NoTransaksi.substring(item.NoTransaksi.length - 4);
                    const custName = (item.NamaPelanggan && item.NamaPelanggan.toUpperCase() !== 'CASH') ? item.NamaPelanggan : 'Customer ' + qNum;

                    html += `
                        <div class="order-card ${isReady ? 'new-ready' : ''}">
                            <div class="order-info" style="width: 100%;">
                                <div class="order-number">${qNum}</div>
                                <div class="customer-name">${custName}</div>
                            </div>
                        </div>
                    `;
                });
            }
            container.html(html);
        }

        function checkNewReady(siapItems) {
            if (!siapItems) return;
            const currentIds = siapItems.map(i => i.NoTransaksi);
            
            if (!isFirstLoad) {
                // 1. Check for completely new items
                const newItems = siapItems.filter(i => !lastSiapIds.includes(i.NoTransaksi));
                if (newItems.length > 0) {
                    if (audioEnabled) {
                        announceOrders(newItems);
                    }
                }

                // 2. Check for Recall Trigger (existing items called again)
                const recallItems = [];
                siapItems.forEach(item => {
                    const lastTrigger = lastTriggerMap[item.NoTransaksi] || 0;
                    const currentTrigger = parseInt(item.call_trigger || 0);
                    
                    if (currentTrigger > lastTrigger) {
                        recallItems.push(item);
                    }
                    // Always update current trigger value
                    lastTriggerMap[item.NoTransaksi] = currentTrigger;
                });

                if (recallItems.length > 0 && audioEnabled) {
                    announceOrders(recallItems);
                }
            } else {
                // Initial load: set base trigger values without calling
                siapItems.forEach(item => {
                    lastTriggerMap[item.NoTransaksi] = parseInt(item.call_trigger || 0);
                });
            }
            
            lastSiapIds = currentIds;
        }

        function announceOrders(items) {
            let names = [];
            items.forEach(item => {
                const qNum = item.NoTransaksi.substring(item.NoTransaksi.length - 4);
                const custName = (item.NamaPelanggan && item.NamaPelanggan.toUpperCase() !== 'CASH') ? item.NamaPelanggan : 'nomor ' + qNum;
                names.push(custName);
            });

            if (names.length > 0) {
                let text = "Panggilan untuk antrean atas nama " + names.join(", dan ") + ". Obat anda telah siap untuk diambil. Terima kasih.";
                
                let bell = document.getElementById('audio-bell');
                if(bell) {
                    bell.play().catch(e => console.log('Audio error:', e));
                }
                setTimeout(() => {
                    if (window.responsiveVoice) {
                        responsiveVoice.speak(text, "Indonesian Female", {rate: 0.85});
                    }
                }, 1500);
            }
        }

        // Initial fetch
        fetchData();
        // Refresh every 5 seconds
        setInterval(fetchData, 5000);
    </script>
</body>
</html>
