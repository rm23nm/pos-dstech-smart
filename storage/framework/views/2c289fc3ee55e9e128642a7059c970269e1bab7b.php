<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Booking Online Table di <?php echo e($company->NamaPartner); ?></title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/favicon.ico')); ?>" />

        <!-- Font Awesome icons (free version)-->
        
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link rel="stylesheet" href="<?php echo e(asset('css/styles.css')); ?>">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
		<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand" href="#page-top" style="color: yellow; font-weight: bold;">
                    <?php echo e($company->NamaPartner); ?>

                </a>     
                <input type="hidden" id="kodePartner" value="<?php echo e($company->KodePartner); ?>">
           
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars ms-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                        
                        <li class="nav-item"><a class="nav-link" href="#portfolio">Booking</a></li>
                        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="#team">Gallery</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead" style="position: relative; background-image: url('<?php echo e($company->BannerBooking); ?>'); background-size: cover; background-position: center; ">
            <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);"></div>
    
    <div class="container position-relative">
        <div class="masthead-subheading"><?php echo $company->HeadlineBanner; ?></div>
        <div class="masthead-heading text-uppercase"><?php echo $company->SubHeadlineBanner; ?></div>
    </div>
        </header>
        <!-- Services-->
        
        <!-- Portfolio Grid-->
        <section class="page-section bg-light" id="portfolio">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Booking Layanan</h2>
                    <h3 class="section-subheading text-muted"></h3>
                </div>
                <!-- Filter Buttons -->
                <div class="row justify-content-center mb-5">
                    <div class="col-auto">
                        <div class="btn-group flex-wrap" role="group" aria-label="Filter Layanan">
                            <button type="button" class="btn btn-primary filter-btn m-1 active" data-filter="all">Semua Layanan</button>
                            <?php $__currentLoopData = $groupedLampu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelompok => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" class="btn btn-outline-primary filter-btn m-1" data-filter="<?php echo e(Str::slug($kelompok)); ?>"><?php echo e($kelompok); ?></button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <?php $__currentLoopData = $groupedLampu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelompok => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="portfolio-group" data-category="<?php echo e(Str::slug($kelompok)); ?>">
                        <div class="text-center">
                            <h3 class="section-heading text-muted"><?php echo e($kelompok); ?></h3>
                            <hr>
                        </div>
                        <div class="row">
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lampu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-lg-4 col-sm-6 mb-4">
                                    <div class="portfolio-item">
                                        <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal<?php echo e($lampu->id); ?>">
                                            <div class="portfolio-hover">
                                                <div class="portfolio-hover-content">
                                                    <i class="fas fa-plus fa-3x"></i>
                                                </div>
                                            </div>
                                            <img class="img-fluid" src="<?php echo e(asset('assets/img/portfolio/meja.jpg')); ?>" alt="..." />
                                        </a>
                                        <div class="portfolio-caption">
                                            <div class="portfolio-caption-heading"><?php echo e($lampu->NamaTitikLampu); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </section>
        <!-- About-->
        <section class="page-section" id="about">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">About Us</h2>
                    <h3 class="section-subheading text-muted"><?php echo $company->AboutUs; ?></h3>
                </div>
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Term And Condition</h2>
                    <h3 class="section-subheading text-muted"><?php echo $company->TermAndCondition; ?></h3>
                </div>
                
            </div>
        </section>
        <!-- Team-->
        <section class="page-section bg-light" id="team">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Gallery</h2>
                    <h3 class="section-subheading text-muted">  </h3>
                </div>
               <!-- Grid Gallery -->
<div class="row">
    <?php $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $galleries): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $galleries->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(!is_null($image) && !empty($image)): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="team-member">
                        <!-- Klik gambar untuk membuka modal -->
                        <img class="mx-auto rounded-circle img-thumbnail" src="<?php echo e($image); ?>" alt="Gallery Image" 
                            data-bs-toggle="modal" data-bs-target="#imageModal<?php echo e($index); ?>" style="cursor: pointer; ">
                    </div>
                </div>

                <!-- Modal untuk menampilkan gambar full-size -->
                <div class="modal fade" id="imageModal<?php echo e($index); ?>" tabindex="-1" aria-labelledby="imageModalLabel<?php echo e($index); ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel<?php echo e($index); ?>">Preview Gambar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="<?php echo e($image); ?>" style="max-width: 100%; height: auto;" alt="Full Image">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

                
                
                
            </div>
        </section>
        <!-- Clients-->
        
        <!-- Contact-->
        <section class="page-section" id="contact">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Contact Us</h2>
                    <h3 class="section-subheading text-muted" style="color: white !important;">Hubungi kami untuk informasi lebih lanjut.</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="mb-4">
                            <h4 class="text-uppercase" style="color: white;">Alamat</h4>
                            <p class="text-muted" style="color: yellow !important;"><?php echo e($company->AlamatTagihan); ?></p>
                        </div>
                        <div class="mb-4">
                            <h4 class="text-uppercase" style="color: white;">Telepon</h4>
                            <p class="text-muted"><a href="tel:<?php echo e($company->NoTlp); ?>"><?php echo e($company->NoTlp); ?></a></p>
                        </div>
                        <div class="mb-4">
                            <h4 class="text-uppercase" style="color: white;">Email</h4>
                            <p class="text-muted"><a href="mailto:<?php echo e($user->email); ?>"><?php echo e($user->email); ?></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Footer-->
        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 text-lg-start">Copyright &copy; AIS System Solo 2025</div>
                    
                </div>
            </div>
        </footer>
        <!-- Portfolio Modals-->
        <!-- Portfolio item 1 modal popup-->
        <?php $__currentLoopData = $titikLampu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lampu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <div class="portfolio-modal modal fade" id="portfolioModal<?php echo e($lampu->id); ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="close-modal" data-bs-dismiss="modal"><img src="<?php echo e(asset('assets/img/close-icon.svg')); ?>" alt="Close modal" />
                    </div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="modal-body">
                                    <!-- Project details-->
                                    <h2 class="text-uppercase"><?php echo e($lampu->NamaTitikLampu); ?></h2>
                                    <input type="hidden" name="idMeja" value="<?php echo e($lampu->id); ?>">
                                    <p class="item-intro text-muted">Layanan Bisa Di Booking dari Jam: <?php echo e($company->JamAwalBooking); ?> - <?php echo e($company->fJamAkhirBooking); ?></p>
                                    
                                    
                                    <ul class="list-group w-100">
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <strong >No HP:</strong>
                                            <input type="tel" class="form-control w-75" name="noTelp" id="phoneNumber">
                                        </li>

                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <strong >Nama Lengkap:</strong>
                                            <input type="text" class="form-control w-75" name="namaLengkap" id ="namaLengkap">
                                        </li>
                                    
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <strong >Email Address:</strong>
                                            <input type="email" class="form-control w-75" name="email" id= "emailaddress">
                                        </li>
                                    
                                        <li class="list-group-item text-center fw-bold">---</li>
                                    
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <strong>Pilih Tanggal Booking:</strong>
                                            <input type="date" class="form-control w-75 text-center" name="tanggalbooking" id="tanggalbooking" min="<?php echo e($today); ?>">
                                        </li>
                                    
                                        <div id="bookingInfo" class="text-danger text-center my-2"></div>
                                    
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <strong>Jam Awal Booking:</strong>
                                            <input type="text" class="form-control w-75 text-center" name="jamMulai" id="jamMulai" step="60">
                                        </li>
                                    
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <strong>Jam Akhir Booking:</strong>
                                            <input type="text" class="form-control w-75 text-center" name="jamSelesai" id="jamSelesai" step="60">
                                        </li>
                                        
                                        <div id="crashInfo" class="text-danger text-center my-2"></div>
                                        <li class="list-group-item text-center fw-bold">---</li>
                                    
                                        <li class="list-group-item">
                                            <strong>Pilih Paket :</strong>
                                            <div class="mt-2">
                                                <?php $__currentLoopData = $paketTransaksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="paket" 
                                                            value="<?php echo e($paket->id); ?>" id="paket<?php echo e($paket->id); ?>" 
                                                            data-harga="<?php echo e($paket->HargaNormal); ?>" 
                                                            data-jenis="<?php echo e($paket->JenisPaket); ?>">
                                                        <label class="form-check-label" for="paket<?php echo e($paket->id); ?>">
                                                            <?php echo e($paket->NamaPaket); ?> - Rp <?php echo e(number_format($paket->HargaNormal, 0, ',', '.')); ?> 
                                                            per <?php echo e($paket->JenisPaket); ?>

                                                        </label>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </li>
                                    
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <strong>Extra Request:</strong>
                                            <textarea class="form-control w-75" name="extraRequest" rows="3"></textarea>
                                        </li>

                                        <li class="list-group-item d-flex flex-column">
                                            <strong>Kode Voucher Discount:</strong>
                                            <input type="text" class="form-control w-100 mt-2" name="voucherCode" id="voucherCode">
                                            <button class="btn btn-primary mt-2 w-30 mx-auto" type="button" id="applyVoucher">Apply</button>
                                            
                                            <div class="voucherInfo text-danger text-center my-2"></div>
                                        </li>

                                        
                                    
                                        <li class="list-group-item d-flex flex-column">
                                            <strong class="fs-6">Total Transaksi: Rp <span id="totalAsli" class="fs-6 text-danger">0</span></strong>
                                            <strong class="fs-6">Total Diskon: Rp <span id="totalDiskon" class="fs-6 text-warning">0</span></strong>
                                            <strong class="fs-4">Total Setelah Diskon: Rp <span id="totalTransaksi" class="fs-3 fw-bold text-success">0</span></strong>
                                        </li>
                                        
                                        <li class="list-group-item d-flex flex-column">
                                            <strong>Term and Condition</strong>
                                            <div> <?php echo $company->TermAndConditionBookingOnline; ?> </div>
                                            <input type="checkbox" name="AcceptTermAndConditionBookingOnline" id="AcceptTermAndConditionBookingOnline">
                                            <label for="terms"> Setuju dengan syarat diatas</label>

                                            <div class="voucherInfo text-danger text-center my-2"></div>
                                        </li>
                                    </ul>


                                    
                                    <div class="d-flex justify-content-center gap-3 mt-4">
                                        <button class="btn btn-success btn-lg text-uppercase" id="btn-success" type="button">
                                            <i class="fas fa-check me-1"></i>
                                            Bayar
                                        </button>
                                    
                                        <button class="btn btn-danger btn-lg text-uppercase" data-bs-dismiss="modal" type="button">
                                            <i class="fas fa-xmark me-1"></i>
                                            Batal
                                        </button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
       
        <!-- Core theme JS-->
        <script src="<?php echo e(asset('js/scripts.js')); ?>"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php if(env('MIDTRANS_IS_PRODUCTION') == false): ?>
<script src="<?php echo e(env('MIDTRANS_DEV_URL')); ?>" data-client-key="<?php echo e($midtransclientkey); ?>"></script>
<?php else: ?>
<script src="<?php echo e(env('MIDTRANS_PROD_URL')); ?>" data-client-key="<?php echo e($midtransclientkey); ?>"></script>
<?php endif; ?>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
        <script>

            function hitungTotal(event) {
                let modal = event.target.closest(".modal");
                let jamMulai = modal.querySelector("input[name='jamMulai']").value;
                let jamSelesai = modal.querySelector("input[name='jamSelesai']").value;
                let paketDipilih = modal.querySelector("input[name='paket']:checked");
                let voucherCode = modal.querySelector("input[name='voucherCode']").value.trim();
                var kodePartner = document.getElementById('kodePartner').value;
                

                if (!jamMulai || !jamSelesai || !paketDipilih) {
                    updateTotal(0, 0, 0);
                    return;
                }

                let harga = parseInt(paketDipilih.getAttribute("data-harga"));
                let jenisPaket = paketDipilih.getAttribute("data-jenis");

                // Konversi jam ke menit
                let [jamAwal, menitAwal] = jamMulai.split(":").map(Number);
                let [jamAkhir, menitAkhir] = jamSelesai.split(":").map(Number);
                let totalMenit = (jamAkhir * 60 + menitAkhir) - (jamAwal * 60 + menitAwal);

                let totalAsli = 0;
                if (jenisPaket.toLowerCase() === "jam") {
                    let totalJam = Math.ceil(totalMenit / 60);
                    totalAsli = harga * totalJam;
                } else if (jenisPaket.toLowerCase() === "menit") {
                    totalAsli = harga * totalMenit;
                }

                let totalDiskon = 0;
                let totalSetelahDiskon = totalAsli;

                console.log("Total Asli sebelum diskon:", totalAsli);

                function updateTotal(finalTotal, discount, originalTotal) {
                    modal.querySelector("#totalAsli").innerText = originalTotal.toLocaleString("id-ID");
                    modal.querySelector("#totalDiskon").innerText = discount.toLocaleString("id-ID");
                    modal.querySelector("#totalTransaksi").innerText = finalTotal.toLocaleString("id-ID");
                }

                if (voucherCode === "") {
                    updateTotal(totalSetelahDiskon, totalDiskon, totalAsli);
                    return;
                }

                $.ajax({
                    url: `/booking/${kodePartner}/get-DiscountVoucher`,
                    type: 'GET',
                    data: { code: voucherCode, kodePartner: kodePartner },
                    dataType: 'json',
                    success: function (data) {
                        console.log("Response voucher:", data);
                        
                        if (data.success) {

                            let discountPercent = parseFloat(data.discountPercent) / 100;
                            let maximalDiscount = parseFloat(data.maximalDiscount);
                            let discountQuota = parseFloat(data.discountQuota);

                            console.log("Diskon persen:", discountPercent);
                            console.log("Maksimal diskon:", maximalDiscount);
                            console.log("Kuota diskon:", discountQuota);

                            if (discountQuota >= totalAsli) {
                                let calculatedDiscount = totalAsli * discountPercent;
                                totalDiskon = Math.min(calculatedDiscount, maximalDiscount);
                                totalSetelahDiskon = totalAsli - totalDiskon;

                                console.log("Diskon diterapkan:", totalDiskon);

                            
                            } else {
                                console.log("Kuota diskon tidak mencukupi, diskon tidak diterapkan.");
                                
                            }
                        } else {
                            console.log("Kode voucher tidak valid atau tidak ditemukan.");
                            
                        }
                        
                        updateTotal(totalSetelahDiskon, totalDiskon, totalAsli);
                    

                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching voucher data:", error);
                        updateTotal(totalSetelahDiskon, totalDiskon, totalAsli);
                    }
                });
            }



            // Event listener untuk perubahan input
            document.addEventListener("change", function (event) {
                if (
                    event.target.matches("input[name='jamMulai']") ||
                    event.target.matches("input[name='jamSelesai']") ||
                    event.target.matches("input[name='paket']") ||
                    event.target.matches("input[name='voucherCode']")
                ) {
                    hitungTotal(event);
                }
            });


            document.addEventListener("DOMContentLoaded", function () {
                
                document.querySelectorAll(".btn-success").forEach(button => {
                    button.addEventListener("click", function (event) {
                        var kodePartner = document.getElementById('kodePartner').value;
                        let modal = event.target.closest(".modal");
                        let modalId = modal.id; 

                        let lampuId = modalId.replace("portfolioModal", "");

                        let formData = {
                            namaLengkap: modal.querySelector("input[name='namaLengkap']").value,
                            mejaID: lampuId,
                            email: modal.querySelector("input[name='email']").value,
                            noTelp: modal.querySelector("input[name='noTelp']").value,
                            tanggalBooking: modal.querySelector("input[name='tanggalbooking']").value,
                            jamMulai: modal.querySelector("input[name='jamMulai']").value,
                            jamSelesai: modal.querySelector("input[name='jamSelesai']").value,
                            paketid: modal.querySelector("input[name='paket']:checked")?.value || null,
                            ExtraRequest: modal.querySelector("textarea[name='extraRequest']").value,
                            totalPembelian: parseInt(modal.querySelector("#totalTransaksi").innerText.replace(/\D/g, "")),
                            totalAsli: parseInt(modal.querySelector("#totalAsli").innerText.replace(/\D/g, "")),
                            totalDiskon: parseInt(modal.querySelector("#totalDiskon").innerText.replace(/\D/g, "")),
                            voucherCode: modal.querySelector("input[name='voucherCode']").value,
                            kodePartner: kodePartner,
                        };
                        var isAccepted = modal.querySelector('input[name="AcceptTermAndConditionBookingOnline"]');
                    
                                    // Validasi hanya untuk field yang wajib diisi
                        if (!formData.namaLengkap || !formData.email || 
                            !formData.tanggalBooking || !formData.jamMulai || !formData.jamSelesai || 
                            !formData.paketid) {
                            
                            Swal.fire({
                                icon: "warning",
                                title: "Oops...",
                                text: "Mohon isi semua data yang diperlukan!",
                            });
                            return;
                        }

                        if(!isAccepted.checked){
                            Swal.fire({
                                icon: "warning",
                                title: "Oops...",
                                text: "Anda belum Menyetujui Term and Condition",
                            });
                            return;
                        }
                                    
                        let noTransaksi = "BOOKING"+Date.now(); // Contoh nomor transaksi unik
                        PaymentGateWay($(button), "Bayar", formData);
                    });
                });
            });

            function PaymentGateWay(ButtonObject, ButtonDefaultText, formData) {
                ButtonObject.text('Tunggu Sebentar.....');
                ButtonObject.attr('disabled', true);

                console.log("FormData:", formData);  // Debugging
                console.log("TotalPembelian:", formData.totalPembelian);

                    
                    let oData = {
                        'NoTransaksi': formData.NoTransaksi,
                        'TotalPembelian': formData.totalPembelian,
                        "kodePartner": formData.kodePartner,
                    };
                    
                    fetch("<?php echo e(route('booking-create-gateway')); ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify(oData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.snap_token) {
                            snap.pay(data.snap_token, {
                                onSuccess: function (result) {
                                    if (result.transaction_status === "cancel") {
                                        ButtonObject.text('Bayar');
                                        ButtonObject.attr('disabled', false);
                                        Swal.fire({
                                            icon: "error",
                                            title: "Oops...",
                                            text: "Pembayaran Dibatalkan",
                                        });
                                    } else {
                                        let xData = {
                                            "NoTransaksi": formData.NoTransaksi,
                                            "TglBooking": formData.tanggalBooking,
                                            "Keterangan": result.payment_type + "#" + (result.va_numbers?.[0]?.bank || "") + "#" + (result.va_numbers?.[0]?.va_number || ""),
                                            "JamMulai": formData.jamMulai,
                                            "JamSelesai": formData.jamSelesai,
                                            "mejaID": formData.mejaID,
                                            "paketid": formData.paketid,
                                            "KodeSales": "-",
                                            "KodePelanggan": "-",
                                            "StatusTransaksi": 0,
                                            "ExtraRequest": formData.ExtraRequest,
                                            "TotalTransaksi": formData.totalAsli,
                                            "TotalTax": 0,
                                            "TotalDiskon": formData.totalDiskon,
                                            "TotalLainLain": 0,
                                            "NetTotal": formData.totalPembelian,
                                            "NamaPelanggan": formData.namaLengkap,
                                            "Email": formData.email,
                                            "NoTlp1": formData.noTelp,
                                            "VoucherCode" : formData.voucherCode,
                                            "kodePartner": formData.kodePartner,
                                        };
                                        
                                        fetch("<?php echo e(route('booking-pay-gateway')); ?>", {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                            },
                                            body: JSON.stringify(xData)
                                        })
                                        .then(response => response.json())
                                        .then(response => {
                                            if (response.success) {
                                                Swal.fire({
                                                    icon: "success",
                                                    title: 'Berhasil',
                                                    text: 'Pembayaran berhasil disimpan, Silahkan Cek Email Anda!',
                                                }).then(() => {
                                                    location.reload();
                                                });
                                            } else {
                                                ButtonObject.text('Bayar');
                                                ButtonObject.attr('disabled', false);
                                                Swal.fire({
                                                    icon: "error",
                                                    title: 'Error',
                                                    text: response.message,
                                                });
                                            }
                                        });
                                    }
                                },
                                onError: function (result) {
                                    ButtonObject.text('Bayar');
                                    ButtonObject.attr('disabled', false);
                                    Swal.fire({
                                        icon: "error",
                                        title: "Oops...",
                                        text: "Terjadi kesalahan saat pembayaran",
                                    });
                                },
                                onClose: function () {
                                    ButtonObject.text('Bayar');
                                    ButtonObject.attr('disabled', false);
                                    console.log('Pelanggan menutup popup pembayaran');
                                }
                            });
                        } else {
                            ButtonObject.text('Bayar');
                            ButtonObject.attr('disabled', false);
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: data.error,
                            });
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
            function formatDateToDMY_HM(date) {
                const pad = (n) => n.toString().padStart(2, '0');
                const day = pad(date.getDate());
                const month = pad(date.getMonth() + 1); // getMonth() dimulai dari 0
                const year = date.getFullYear();
                const hours = pad(date.getHours());
                const minutes = pad(date.getMinutes());

                return `${day}/${month}/${year} ${hours}:${minutes}`;
            }

            function SetEnableCommand() {
                var modal = $(this).closest('.modal-body');
                var namaLengkap = modal.find('input[name="namaLengkap"]').val();
                var email = modal.find('input[name="email"]').val();
                var noTelp = modal.find('input[name="noTelp"]').val();

                var isAccepted = modal.find('input[name="AcceptTermAndConditionBookingOnline"]').is(':checked');

                var isValid = true;

                if (isEmpty(namaLengkap) || isEmpty(email) || isEmpty(noTelp) || !isAccepted) {
                    isValid = false;
                }
                console.log(isValid);

                modal.find('#btn-success').prop('disabled', isValid);
            }
            function isEmpty(value) {
                return typeof value === 'undefined' || value.trim() === '';
            }

            $(document).ready(function () {
                flatpickr("#jamMulai", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",  // 24-hour format: H = hour (00-23), i = minutes
                    time_24hr: true,
                    minuteIncrement: 60,
                    onChange: function(selectedDates, dateStr, instance) {
                        const hour = selectedDates[0].getHours();
                        if (hour % 1 !== 0) { // hanya izinkan jam kelipatan 2
                            alert("Hanya boleh pilih jam kelipatan 1.");
                            instance.clear(); // reset input
                        }
                    }
                    // minTime: new Date().toTimeString().slice(0,5)
                });
                flatpickr("#jamSelesai", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",  // 24-hour format: H = hour (00-23), i = minutes
                    time_24hr: true,
                    minuteIncrement: 60,
                    // minTime: new Date().toTimeString().slice(0,5)
                });
                let bookedSlots = {}; // Objek untuk menyimpan daftar jam yang sudah dibooking berdasarkan ID meja

                SetEnableCommand()
                
                $(document).on('blur', 'input[name="noTelp"]', function () {
                    const modal   = $(this).closest('.modal-body');
                    const NomorHP = $.trim($(this).val());
                    const Email   = modal.find('input[name="email"]').val();

                    console.log('lostfocus:', NomorHP);

                    if (!NomorHP) return;

                    $.ajax({
                        url: '/api/pelanggan/viewJson',
                        method: 'POST',
                        contentType: 'application/json; charset=UTF-8',
                        dataType: 'json',
                        headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        data: JSON.stringify({
                        KodePelanggan: "",
                        GrupPelanggan: "",
                        Search: "",
                        NoTlp1: NomorHP,
                        Email: Email,
                        RecordOwnerID: document.getElementById('kodePartner').value
                        })
                    })
                    .done(function (resp) {
                        // Lihat bentuk data sebenarnya
                        console.log('resp:', resp);

                        const rows = resp && resp.data ? resp.data : [];
                        if (rows.length) {
                        const row = rows[0];
                        // Set email di modal ini
                        modal.find('input[name="email"]').val(row.Email || '');
                        // Set nama lengkap (coba by name dulu, fallback ke id)
                        modal.find('input[name="namaLengkap"]').val(row.NamaPelanggan || '');
                        $('#namaLengkap').val(row.NamaPelanggan || '');
                        } else {
                        console.log('Tidak ada data pelanggan untuk nomor ini');
                        }
                    })
                    .fail(function (xhr) {
                        console.error('AJAX error:', xhr.responseText || xhr.statusText);
                        modal.find('#btn-success').prop('disabled', true);
                    });
                });

                // Event ketika tanggal booking diubah
                $(document).on('change', 'input[name="tanggalbooking"]', function () {
                    var selectedDate = $(this).val();
                    var modal = $(this).closest('.modal-body');
                    var idMeja = modal.find('input[name="idMeja"]').val();
                    var bookingInfoContainer = modal.find('#bookingInfo');
                    var kodePartner = document.getElementById('kodePartner').value;

                    var _nowLocal = new Date();
                    const year = _nowLocal.getFullYear();
                    const month = String(_nowLocal.getMonth() + 1).padStart(2, '0');
                    const day = String(_nowLocal.getDate()).padStart(2, '0');
                    const today = `${year}-${month}-${day}`;

                    var jamMulaiInstance = modal.find('input[name="jamMulai"]')[0];
                    var jamSelesaiInstance = modal.find('input[name="jamSelesai"]')[0];
                    if (selectedDate === today) {
                        const nowTime = new Date().toTimeString().slice(0, 5); // HH:mm
                        jamMulaiInstance._flatpickr.set('minTime', nowTime);
                        jamSelesaiInstance._flatpickr.set('minTime', nowTime);
                    } else {
                        jamMulaiInstance._flatpickr.set('minTime', null);
                        jamSelesaiInstance._flatpickr.set('minTime', null);
                    }

                    bookingInfoContainer.html('');
                    bookedSlots[idMeja] = []; // Reset daftar booking sebelumnya untuk meja ini

                    if (selectedDate && idMeja) {
                        $.ajax({
                            url: `/booking/${kodePartner}/get-bookedtable`,
                            type: 'GET',
                            data: { tanggal: selectedDate, idMeja: idMeja, RecordOwnerID:kodePartner },
                            success: function (data) {
                                // console.log(data);
                                SetEnableCommand()
                                if (data.length > 0) {
                                    var infoHtml = '<strong>Meja ini sudah dibooking:</strong><ul>';
                                    data.forEach(function (booking) {
                                        infoHtml += '<li>Jam ' + booking.JamMulai + ' - ' + booking.JamSelesai + '</li>';
                                        bookedSlots[idMeja].push({ start: booking.JamMulai, end: booking.JamSelesai, JenisPaket:booking.JenisPaket }); // Simpan waktu booking untuk meja ini
                                    });
                                    infoHtml += '</ul>';
                                    bookingInfoContainer.html(infoHtml);
                                    // $('#btn-success').attr('disabled',true);
                                    modal.find('#btn-success').prop('disabled', true);
                                } else {
                                    bookingInfoContainer.html('<strong>Meja ini masih tersedia di tanggal ini.</strong>');
                                    // $('#btn-success').attr('disabled',false);
                                    modal.find('#btn-success').prop('disabled', false);
                                }
                            },
                            error: function () {
                                bookingInfoContainer.html('<strong>Terjadi kesalahan saat mengambil data.</strong>');
                                // $('#btn-success').attr('disabled',true);
                                modal.find('#btn-success').prop('disabled', true);
                            }
                        });
                    }
                });

                // Validasi input jam booking (Gunakan event delegation untuk semua modal)
                $(document).on('change', 'input[name="jamMulai"], input[name="jamSelesai"]', function () {
                    SetEnableCommand();
                    var modal = $(this).closest('.modal-body');
                    var idMeja = modal.find('input[name="idMeja"]').val();
                    var jamMulai = modal.find('input[name="jamMulai"]').val();
                    var jamSelesai = modal.find('input[name="jamSelesai"]').val();
                    var tanggal = modal.find('input[name="tanggalbooking"]').val();
                    
                    var crashInfoContainer = modal.find('#crashInfo');

                    // ⏱️ Set minTime di jamSelesai jika pakai Flatpickr
                    if (jamMulai) {
                        var jamSelesaiInput = modal.find('input[name="jamSelesai"]')[0];
                        if (jamSelesaiInput._flatpickr) {
                            jamSelesaiInput._flatpickr.set('minTime', jamMulai);
                        }
                    }

                    if (tanggal && jamMulai && jamSelesai) {
                        var mulai = new Date(tanggal + 'T' + jamMulai + ':00');
                        var selesai = new Date(tanggal + 'T' + jamSelesai + ':00');

                        if (!bookedSlots[idMeja] || bookedSlots[idMeja].length === 0) {
                            modal.find('#btn-success').prop('disabled', false);
                            return;
                        }

                        var conflicts = false;
                        console.log(bookedSlots);
                        for (let i = 0; i < bookedSlots[idMeja].length; i++) {
                            let booking = bookedSlots[idMeja][i];

                            // ✅ Jika JenisPaket = 'Menit', langsung blokir booking
                            if (booking.JenisPaket === 'MENIT') {
                                alert('Layanan ini Sudah dipakai');
                                modal.find('input[name="jamMulai"]').val('');
                                modal.find('input[name="jamSelesai"]').val('');
                                modal.find('#btn-success').prop('disabled', true);
                                return;
                            }

                            var bookedStart = new Date(booking.start + ':00');
                            var bookedEnd = new Date(booking.end + ':00');

                            if (
                                (mulai >= bookedStart && mulai < bookedEnd) ||
                                (selesai > bookedStart && selesai <= bookedEnd) ||
                                (mulai <= bookedStart && selesai >= bookedEnd)
                            ) {
                                conflicts = true;
                                // crashInfo
                                crashInfoContainer.html('Waktu yang dipilih bertabrakan dengan booking lain (' + formatDateToDMY_HM(bookedStart) + ' - ' + formatDateToDMY_HM(bookedEnd) + ').');
                                // alert('Waktu yang dipilih bertabrakan dengan booking lain (' + formatDateToDMY_HM(bookedStart) + ' - ' + formatDateToDMY_HM(bookedEnd) + ').');
                                // modal.find('input[name="jamMulai"]').val('');
                                // modal.find('input[name="jamSelesai"]').val('');
                                modal.find('#btn-success').prop('disabled', true);
                                break;
                            }
                        }

                        if (!conflicts) {
                            modal.find('#btn-success').prop('disabled', false);
                        }
                    }
                });

                // --- Logic Filter Layanan ---
                $('.filter-btn').on('click', function() {
                    const filter = $(this).data('filter');
                    
                    // Update active button state
                    $('.filter-btn').removeClass('btn-primary active').addClass('btn-outline-primary');
                    $(this).removeClass('btn-outline-primary').addClass('btn-primary active');

                    if (filter === 'all') {
                        $('.portfolio-group').stop(true, true).fadeIn(300);
                    } else {
                        $('.portfolio-group').stop(true, true).hide();
                        $(`.portfolio-group[data-category="${filter}"]`).stop(true, true).fadeIn(300);
                    }
                });



            });


        </script>

    
<script>
    var _globalBarcodeScannerBuffer = "";
    var _globalBarcodeScannerTimer = null;
    
    $(document).on("keypress", function(e) {
        if (e.target.id === "_Barcode") return; // Ignore if already focused on barcode
        
        if (e.key && e.key.length === 1 && !e.ctrlKey && !e.altKey) {
            _globalBarcodeScannerBuffer += e.key;
            
            if (_globalBarcodeScannerTimer) clearTimeout(_globalBarcodeScannerTimer);
            
            _globalBarcodeScannerTimer = setTimeout(function() {
                _globalBarcodeScannerBuffer = "";
            }, 60); // Scanner types very fast
            
        } else if (e.key === "Enter" || e.keyCode === 13) {
            if (_globalBarcodeScannerBuffer.length >= 3) {
                // It's a scanner!
                e.preventDefault();
                $('#_Barcode').val(_globalBarcodeScannerBuffer);
                _globalBarcodeScannerBuffer = "";
                $('#_Barcode').focus();
                
                var eEnter = $.Event('keypress');
                eEnter.which = 13;
                eEnter.keyCode = 13;
                $('#_Barcode').trigger(eEnter);
            } else {
                _globalBarcodeScannerBuffer = "";
            }
        }
    });
</script>
</body>
</html>
<?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/Transaksi/Penjualan/PoS/BookingOnline.blade.php ENDPATH**/ ?>