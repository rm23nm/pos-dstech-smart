<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Antrian Bengkel - {{ $company->NamaPerusahaan ?? 'Bengkel' }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      padding: 0;
      height: 100vh;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    .header {
      background-color: #1a237e;
      color: white;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      z-index: 10;
    }
    .header img {
      max-height: 50px;
    }
    .header h1 {
      margin: 0;
      font-size: 1.8rem;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .clock {
      font-size: 1.5rem;
      font-weight: 600;
    }
    .kanban-container {
      display: flex;
      flex: 1;
      padding: 20px;
      gap: 20px;
      overflow: hidden;
    }
    .kanban-col {
      flex: 1;
      background: white;
      border-radius: 12px;
      display: flex;
      flex-direction: column;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      overflow: hidden;
    }
    .kanban-header {
      padding: 15px 20px;
      text-align: center;
      font-weight: 700;
      font-size: 1.4rem;
      color: white;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .bg-menunggu { background-color: #ff9800; }
    .bg-dikerjakan { background-color: #2196f3; }
    .bg-selesai { background-color: #4caf50; }
    
    .kanban-body {
      padding: 15px;
      overflow-y: auto;
      flex: 1;
      background: #f8f9fa;
    }
    .kanban-card {
      background: white;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      border-left: 5px solid #ddd;
      transition: all 0.3s;
      position: relative;
    }
    .kanban-card:hover {
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transform: translateY(-2px);
    }
    .card-menunggu { border-left-color: #ff9800; }
    .card-dikerjakan { border-left-color: #2196f3; }
    .card-selesai { border-left-color: #4caf50; }
    
    .plat-nomor {
      font-size: 1.8rem;
      font-weight: 800;
      color: #333;
      margin-bottom: 5px;
      text-align: center;
      letter-spacing: 2px;
    }
    .detail-row {
      display: flex;
      justify-content: space-between;
      font-size: 0.9rem;
      color: #666;
      margin-bottom: 3px;
    }
    .detail-row span {
      font-weight: 600;
      color: #333;
    }
    .action-btns {
      display: flex;
      gap: 10px;
      margin-top: 10px;
      justify-content: center;
    }
    .btn-action {
      padding: 5px 10px;
      font-size: 0.8rem;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <div class="header">
    <div class="d-flex align-items-center gap-3">
      @if(!empty($company->Logo))
        <img src="{{ $company->Logo }}" alt="Logo">
      @else
        <i class="bi bi-tools fs-1"></i>
      @endif
      <h1>STATUS SERVIS BENGKEL</h1>
      <button id="btnEnableSound" class="btn btn-sm btn-outline-light ms-3"><i class="bi bi-volume-mute-fill"></i> Aktifkan Suara</button>
    </div>
    <div class="clock" id="jam">00:00:00</div>
  </div>

  <div class="kanban-container">
    
    <!-- Kolom Menunggu -->
    <div class="kanban-col">
      <div class="kanban-header bg-menunggu">
        <i class="bi bi-hourglass-split"></i> Menunggu
      </div>
      <div class="kanban-body" id="col-menunggu">
        <!-- Data via AJAX -->
      </div>
    </div>

    <!-- Kolom Dikerjakan -->
    <div class="kanban-col">
      <div class="kanban-header bg-dikerjakan">
        <i class="bi bi-wrench-adjustable-circle"></i> Sedang Dikerjakan
      </div>
      <div class="kanban-body" id="col-dikerjakan">
        <!-- Data via AJAX -->
      </div>
    </div>

    <!-- Kolom Selesai -->
    <div class="kanban-col">
      <div class="kanban-header bg-selesai">
        <i class="bi bi-check-circle-fill"></i> Selesai
      </div>
      <div class="kanban-body" id="col-selesai">
        <!-- Data via AJAX -->
      </div>
    </div>

  </div>

  <!-- Audio untuk Notifikasi -->
  <audio id="audio-bell" src="https://cdn.pixabay.com/download/audio/2021/08/04/audio_0625c1539c.mp3?filename=ding-idea-40142.mp3" preload="auto"></audio>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.responsivevoice.org/responsivevoice.js?key=jn7RFIYJ"></script>
  
  <script>
    const recordOwnerID = '{{ $company->RecordOwnerID ?? '' }}';
    const idE = '{{ $idE }}';
    
    // Clock
    setInterval(() => {
      const d = new Date();
      document.getElementById('jam').innerText = d.toLocaleTimeString('id-ID');
    }, 1000);

    let finishedTrxCache = [];
    let processingTrxCache = [];
    let initialLoad = true;

    function formatPlat(plat) {
      if(!plat) return "";
      // Tambahkan spasi di tiap huruf/angka agar dibaca per karakter
      return plat.replace(/[^a-zA-Z0-9]/g, '').split('').join(' ');
    }

    function fetchData() {
      $.ajax({
        url: '{{ route("queue-bengkel-getData") }}',
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          RecordOwnerID: recordOwnerID
        },
        success: function(res) {
          renderCol('col-menunggu', res.menunggu, 'card-menunggu', 'menunggu');
          renderCol('col-dikerjakan', res.dikerjakan, 'card-dikerjakan', 'dikerjakan');
          renderCol('col-selesai', res.selesai, 'card-selesai', 'selesai');

          let newFinished = res.selesai;
          let newProcessing = res.dikerjakan;

          if(!initialLoad) {
            let addedFinished = newFinished.filter(x => !finishedTrxCache.includes(x.NoTransaksi));
            let addedProcessing = newProcessing.filter(x => !processingTrxCache.includes(x.NoTransaksi));

            if (addedFinished.length > 0) {
              let plat = formatPlat(addedFinished[0].PlatNomor);
              let text = "Panggilan untuk pemilik kendaraan dengan plat nomor " + plat + ", servis telah selesai, silakan menuju ke kasir.";
              document.getElementById('audio-bell').play();
              setTimeout(() => {
                responsiveVoice.speak(text, "Indonesian Female", {rate: 0.85});
              }, 1500);
            } else if (addedProcessing.length > 0) {
              let plat = formatPlat(addedProcessing[0].PlatNomor);
              let text = "Kendaraan dengan plat nomor " + plat + ", sedang mulai dikerjakan oleh mekanik.";
              responsiveVoice.speak(text, "Indonesian Female", {rate: 0.85});
            }
          }

          finishedTrxCache = newFinished.map(x => x.NoTransaksi);
          processingTrxCache = newProcessing.map(x => x.NoTransaksi);
          initialLoad = false;
        }
      });
    }

    function renderCol(elId, dataArr, cardClass, type) {
      let html = '';
      if(dataArr.length === 0) {
        html = '<div class="text-center text-muted mt-5"><i class="bi bi-inbox fs-1"></i><p>Tidak ada data</p></div>';
      } else {
        dataArr.forEach(trx => {
          let actionBtns = '';
          // Jika status bukan Selesai (2), tampilkan tombol update
          if (trx.StatusServis == 0) {
            actionBtns = `<button class="btn btn-sm btn-primary btn-action w-100" onclick="updateStatus('${trx.NoTransaksi}', 1)"><i class="bi bi-play-fill"></i> Mulai Kerjakan</button>`;
          } else if (trx.StatusServis == 1) {
            actionBtns = `<button class="btn btn-sm btn-success btn-action w-100" onclick="updateStatus('${trx.NoTransaksi}', 2)"><i class="bi bi-check-lg"></i> Selesai Servis</button>`;
          }

          html += `
            <div class="kanban-card ${cardClass}">
              <div class="plat-nomor"><i class="fas fa-car-side text-muted me-2"></i>${trx.PlatNomor}</div>
              <div class="detail-row">
                Mekanik: <span>${trx.NamaMekanik ?? '-'}</span>
              </div>
              <div class="detail-row">
                No Struk: <span style="font-size:0.8rem;">${trx.NoTransaksi}</span>
              </div>
              <div class="detail-row">
                Waktu: <span>${trx.TglTransaksi}</span>
              </div>
              ${actionBtns ? '<div class="action-btns">' + actionBtns + '</div>' : ''}
            </div>
          `;
        });
      }
      $('#' + elId).html(html);
    }

    function updateStatus(noTransaksi, newStatus) {
      Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin memindahkan status kendaraan ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Pindahkan',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '{{ route("queue-bengkel-updateStatus") }}',
            type: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              NoTransaksi: noTransaksi,
              StatusServis: newStatus,
              RecordOwnerID: recordOwnerID
            },
            success: function(res) {
              if(res.success) {
                fetchData();
              } else {
                Swal.fire('Error', res.message, 'error');
              }
            }
          });
        }
      });
    }

    $(document).ready(function() {
      fetchData();
      // Auto refresh every 5 seconds
      setInterval(fetchData, 5000);

      $('#btnEnableSound').on('click', function() {
        // Unlock browser autoplay policy
        let audio = document.getElementById('audio-bell');
        audio.play().then(() => {
          audio.pause();
          audio.currentTime = 0;
        }).catch(e => console.log("Audio unlock error:", e));
        
        if (typeof responsiveVoice !== 'undefined') {
          responsiveVoice.speak("", "Indonesian Female");
        }
        
        $(this).removeClass('btn-outline-light').addClass('btn-success');
        $(this).html('<i class="bi bi-volume-up-fill"></i> Suara Aktif');
      });
    });
  </script>
</body>
</html>
