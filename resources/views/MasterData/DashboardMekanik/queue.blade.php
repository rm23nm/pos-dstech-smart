<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }
        .header {
            background-color: #1e293b;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #3b82f6;
        }
        .card-queue {
            background-color: #1e293b;
            border-left: 5px solid #3b82f6;
            margin-bottom: 15px;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .status-badge {
            font-size: 1.2rem;
            padding: 8px 15px;
        }
        .plat-nomor {
            font-size: 2rem;
            font-weight: bold;
            color: #e0f2fe;
        }
        .mekanik-name {
            font-size: 1.2rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="header position-relative">
        <h1>ANTREAN BENGKEL</h1>
        <p class="mb-0 text-muted">Daftar Kendaraan Sedang Dikerjakan & Menunggu</p>
        <button id="btnEnableSound" class="btn btn-outline-warning position-absolute" style="right: 20px; top: 20px;">
            <i class="fas fa-volume-up"></i> Aktifkan Suara
        </button>
    </div>
    <div class="container-fluid mt-4 px-4">
        <div class="row">
            <div class="col-md-4">
                <h3 class="text-warning mb-4 text-center border-bottom border-warning pb-2">MENUNGGU</h3>
                <div id="waitingList"></div>
            </div>
            <div class="col-md-4">
                <h3 class="text-info mb-4 text-center border-bottom border-info pb-2">DIKERJAKAN</h3>
                <div id="workingList"></div>
            </div>
            <div class="col-md-4">
                <h3 class="text-success mb-4 text-center border-bottom border-success pb-2">SELESAI</h3>
                <div id="doneList"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let previousStatus = {};
        let soundEnabled = false;

        $(document).on('click', '#btnEnableSound', function() {
            soundEnabled = true;
            if ('speechSynthesis' in window) {
                // Unlock speech synthesis on user interaction
                // (Removed cancel() here as it can sometimes bug out Chrome's engine on first load)
                let msg = new SpeechSynthesisUtterance("Suara antrean telah diaktifkan.");
                msg.lang = 'id-ID';
                msg.rate = 0.9;
                window.speechSynthesis.speak(msg);
                console.log("Suara diaktifkan");
            }
            $(this).removeClass('btn-outline-warning').addClass('btn-success').html('<i class="fas fa-volume-up"></i> Suara Aktif');
        });

        function speak(text) {
            console.log("Mencoba memanggil suara: ", text);
            if (!soundEnabled) {
                console.log("Suara belum diaktifkan oleh pengguna.");
                return;
            }
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                let msg = new SpeechSynthesisUtterance(text);
                msg.lang = 'id-ID';
                msg.rate = 0.9;
                window.speechSynthesis.speak(msg);
            }
        }

        function loadQueue() {
            $.ajax({
                url: "{{ route('dashboard-mekanik.getData') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", type: "queue" },
                success: function(res) {
                    let waitingHtml = '';
                    let workingHtml = '';
                    let doneHtml = '';

                    if(res.data) {
                        res.data.forEach(item => {
                            // Check for status changes to trigger voice announcement
                            if (previousStatus[item.NoPKB] !== undefined && previousStatus[item.NoPKB] != item.StatusServis) {
                                // Read the plat number letter by letter for better pronunciation
                                let platSpelled = item.PlatNomor.split('').join(' ');
                                if (item.StatusServis == 1) {
                                    speak("Panggilan untuk kendaraan plat nomor " + platSpelled + ", sedang dikerjakan.");
                                } else if (item.StatusServis == 2) {
                                    speak("Pemberitahuan, kendaraan plat nomor " + platSpelled + ", telah selesai dikerjakan. Silakan menuju kasir untuk menyelesaikan pembayaran.");
                                }
                            }
                            previousStatus[item.NoPKB] = item.StatusServis;

                            let badgeClass = item.StatusServis == 0 ? 'warning' : (item.StatusServis == 1 ? 'info' : 'success');
                            let statusText = item.StatusServis == 0 ? 'Menunggu' : (item.StatusServis == 1 ? 'Dikerjakan' : 'Selesai');
                            
                            let createdAt = new Date(item.created_at.replace(' ', 'T'));
                            let timeInStr = createdAt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                            let timeOutStr = '-';
                            let timeOutLabel = 'Estimasi';
                            
                            if (item.StatusServis == 2) {
                                let updatedAt = new Date(item.updated_at.replace(' ', 'T'));
                                timeOutStr = updatedAt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                                timeOutLabel = 'Selesai';
                            } else if (item.EstimasiWaktu > 0) {
                                let estimatedFinish = new Date(createdAt.getTime() + item.EstimasiWaktu * 60000);
                                timeOutStr = estimatedFinish.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                            }

                            let card = `
                                <div class="card-queue">
                                    <div>
                                        <div class="plat-nomor"><i class="fas ${item.JenisKendaraan === 'Motor' ? 'fa-motorcycle' : 'fa-car-side'} me-2"></i>${item.PlatNomor}</div>
                                        <div class="mekanik-name">
                                            <i class="fas fa-user text-primary"></i> ${item.NamaMekanik} <br>
                                            <small class="text-info"><i class="fas fa-clock"></i> Masuk: ${timeInStr} | ${timeOutLabel}: ${timeOutStr}</small>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge bg-${badgeClass} status-badge">
                                            ${statusText}
                                        </span>
                                    </div>
                                </div>
                            `;
                            
                            if(item.StatusServis == 0) waitingHtml += card;
                            else if(item.StatusServis == 1) workingHtml += card;
                            else if(item.StatusServis == 2) doneHtml += card;
                        });
                    }

                    $('#waitingList').html(waitingHtml || '<p class="text-muted text-center">Tidak ada antrean</p>');
                    $('#workingList').html(workingHtml || '<p class="text-muted text-center">Tidak ada pengerjaan</p>');
                    $('#doneList').html(doneHtml || '<p class="text-muted text-center">Belum ada yang selesai</p>');
                }
            });
        }

        $(document).ready(function() {
            loadQueue();
            setInterval(loadQueue, 5000); // refresh every 5 seconds
        });
    </script>
</body>
</html>
