<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kiosk Antrean Pendaftaran</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!-- Global Theme Styles -->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body {
            @if(isset($company) && $company->KioskBackground)
            background-image: url('{{ asset($company->KioskBackground) }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            @else
            background-color: #f0f2f5;
            @endif
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }
        
        .kiosk-container {
            text-align: center;
            background: #fff;
            padding: 50px 60px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 90%;
        }

        .company-logo {
            max-width: 120px;
            max-height: 120px;
            margin-bottom: 20px;
        }

        .btn-ambil {
            font-size: 1.5rem;
            padding: 20px 0;
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .btn-ambil:last-child {
            margin-bottom: 0;
        }

        .btn-umum { background-color: #3b99fc; color: white; border: none; }
        .btn-bpjs { background-color: #27c2b3; color: white; border: none; }
        .btn-asuransi { background-color: #8f5af6; color: white; border: none; }

        .btn-ambil:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            color: #fff;
            opacity: 0.9;
        }

        .ticket-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .ticket-box {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            animation: popIn 0.3s ease forwards;
        }
        .nomor-antrean {
            font-size: 5rem;
            font-weight: 800;
            color: #181c32;
            margin: 20px 0;
        }
        @keyframes popIn {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>

    <div class="kiosk-container">
        @if(isset($company) && $company->Logo)
            <img src="{{ asset($company->Logo) }}" alt="Logo" class="company-logo">
        @endif
        
        <h2 class="mb-4 font-weight-bolder text-dark" style="font-size: 2rem;">
            Selamat Datang di {{ isset($company) && $company->NamaPartner ? $company->NamaPartner : 'Klinik' }}
        </h2>
        <p class="text-dark-50 font-size-h5 mb-10" style="font-size: 1.1rem;">
            Silakan ambil nomor antrean pendaftaran Anda dengan menekan tombol di bawah ini.
        </p>
        
        <button class="btn btn-ambil btn-umum btn-kiosk" data-tipe="Umum">
            UMUM / PRIBADI
        </button>
        <button class="btn btn-ambil btn-bpjs btn-kiosk" data-tipe="BPJS">
            ASURANSI BPJS
        </button>
        <button class="btn btn-ambil btn-asuransi btn-kiosk" data-tipe="Asuransi">
            ASURANSI SWASTA
        </button>
    </div>

    <!-- Modal Tiket -->
    <div class="ticket-modal" id="ticketModal">
        <div class="ticket-box">
            <h2 class="text-muted">Nomor Antrean Anda:</h2>
            <div class="nomor-antrean" id="txtNomor">--</div>
            <h4 class="text-dark">Silakan menunggu di ruang tunggu.</h4>
            <p class="text-muted mt-3">Layar ini akan menutup otomatis dalam <span id="countdown">5</span> detik...</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.btn-kiosk').click(function() {
                var btn = $(this);
                var tipe = btn.data('tipe');
                var originalText = btn.text();
                
                // Disable all buttons to prevent double click
                $('.btn-kiosk').attr('disabled', true);
                btn.html('<i class="fas fa-spinner fa-spin"></i> MEMPROSES...');

                $.ajax({
                    url: "{{ route('klinik-kiosk.ambil') }}",
                    type: "POST",
                    data: { tipe: tipe },
                    success: function(response) {
                        $('.btn-kiosk').attr('disabled', false);
                        btn.text(originalText);
                        
                        if(response.success) {
                            $('#txtNomor').text(response.nomor);
                            $('#ticketModal').css('display', 'flex');
                            
                            var timeLeft = 5;
                            var timer = setInterval(function() {
                                timeLeft--;
                                $('#countdown').text(timeLeft);
                                if(timeLeft <= 0) {
                                    clearInterval(timer);
                                    $('#ticketModal').hide();
                                }
                            }, 1000);
                        } else {
                            Swal.fire("Error!", "Gagal mengambil antrean.", "error");
                        }
                    },
                    error: function() {
                        $('.btn-kiosk').attr('disabled', false);
                        btn.text(originalText);
                        Swal.fire("Error!", "Terjadi kesalahan sistem.", "error");
                    }
                });
            });
        });
    </script>
</body>
</html>
