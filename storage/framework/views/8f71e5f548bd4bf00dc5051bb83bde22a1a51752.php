<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrean Poli Klinik</title>
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
        #startOverlay h2 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 15px;
        }
        #startOverlay p {
            font-size: 1.5rem;
            color: #7e8299;
        }
        @keyframes  pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.15); opacity: 0.7; }
        }

        /* ===== HEADER ===== */
        .header-tv {
            background: #ffffff;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #00a896;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 10;
        }
        .header-tv h1 { font-size: 2.5rem; font-weight: 800; color: #028090; display: flex; align-items: center; }
        .header-tv h1 i { font-size: 3rem; color: #00a896; margin-right: 15px; }
        .header-tv .time { font-size: 2.5rem; font-weight: 700; color: #028090; letter-spacing: 2px; text-align: right; line-height: 1.2; }
        .header-tv .date { font-size: 1.2rem; font-weight: 600; color: #777; }

        /* ===== MAIN CONTENT ===== */
        .main-content { display: flex; flex: 1; overflow: hidden; padding: 20px; gap: 20px; background: #e0e5ec; }

        /* LEFT PANEL (40%) */
        .left-panel {
            flex: 4;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* CURRENT CALL BOX */
        .current-call-box {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        .doctor-info-header {
            background: #00a896;
            padding: 20px;
            display: flex;
            align-items: center;
            color: #fff;
        }
        .doctor-photo {
            width: 100px;
            height: 100px;
            background: #fff;
            border-radius: 10px;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #00a896;
        }
        .doctor-details h2 { font-size: 2.5rem; font-weight: 800; margin: 0; line-height: 1.2; text-transform: uppercase; }
        .doctor-details h3 { font-size: 1.8rem; font-weight: 600; margin: 0; color: #e0fbfc; }

        .call-number-section {
            background: #028090;
            padding: 20px;
            text-align: center;
            color: #fff;
        }
        .call-label-row {
            display: flex;
            justify-content: space-around;
            font-size: 1.5rem;
            font-weight: 600;
            color: #e0fbfc;
            margin-bottom: 5px;
        }
        .call-value-row {
            display: flex;
            justify-content: space-around;
            align-items: baseline;
        }
        .call-number-big {
            font-size: 8rem;
            font-weight: 800;
            color: #fca311;
            line-height: 1;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .room-number-big {
            font-size: 5rem;
            font-weight: 700;
            color: #fff;
        }
        .status-bar {
            background: #00a896;
            color: #fff;
            text-align: center;
            padding: 10px;
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* WAITING LIST BOX */
        .waiting-list-box {
            background: #fff;
            border-radius: 15px;
            flex: 1;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .waiting-header {
            background: #02c39a;
            color: #fff;
            padding: 15px;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
        }
        .waiting-list-container {
            padding: 15px;
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .waiting-item {
            background: #e0fbfc;
            padding: 15px 25px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #028090;
            border-left: 8px solid #00a896;
        }
        .waiting-item-status {
            background: #fca311;
            color: #fff;
            font-size: 1.2rem;
            padding: 5px 15px;
            border-radius: 20px;
        }
        .empty-waiting {
            text-align: center;
            color: #999;
            font-size: 1.5rem;
            padding: 30px;
            font-weight: 600;
        }

        /* RIGHT PANEL (60%) - VIDEO */
        .right-panel {
            flex: 6;
            background: #000;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            position: relative;
        }
        .video-container {
            width: 100%;
            height: 100%;
        }
        .video-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* BLINK ANIMATION */
        @keyframes  blinkAnim {
            0%   { opacity: 1; }
            25%  { opacity: 0; }
            50%  { opacity: 1; }
            75%  { opacity: 0; }
            100% { opacity: 1; }
        }
        .blinking {
            animation: blinkAnim 1s ease 3;
        }
    </style>
</head>
<body>

    <!-- OVERLAY: Klik untuk aktifkan suara -->
    <div id="startOverlay" onclick="activateAudio()">
        <div class="icon-pulse"><i class="fas fa-volume-up"></i></div>
        <h2>KLIK UNTUK MENGAKTIFKAN LAYAR TV</h2>
        <p>Sentuh / klik layar ini untuk mengaktifkan fitur suara panggilan poli</p>
    </div>

    <!-- HEADER -->
    <div class="header-tv">
        <h1><i class="fas fa-plus-square"></i> KLINIK INDONESIA - <?php echo e(strtoupper($poli->NamaPoli)); ?></h1>
        <div class="time-container">
            <div class="time" id="clock">00:00:00</div>
            <div class="date" id="dateText">Senin, 01 Januari 2026</div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- LEFT: INFO PANGGILAN & DAFTAR TUNGGU -->
        <div class="left-panel">
            
            <!-- Kotak Info Panggilan -->
            <div class="current-call-box">
                <div class="doctor-info-header">
                    <div class="doctor-photo">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="doctor-details">
                        <h2 id="currentPoli">POLI <?php echo e(strtoupper($poli->NamaPoli)); ?></h2>
                        <?php if(!empty($poli->Shift)): ?>
                            <div class="text-warning font-weight-bold" style="font-size: 1.2rem; margin-bottom: 5px;"><i class="fas fa-clock"></i> <?php echo e($poli->Shift); ?></div>
                        <?php endif; ?>
                        <h3 id="currentDoctor"><?php echo e($defaultDoctor ?? 'Menunggu Panggilan'); ?></h3>
                    </div>
                </div>
                
                <div class="call-number-section">
                    <div class="call-label-row">
                        <span>NO. ANTREAN</span>
                        <span>RUANG</span>
                    </div>
                    <div class="call-value-row">
                        <span class="call-number-big" id="currentCall">---</span>
                        <span class="room-number-big">POLI</span>
                    </div>
                </div>
                
                <div class="status-bar" id="statusBar">
                    STATUS: MENUNGGU PASIEN
                </div>
            </div>

            <!-- Kotak Daftar Tunggu -->
            <div class="waiting-list-box">
                <div class="waiting-header">
                    <span>Sisa Antrean: <span id="sisaAntrean">0</span></span>
                    <span>Status</span>
                </div>
                <div class="waiting-list-container" id="waitingList">
                    <div class="empty-waiting">Memuat data...</div>
                </div>
            </div>

        </div>

        <!-- RIGHT: VIDEO PLAYER -->
        <div class="right-panel">
            <div class="video-container">
                <?php
                    $playlist = [];
                    if (!empty($poli->VideoURL)) {
                        $playlist[] = $poli->VideoURL;
                    } elseif (!empty($globalVideos) && count($globalVideos) > 0) {
                        $playlist = $globalVideos;
                    }
                ?>

                <?php if(count($playlist) > 0): ?>
                    <div id="mediaPlayerContainer" style="width: 100%; height: 100%; border-radius: 15px; overflow: hidden; background: #000;"></div>
                    <script>
                        var mediaPlaylist = <?php echo json_encode($playlist, 15, 512) ?>;
                        var currentMediaIdx = 0;
                        
                        function playNextMedia() {
                            if (mediaPlaylist.length === 0) return;
                            var mediaUrl = mediaPlaylist[currentMediaIdx];
                            var container = document.getElementById('mediaPlayerContainer');
                            container.innerHTML = '';

                            if (mediaUrl.includes('youtube.com') || mediaUrl.includes('youtu.be')) {
                                var embed = mediaUrl;
                                if (mediaUrl.includes('watch?v=')) embed = mediaUrl.replace('watch?v=', 'embed/');
                                else if (mediaUrl.includes('youtu.be/')) embed = mediaUrl.replace('youtu.be/', 'youtube.com/embed/');
                                
                                embed += (embed.includes('?') ? '&' : '?') + 'autoplay=1&mute=1';
                                if (mediaPlaylist.length === 1) embed += '&loop=1';

                                container.innerHTML = '<iframe style="width:100%; height:100%; border:none;" src="' + embed + '" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                                
                                if (mediaPlaylist.length > 1) {
                                    setTimeout(function() {
                                        currentMediaIdx = (currentMediaIdx + 1) % mediaPlaylist.length;
                                        playNextMedia();
                                    }, 180000); // 3 mins fallback for youtube
                                }
                            } else if (mediaUrl.toLowerCase().endsWith('.mp4')) {
                                var videoEl = document.createElement('video');
                                videoEl.style.width = '100%';
                                videoEl.style.height = '100%';
                                videoEl.style.objectFit = 'cover';
                                videoEl.autoplay = true;
                                videoEl.muted = true;
                                if (mediaPlaylist.length === 1) videoEl.loop = true;
                                videoEl.src = mediaUrl;
                                
                                videoEl.onended = function() {
                                    if (mediaPlaylist.length > 1) {
                                        currentMediaIdx = (currentMediaIdx + 1) % mediaPlaylist.length;
                                        playNextMedia();
                                    }
                                };
                                container.appendChild(videoEl);
                            } else {
                                var imgEl = document.createElement('img');
                                imgEl.style.width = '100%';
                                imgEl.style.height = '100%';
                                imgEl.style.objectFit = 'cover';
                                imgEl.src = mediaUrl;
                                container.appendChild(imgEl);
                                
                                if (mediaPlaylist.length > 1) {
                                    setTimeout(function() {
                                        currentMediaIdx = (currentMediaIdx + 1) % mediaPlaylist.length;
                                        playNextMedia();
                                    }, 15000); // 15s for image
                                }
                            }
                        }
                        
                        document.addEventListener("DOMContentLoaded", function() {
                            playNextMedia();
                        });
                    </script>
                <?php else: ?>
                    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; color:#aaa;">
                        <i class="fas fa-play-circle mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
                        <h4>Area Video Promosi / Edukasi</h4>
                        <p class="text-sm">Atur Video di Master Poli atau Company Setting</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var audioActivated = false;
        var audioCtx = null;

        function activateAudio() {
            try {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                audioActivated = true;
            } catch(e) {
                console.warn('AudioContext tidak tersedia:', e);
            }

            if ('speechSynthesis' in window) {
                var testMsg = new SpeechSynthesisUtterance('');
                window.speechSynthesis.speak(testMsg);
            }

            $('#startOverlay').fadeOut(400);
        }

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
            } catch(e) {}
        }

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
                var idVoice = voices.find(function(v) {
                    return v.lang === 'id-ID' || v.lang.startsWith('id');
                });
                if (idVoice) msg.voice = idVoice;

                window.speechSynthesis.speak(msg);
            }, 200);
        }

        setInterval(function() {
            if (window.speechSynthesis && window.speechSynthesis.speaking) {
                window.speechSynthesis.pause();
                window.speechSynthesis.resume();
            }
        }, 14000);

        function bunyikanPanggilan(nomor, pasien, poli) {
            if (!audioActivated) return;

            playBell();
            
            // Format nomor antrean, baca huruf dan angkanya
            var nomorPanggil = nomor.replace('-', '... ');
            var teks = 'Nomor antrean... ' + nomorPanggil + '... atas nama... ' + pasien + '... silakan masuk ke... ' + poli;

            setTimeout(function() { speakText(teks); }, 900);
        }

        function updateClock() {
            var d = new Date();
            var h = d.getHours().toString().padStart(2, '0');
            var m = d.getMinutes().toString().padStart(2, '0');
            var s = d.getSeconds().toString().padStart(2, '0');
            document.getElementById('clock').textContent = h + ':' + m + ':' + s;
            
            var hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            document.getElementById('dateText').textContent = hari[d.getDay()] + ', ' + d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
        }
        setInterval(updateClock, 1000);
        updateClock();

        $(document).ready(function() {
            var lastCalled    = '--';
            var lastUpdatedAt = '';

            function fetchDisplayData() {
                $.ajax({
                    url:  "<?php echo e(route('klinik-display-poli.data', ['poli_id' => $poli->id])); ?>",
                    type: 'GET',
                    success: function(res) {
                        var current   = res.current_call ? res.current_call.NoAntrean : '---';
                        var currentPatient = res.current_call ? res.current_call.NamaPasien : '--';
                        var currentPoli = res.current_call ? res.current_call.NamaPoli : 'POLI --';
                        var currentDoctor = res.current_call && res.current_call.NamaDokter ? res.current_call.NamaDokter : 'dr. --';
                        var updatedAt = res.current_call ? res.current_call.updated_at : '';

                        if (current !== '---' && (current !== lastCalled || updatedAt !== lastUpdatedAt)) {
                            lastCalled    = current;
                            lastUpdatedAt = updatedAt;

                            var el = $('#currentCall');
                            el.text(current).removeClass('blinking');
                            $('#currentPoli').text(currentPoli.toUpperCase());
                            $('#currentDoctor').text(currentDoctor);
                            $('#statusBar').text('STATUS: MEMANGGIL PASIEN');
                            
                            void el[0].offsetWidth;
                            el.addClass('blinking');

                            bunyikanPanggilan(current, currentPatient, currentPoli);

                            setTimeout(function(){
                                $('#statusBar').text('STATUS: SERVING');
                            }, 5000);

                        } else if (current === '---') {
                            $('#currentCall').text('---').removeClass('blinking');
                            $('#currentPoli').text('POLI <?php echo e(strtoupper($poli->NamaPoli)); ?>');
                            $('#currentDoctor').text('<?php echo e($defaultDoctor ?? "Menunggu Panggilan"); ?>');
                            $('#statusBar').text('STATUS: MENUNGGU PASIEN');
                        }

                        // Update daftar tunggu
                        var html = '';
                        var count = 0;
                        if (res.waiting_list && res.waiting_list.length > 0) {
                            count = res.waiting_list.length;
                            res.waiting_list.forEach(function(item) {
                                html += '<div class="waiting-item">';
                                html += '<span>' + item.NoAntrean + '</span>';
                                html += '<span class="waiting-item-status">WAITING</span>';
                                html += '</div>';
                            });
                        } else {
                            html = '<div class="empty-waiting">Tidak ada antrean menunggu</div>';
                        }
                        $('#waitingList').html(html);
                        $('#sisaAntrean').text(count);
                    },
                    error: function() {}
                });
            }

            setInterval(fetchDisplayData, 3000);
            fetchDisplayData();

            setTimeout(function() {
                if ('speechSynthesis' in window) {
                    window.speechSynthesis.getVoices();
                }
            }, 1000);
        });
    </script>
</body>
</html>
<?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/klinik/display/poli.blade.php ENDPATH**/ ?>