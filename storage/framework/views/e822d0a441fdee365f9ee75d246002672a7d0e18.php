<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrean Pendaftaran</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            color: #333;
        }

        /* ===== OVERLAY AKTIVASI SUARA ===== */
        #startOverlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, #1e1e2d 0%, #0f3460 100%);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #fff;
            transition: opacity 0.5s ease;
        }
        #startOverlay .icon-pulse {
            font-size: 8rem;
            color: #1BC5BD;
            animation: pulse 1.5s infinite;
            margin-bottom: 30px;
        }
        #startOverlay h2 { font-size: 3.5rem; font-weight: 800; margin-bottom: 15px; }
        #startOverlay p { font-size: 1.5rem; color: #7e8299; }
        @keyframes  pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.15); opacity: 0.7; }
        }

        /* ===== HEADER ===== */
        .header-tv {
            background: #5b73e8; /* Blue similar to the screenshot */
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            z-index: 10;
        }
        .header-tv .brand { display: flex; align-items: center; }
        .header-tv .brand-logo { width: 50px; height: 50px; background: #fff; border-radius: 8px; margin-right: 15px; display: flex; align-items:center; justify-content:center; color:#5b73e8; font-size: 24px; font-weight: bold;}
        .header-tv .brand-info h1 { font-size: 1.5rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;}
        .header-tv .brand-info p { font-size: 0.9rem; opacity: 0.9;}
        
        .header-tv .clock-container { text-align: right; }
        .header-tv .date { font-size: 1rem; opacity: 0.9; margin-bottom: -5px; }
        .header-tv .time { font-size: 2.2rem; font-weight: 700; letter-spacing: 2px; }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            display: flex;
            flex: 1;
            padding: 15px;
            gap: 15px;
            height: calc(100vh - 250px);
        }

        /* LEFT PANEL (PANGGILAN UTAMA) */
        .left-panel {
            flex: 4;
            display: flex;
            flex-direction: column;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .panel-header {
            background: #8087ad;
            color: #fff;
            text-align: center;
            padding: 15px;
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .panel-body {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #8e95c1;
            color: #fff;
        }
        .current-call-number {
            font-size: 14vw; /* Huge number */
            font-weight: 800;
            line-height: 1;
            text-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .panel-footer {
            background: #8087ad;
            color: #fff;
            text-align: center;
            padding: 15px;
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* RIGHT PANEL (VIDEO) */
        .right-panel {
            flex: 6;
            background: #000;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
        }
        .right-panel iframe, .right-panel video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: none;
        }

        /* ===== BOTTOM GRID (LOKET) ===== */
        .bottom-grid {
            display: flex;
            gap: 15px;
            padding: 0 15px 15px 15px;
            height: 180px;
        }
        .loket-box {
            flex: 1;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            color: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            position: relative;
        }
        
        .loket-box.loket-umum { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .loket-box.loket-bpjs { background: linear-gradient(135deg, #2ecc71, #27ae60); }
        .loket-box.loket-asuransi { background: linear-gradient(135deg, #f39c12, #d35400); }

        /* Dynamic Colors */
        .bottom-grid .loket-box:nth-child(6n+1) { background: linear-gradient(135deg, #FF6B6B, #EE5253); } /* Red */
        .bottom-grid .loket-box:nth-child(6n+2) { background: linear-gradient(135deg, #10AC84, #01A3A4); } /* Teal/Green */
        .bottom-grid .loket-box:nth-child(6n+3) { background: linear-gradient(135deg, #54A0FF, #2E86DE); } /* Blue */
        .bottom-grid .loket-box:nth-child(6n+4) { background: linear-gradient(135deg, #FF9F43, #FECA57); } /* Orange/Yellow */
        .bottom-grid .loket-box:nth-child(6n+5) { background: linear-gradient(135deg, #9B59B6, #8E44AD); } /* Purple */
        .bottom-grid .loket-box:nth-child(6n+6) { background: linear-gradient(135deg, #34495E, #2C3E50); } /* Dark */

        /* Decorative texture */
        .loket-box::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(#ffffff 15%, transparent 16%);
            background-size: 10px 10px;
            opacity: 0.1;
            pointer-events: none;
        }

        .loket-header {
            padding: 10px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            z-index: 1;
        }
        .loket-body {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }
        .loket-number {
            font-size: 5rem;
            font-weight: 800;
            text-shadow: 0 5px 10px rgba(0,0,0,0.2);
        }

        /* ===== FOOTER MARQUEE ===== */
        .footer-tv {
            background: #5b73e8;
            color: #fff;
            padding: 10px;
            font-size: 1.2rem;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            display: flex;
            align-items: center;
        }
        .marquee {
            animation: marquee 20s linear infinite;
        }
        @keyframes  marquee {
            0%   { transform: translateX(100vw); }
            100% { transform: translateX(-100%); }
        }

        /* BLINK ANIMATION */
        @keyframes  blinkAnim {
            0%   { opacity: 1;   transform: scale(1); }
            25%  { opacity: 0.5; transform: scale(1.05); color: #FFD700; }
            50%  { opacity: 1;   transform: scale(1); }
            75%  { opacity: 0.5; transform: scale(1.05); color: #FFD700; }
            100% { opacity: 1;   transform: scale(1); }
        }
        .blinking { animation: blinkAnim 0.8s ease 3; }
    </style>
</head>
<body>

    <!-- OVERLAY: Klik untuk aktifkan suara -->
    <div id="startOverlay" onclick="activateAudio()">
        <div class="icon-pulse"><i class="fas fa-volume-up"></i></div>
        <h2>KLIK UNTUK MENGAKTIFKAN LAYAR</h2>
        <p>Sentuh / klik layar ini untuk mengaktifkan fitur suara panggilan otomatis</p>
    </div>

    <!-- HEADER -->
    <div class="header-tv">
        <div class="brand">
            <div class="brand-logo">
                <i class="fas fa-hospital"></i>
            </div>
            <div class="brand-info">
                <h1><?php echo e($company->NamaPerusahaan ?? 'KLINIK SMART'); ?></h1>
                <p><?php echo e($company->Alamat ?? 'Layanan Pendaftaran Antrean'); ?></p>
            </div>
        </div>
        <div class="clock-container">
            <div class="date" id="date">--</div>
            <div class="time" id="clock">00:00:00</div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- Kiri: Nomor Dipanggil -->
        <div class="left-panel">
            <div class="panel-header">NOMOR ANTREAN</div>
            <div class="panel-body">
                <div class="current-call-number" id="currentCall">--</div>
            </div>
            <div class="panel-footer" id="currentLoket">LOKET PENDAFTARAN</div>
        </div>

        <!-- Kanan: Pemutar Video -->
        <div class="right-panel">
            <?php
                $videoUrl = $company->VideoPromo ?? '';
                $isYoutube = strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false;
            ?>

            <?php if($isYoutube): ?>
                <?php
                    // Ekstrak ID Youtube
                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $matches);
                    $ytId = $matches[1] ?? '';
                ?>
                <?php if($ytId): ?>
                    <div id="player"></div>
                    <script>
                        var tag = document.createElement('script');
                        tag.src = "https://www.youtube.com/iframe_api";
                        var firstScriptTag = document.getElementsByTagName('script')[0];
                        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                        var player;
                        function onYouTubeIframeAPIReady() {
                            player = new YT.Player('player', {
                                height: '100%',
                                width: '100%',
                                videoId: '<?php echo e($ytId); ?>',
                                playerVars: {
                                    'autoplay': 1,
                                    'controls': 0,
                                    'rel': 0,
                                    'showinfo': 0,
                                    'mute': 1, // Harus mute agar bisa autoplay di browser modern
                                    'loop': 1,
                                    'playlist': '<?php echo e($ytId); ?>'
                                },
                                events: {
                                    'onReady': onPlayerReady
                                }
                            });
                        }
                        function onPlayerReady(event) { event.target.playVideo(); }
                    </script>
                <?php endif; ?>
            <?php elseif($videoUrl): ?>
                <video src="<?php echo e($videoUrl); ?>" autoplay loop muted playsinline></video>
            <?php else: ?>
                <!-- Placeholder jika tidak ada video -->
                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; flex-direction:column; background:#1e1e2d; color:#fff;">
                    <i class="fas fa-video-slash" style="font-size: 5rem; margin-bottom: 20px; color:#3699FF;"></i>
                    <h2>Media Promosi / Edukasi</h2>
                    <p class="text-muted">Atur URL Video di Menu Pengaturan Perusahaan</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

        <!-- BOTTOM GRID (LOKET) -->
        <div class="bottom-grid">
            <?php if(isset($lokets) && count($lokets) > 0): ?>
                <?php $__currentLoopData = $lokets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="loket-box loket-dinamis">
                    <div class="loket-header"><?php echo e(strtoupper($loket->NamaLoket)); ?></div>
                    <div class="loket-body">
                        <div class="loket-number loket-number-dinamis" id="loket-<?php echo e($loket->id); ?>">--</div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <!-- Fallback jika belum ada loket yang diatur -->
                <div class="loket-box loket-umum">
                    <div class="loket-header">LOKET 1</div>
                    <div class="loket-body">
                        <div class="loket-number">--</div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    <!-- FOOTER MARQUEE -->
    <div class="footer-tv">
        <div class="marquee">
            <?php echo e($company->RunningText ?? 'SELAMAT DATANG DI KLINIK KAMI. JAM BUKA LAYANAN KAMI ADALAH PUKUL 07:00 S/D 21:00. TERIMA KASIH ATAS KUNJUNGAN ANDA, KAMI SENANTIASA MELAYANI SEPENUH HATI.'); ?>

        </div>
    </div>

    <!-- Audio bel tersembunyi -->
    <audio id="bellSound" preload="auto"></audio>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var audioActivated = false;
        var audioCtx = null;

        // ===== AKTIVASI AUDIO CONTEXT =====
        function activateAudio() {
            try {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                audioActivated = true;
            } catch(e) { console.warn('AudioContext tidak tersedia:', e); }

            if ('speechSynthesis' in window) {
                var testMsg = new SpeechSynthesisUtterance('');
                window.speechSynthesis.speak(testMsg);
            }
            $('#startOverlay').fadeOut(400);
        }

        // ===== PUTAR BEL DENGAN WEB AUDIO API =====
        function playBell() {
            if (!audioCtx) return;
            try {
                var oscillator = audioCtx.createOscillator();
                var gainNode   = audioCtx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); 
                oscillator.frequency.exponentialRampToValueAtTime(660, audioCtx.currentTime + 0.3);
                gainNode.gain.setValueAtTime(0.6, audioCtx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.8);
                oscillator.start(audioCtx.currentTime);
                oscillator.stop(audioCtx.currentTime + 0.8);
            } catch(e) { console.warn('Bell error:', e); }
        }

        // ===== TEXT TO SPEECH =====
        function speakText(text) {
            if (!('speechSynthesis' in window)) return;
            if (window.speechSynthesis.paused) window.speechSynthesis.resume();
            window.speechSynthesis.cancel();

            setTimeout(function() {
                var msg = new SpeechSynthesisUtterance(text);
                msg.lang  = 'id-ID';
                msg.rate  = 0.85;
                msg.pitch = 1.0;
                msg.volume = 1.0;
                var voices = window.speechSynthesis.getVoices();
                var idVoice = voices.find(v => v.lang === 'id-ID' || v.lang.startsWith('id'));
                if (idVoice) msg.voice = idVoice;
                window.speechSynthesis.speak(msg);
            }, 200);
        }

        // Fix Chrome macet
        setInterval(function() {
            if (window.speechSynthesis && window.speechSynthesis.speaking) {
                window.speechSynthesis.pause();
                window.speechSynthesis.resume();
            }
        }, 14000);

        // ===== PANGGIL NOMOR =====
        function bunyikanPanggilan(nomor, tipe) {
            if (!audioActivated) return;
            playBell();
            var parts = nomor.split('-');
            var teks;
            
            var namaLoket = tipe.toUpperCase();
            var tujuan = '';
            if(namaLoket === 'BPJS' || namaLoket === 'LOKET BPJS') {
                tujuan = namaLoket === 'BPJS' ? 'Loket Be Pe Je Es' : 'Loket Be Pe Je Es';
            } else {
                if(namaLoket.indexOf('LOKET') !== -1) {
                    tujuan = tipe;
                } else {
                    tujuan = 'Loket ' + tipe;
                }
            }
            
            if (parts.length === 2) {
                teks = 'Nomor antrean... ' + parts[0] + '... ' + parseInt(parts[1]) + '... silakan menuju ' + tujuan;
            } else {
                teks = 'Nomor antrean... ' + nomor + '... silakan menuju ' + tujuan;
            }
            setTimeout(function() { speakText(teks); }, 900);
        }

        // ===== JAM DIGITAL & TANGGAL =====
        function updateClock() {
            var d = new Date();
            var h = d.getHours().toString().padStart(2, '0');
            var m = d.getMinutes().toString().padStart(2, '0');
            var s = d.getSeconds().toString().padStart(2, '0');
            document.getElementById('clock').textContent = h + ':' + m + ':' + s;
            
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('date').textContent = d.toLocaleDateString('id-ID', options);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // ===== POLLING DATA ANTREAN =====
        $(document).ready(function() {
            var lastCalled    = '--';
            var lastUpdatedAt = '';

            function fetchDisplayData() {
                $.ajax({
                    url:  "<?php echo e(route('klinik-display.data')); ?>",
                    type: 'GET',
                    success: function(res) {
                        var current   = res.current_call;
                        var loket     = res.current_loket;
                        var updatedAt = res.updated_at || '';
                        
                        // Update Bottom Grid (Dynamic Loket)
                        if(res.loket_status) {
                            for(var id in res.loket_status) {
                                $('#loket-' + id).text(res.loket_status[id]);
                            }
                        }

                        // Panggil suara jika nomor BARU atau DIULANG
                        if (current !== '--' && (current !== lastCalled || updatedAt !== lastUpdatedAt)) {
                            lastCalled    = current;
                            lastUpdatedAt = updatedAt;
                            
                            // Tampilkan nomor + animasi di Layar Utama
                            var el = $('#currentCall');
                            el.text(current).removeClass('blinking');
                            $('#currentLoket').text(loket.toUpperCase());
                            
                            void el[0].offsetWidth; // trigger reflow
                            el.addClass('blinking');
                            
                            // Suara bisa diabaikan atau tetap jalan
                            bunyikanPanggilan(current, loket);
                        } else if (current === '--') {
                            $('#currentCall').text('--').removeClass('blinking');
                            $('#currentLoket').text('LOKET PENDAFTARAN');
                        }
                    }
                });
            }

            setInterval(fetchDisplayData, 3000);
            fetchDisplayData();

            setTimeout(function() {
                if ('speechSynthesis' in window) window.speechSynthesis.getVoices();
            }, 1000);
        });
    </script>
</body>
</html>
<?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/klinik/kiosk/display.blade.php ENDPATH**/ ?>