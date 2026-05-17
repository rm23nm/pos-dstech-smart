<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company[0]['NamaPartner'] ?? 'Marketplace' }} | Belanja Online Aman & Nyaman</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>

    <style>
        :root {
            --primary-blue: #0d47a1; /* Royal Blue - Identitas Perusahaan */
            --primary-red: #d32f2f;  /* Strong Red - Untuk Flash Sale & Promo */
            --bg-light: #f4f7f6;
            --text-dark: #1a237e;
            --text-muted: #546e7a;
            --border-color: #cfd8dc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            margin: 0;
            padding-top: 120px;
        }

        /* TOP NAVIGATION */
        .top-nav {
            background-color: var(--primary-blue);
            color: white;
            font-size: 0.8rem;
            padding: 5px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1100;
        }

        .main-header {
            background-color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            padding: 20px 0;
            position: fixed;
            top: 30px;
            width: 100%;
            z-index: 1050;
        }

        .search-bar {
            background: #f1f3f4;
            border-radius: 8px;
            padding: 2px;
            display: flex;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .search-bar:focus-within {
            background: white;
            border-color: var(--primary-blue);
        }

        .search-input {
            border: none;
            background: transparent;
            width: 100%;
            padding: 10px 15px;
            outline: none;
        }

        .search-btn {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 0 25px;
            border-radius: 6px;
            margin: 2px;
            font-weight: 600;
        }

        .cart-icon {
            font-size: 1.6rem;
            color: var(--primary-blue);
            position: relative;
            cursor: pointer;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background: var(--primary-red);
            color: white;
            font-size: 0.7rem;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* BANNER SLIDER */
        .banner-slider {
            margin-bottom: 25px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .banner-item img {
            width: 100%;
            height: 380px;
            object-fit: cover;
        }

        /* CATEGORY ICONS */
        .category-grid {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            overflow-x: auto;
            gap: 25px;
            scrollbar-width: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .category-grid::-webkit-scrollbar { display: none; }

        .cat-item {
            text-align: center;
            text-decoration: none;
            color: var(--text-dark);
            min-width: 90px;
            transition: all 0.2s;
        }

        .cat-item:hover { transform: translateY(-3px); color: var(--primary-red); }

        .cat-icon {
            width: 60px;
            height: 60px;
            background: #e3f2fd;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            color: var(--primary-blue);
            font-size: 1.6rem;
            border: 1px solid #bbdefb;
            transition: all 0.2s;
        }

        .cat-item:hover .cat-icon { background: var(--primary-blue); color: white; }

        /* FLASH SALE SECTION */
        .flash-sale-section {
            background: white;
            border-radius: 12px;
            margin-bottom: 25px;
            overflow: hidden;
            border-top: 4px solid var(--primary-red);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .fs-header {
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 20px;
            background: linear-gradient(to right, #fff, #fff5f5);
        }

        .fs-title {
            color: var(--primary-red);
            font-weight: 800;
            font-size: 1.4rem;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .fs-timer { display: flex; gap: 8px; }
        .timer-box {
            background: var(--primary-red);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 1rem;
        }

        .fs-slider { padding: 10px 20px; }
        .fs-card { padding: 10px; text-align: center; text-decoration: none; color: inherit; display: block; }
        .fs-img-wrap { position: relative; padding-top: 100%; border-radius: 8px; overflow: hidden; margin-bottom: 12px; background: #f9f9f9; }
        .fs-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; padding: 5px; }
        .fs-price { color: var(--primary-red); font-weight: 700; font-size: 1.1rem; }
        .fs-discount-tag {
            position: absolute;
            top: 0; right: 0;
            background: var(--primary-red);
            color: white;
            font-size: 0.75rem;
            padding: 3px 8px;
            font-weight: 700;
            border-bottom-left-radius: 8px;
        }

        /* SECTION TITLE */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px 20px;
            background: white;
            border-radius: 12px 12px 0 0;
            border-left: 5px solid var(--primary-blue);
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

        .section-title { font-size: 1.2rem; font-weight: 700; color: var(--primary-blue); text-transform: uppercase; }

        /* PRODUCT CARDS */
        .product-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #eee;
            transition: all 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            cursor: pointer;
        }

        .product-card:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }

        .card-img-wrap { position: relative; padding-top: 100%; background: #f9f9f9; }
        .card-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; padding: 10px; }

        .card-content { padding: 15px; flex-grow: 1; }
        .card-title {
            font-size: 0.9rem; font-weight: 500; height: 2.6rem; overflow: hidden;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            margin-bottom: 10px; color: #333;
        }

        .card-price { color: var(--primary-blue); font-weight: 700; font-size: 1.1rem; }
        .card-footer-info { display: flex; justify-content: space-between; font-size: 0.75rem; color: #78909c; margin-top: 12px; }

        /* LOAD MORE */
        .load-more-container { margin: 40px 0 80px; text-align: center; }
        .btn-load-more {
            background: white;
            border: 2px solid var(--primary-blue);
            padding: 12px 60px;
            color: var(--primary-blue);
            font-weight: 700;
            border-radius: 30px;
            transition: all 0.3s;
        }

        .btn-load-more:hover { background: var(--primary-blue); color: white; }

        @media (max-width: 768px) {
            body { padding-top: 140px; }
            .top-nav { display: none; }
            .main-header { top: 0; padding: 15px 0; }
            .banner-item img { height: 200px; }
            .fs-title { font-size: 1.1rem; }
            .category-grid { padding: 15px; gap: 15px; }
            .cat-icon { width: 50px; height: 50px; font-size: 1.3rem; }
        }
    </style>
</head>
<body>

    <!-- TOP NAV -->
    <nav class="top-nav">
        <div class="container d-flex justify-content-between">
            <div>
                <a href="#" class="text-white text-decoration-none me-3"><i class="fas fa-store me-1"></i> Seller Centre</a>
                <a href="#" class="text-white text-decoration-none"><i class="fas fa-mobile-alt me-1"></i> Download App</a>
            </div>
            <div>
                @if(session()->has('customer_id'))
                    <a href="{{ route('cat-orders', ['id' => $RecOID]) }}" class="text-white text-decoration-none fw-bold me-3"><i class="fas fa-box me-1"></i> PESANAN SAYA</a>
                    <a href="#" class="text-white text-decoration-none fw-bold"><i class="fas fa-user-circle me-1"></i> {{ session('customer_name') }}</a>
                @else
                    <a href="#" class="text-white text-decoration-none me-3 fw-bold" onclick="showLoginModal()">LOGIN MEMBER</a>
                    <a href="#" class="text-white text-decoration-none fw-bold" onclick="showRegisterModal()">DAFTAR</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- HEADER -->
    <header class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6 col-md-3 mb-3 mb-md-0">
                    <a href="#" class="text-decoration-none d-flex align-items-center gap-2">
                        @if(!empty($company[0]['icon']))
                            <img src="{{ $company[0]['icon'] }}" height="45" alt="Logo">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                        @endif
                        <span class="fs-4 fw-bold" style="color: var(--primary-blue)">{{ $company[0]['NamaPartner'] }}</span>
                    </a>
                </div>
                <div class="col-12 col-md-7 order-3 order-md-2">
                    <div class="search-bar">
                        <input type="text" id="searchInput" class="search-input" placeholder="Cari produk impian Anda di sini...">
                        <button class="search-btn"><i class="fas fa-search me-2"></i> CARI</button>
                    </div>
                </div>
                <div class="col-6 col-md-2 order-2 order-md-3 text-end d-flex align-items-center justify-content-end gap-3">
                    @if(session()->has('customer_id'))
                        <a href="{{ route('cat-orders', ['id' => $RecOID]) }}" class="cart-icon d-inline-block" title="Pesanan Saya" style="color: var(--primary-blue)">
                            <i class="fas fa-box"></i>
                        </a>
                    @endif
                    <div class="cart-icon d-inline-block">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge" id="cartCount">0</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container mt-3">
        <!-- Banners -->
        <div class="banner-slider" id="bannerSection">
            <div class="banner-item"><img src="{{ $company[0]['Banner1'] ?: 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80' }}" alt="Banner 1"></div>
            @if(!empty($company[0]['Banner2'])) <div class="banner-item"><img src="{{ $company[0]['Banner2'] }}" alt="Banner 2"></div> @endif
        </div>

        <!-- Flash Sale -->
        @if(count($flashSales) > 0)
        <div class="flash-sale-section" id="flashSaleSection">
            <div class="fs-header">
                <div class="fs-title"><i class="fas fa-bolt animate__animated animate__flash animate__infinite"></i> FLASH SALE</div>
                <div class="fs-timer" id="flashTimer">
                    <div class="timer-box" id="timer-h">00</div>
                    <div class="timer-box" id="timer-m">00</div>
                    <div class="timer-box" id="timer-s">00</div>
                </div>
            </div>
            <div class="fs-slider">
                @foreach($flashSales as $fs)
                @php
                    $hargaAsli = $fs->HargaJual > 0 ? $fs->HargaJual : 0;
                    $hargaDiskon = $fs->FlashSalePrice > 0 ? $fs->FlashSalePrice : $hargaAsli * 0.8;
                    if ($hargaDiskon >= $hargaAsli) {
                        $hargaDiskon = $hargaAsli * 0.8; // Paksa diskon 20% jika input harga diskon salah
                    }
                    $cartItemFS = [
                        'KodeItem' => $fs->KodeItem ?? '',
                        'NamaItem' => $fs->NamaItem ?? 'Flash Sale Item',
                        'Gambar' => $fs->Gambar ?? '',
                        'HargaJual' => $hargaDiskon
                    ];
                @endphp
                <a href="javascript:void(0)" class="fs-card" onclick="addToCart({{ json_encode($cartItemFS) }})">
                    <div class="fs-img-wrap">
                        <img src="{{ $fs->Gambar ?: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80' }}" class="fs-img" alt="">
                        <div class="fs-discount-tag">SPECIAL PRICE</div>
                    </div>
                    <div class="fs-price">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</div>
                    <div class="small text-muted text-decoration-line-through">Rp {{ number_format($hargaAsli, 0, ',', '.') }}</div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Best Sellers -->
        @if(count($bestSellers) > 0)
        <div class="section-header" id="bestSellerHeader">
            <div class="section-title"><i class="fas fa-fire me-2 text-danger"></i> Produk Terlaris</div>
        </div>
        <div class="best-seller-slider mb-4" id="bestSellerSection">
            @foreach($bestSellers as $bs)
            @php
                $cartItemBS = [
                    'KodeItem' => $bs->KodeItem ?? '',
                    'NamaItem' => $bs->NamaItem ?? 'Best Seller Item',
                    'Gambar' => $bs->Gambar ?? '',
                    'HargaJual' => $bs->HargaJual ?? 0
                ];
            @endphp
            <div class="p-2">
                <div class="product-card" onclick="addToCart({{ json_encode($cartItemBS) }})">
                    <div class="card-img-wrap">
                        <img src="{{ $bs->Gambar ?: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80' }}" class="card-img">
                    </div>
                    <div class="card-content">
                        <div class="card-title text-center">{{ $bs->NamaItem }}</div>
                        <div class="card-price text-center">Rp {{ number_format($bs->HargaJual, 0, ',', '.') }}</div>
                        <div class="card-footer-info">
                            <span><i class="fas fa-star text-warning"></i> 5.0</span>
                            <span>Terjual 1rb+</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Category Icons -->
        <div class="category-grid" id="categoryIcons">
            <a href="#" class="cat-item active" data-kode="">
                <div class="cat-icon"><i class="fas fa-th-large"></i></div>
                <div class="cat-name">Semua Produk</div>
            </a>
            @foreach ($jenisitem as $item)
                <a href="#" class="cat-item" data-kode="{{ $item->KodeJenis }}">
                    <div class="cat-icon"><i class="fas fa-tag"></i></div>
                    <div class="cat-name">{{ $item->NamaJenis }}</div>
                </a>
            @endforeach
        </div>

        <!-- Recommendation Section -->
        <div class="section-header" id="rekomendasiHeader">
            <div class="section-title" id="rekomendasiTitle"><i class="fas fa-thumbs-up me-2"></i> Rekomendasi Untuk Anda</div>
        </div>

        <div id="productGrid" class="row g-3 product-grid">
            <div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>
        </div>

        <div class="load-more-container d-none" id="loadMoreSection">
            <button class="btn btn-load-more" id="btnLoadMore">LIHAT LEBIH BANYAK PRODUK</button>
        </div>
    </main>

    <footer class="mt-5 py-5 border-top bg-white text-start">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold mb-3" style="color: var(--primary-blue)">KONTAK KAMI</h6>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-3"><i class="fas fa-map-marker-alt me-2 text-danger"></i> {{ $company[0]['Alamat'] }}</li>
                        <li class="mb-3"><i class="fas fa-phone-alt me-2 text-success"></i> {{ $company[0]['NoTlp1'] }}</li>
                        <li class="mb-3"><i class="fas fa-envelope me-2 text-primary"></i> {{ $company[0]['Email'] }}</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold mb-3" style="color: var(--primary-blue)">MEDIA SOSIAL</h6>
                    <div class="d-flex gap-3 fs-3 mt-2">
                        @if(!empty($company[0]['FB']))
                            <a href="{{ $company[0]['FB'] }}" target="_blank" style="color: #3b5998"><i class="fab fa-facebook"></i></a>
                        @endif
                        @if(!empty($company[0]['Instagram']))
                            <a href="{{ $company[0]['Instagram'] }}" target="_blank" style="color: #e4405f"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(!empty($company[0]['Twitter']))
                            <a href="{{ $company[0]['Twitter'] }}" target="_blank" style="color: #1da1f2"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if(!empty($company[0]['Youtube']))
                            <a href="{{ $company[0]['Youtube'] }}" target="_blank" style="color: #cd201f"><i class="fab fa-youtube"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold mb-3" style="color: var(--primary-blue)">TENTANG KAMI</h6>
                    <p class="small text-muted">
                        {{ $company[0]['NamaPartner'] }} adalah solusi terpercaya untuk kebutuhan belanja Anda. Kami mengutamakan kualitas dan kepuasan pelanggan.
                    </p>
                    @if(!empty($company[0]['Website']))
                        <a href="{{ $company[0]['Website'] }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-4">Kunjungi Website</a>
                    @endif
                </div>
            </div>
            <hr>
            <div class="text-center text-muted small">
                &copy; {{ date('Y') }} {{ $company[0]['NamaPartner'] }}. Official Marketplace Platform.
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold" style="color: var(--primary-blue)">LOGIN MEMBER</h4>
                        <p class="small text-muted">Masuk untuk pengalaman belanja lebih baik</p>
                    </div>
                    <form id="loginForm">
                        @csrf
                        <input type="hidden" name="RecordOwnerID" value="{{ $RecOID }}">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email / No. HP</label>
                            <input type="text" name="identifier" class="form-control form-control-lg" placeholder="Masukkan ID Anda" style="border-radius: 10px;" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Masukkan Password" style="border-radius: 10px;" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow" style="border-radius: 10px; background: var(--primary-blue)">MASUK SEKARANG</button>
                    </form>
                    <div class="text-center mt-4 small">
                        Belum punya akun? <a href="#" onclick="showRegisterModal(); return false;" class="text-decoration-none fw-bold" style="color: var(--primary-red)">Daftar Member</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold" style="color: var(--primary-blue)">DAFTAR MEMBER</h4>
                        <p class="small text-muted">Buat akun untuk pengalaman belanja lebih baik</p>
                    </div>
                    <form id="registerForm">
                        @csrf
                        <input type="hidden" name="RecordOwnerID" value="{{ $RecOID }}">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control form-control-lg" placeholder="Masukkan Nama Anda" style="border-radius: 10px;" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email / No. HP</label>
                            <input type="text" name="identifier" class="form-control form-control-lg" placeholder="Masukkan ID Anda" style="border-radius: 10px;" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Buat Password" style="border-radius: 10px;" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow" style="border-radius: 10px; background: var(--primary-blue)">DAFTAR SEKARANG</button>
                    </form>
                    <div class="text-center mt-4 small">
                        Sudah punya akun? <a href="#" onclick="showLoginModal(); return false;" class="text-decoration-none fw-bold" style="color: var(--primary-red)">Login Member</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="p-3 d-flex justify-content-between align-items-center border-bottom bg-white sticky-top">
            <h6 class="fw-bold mb-0">Keranjang Belanja (<span id="cartSideCount">0</span>)</h6>
            <button class="btn-close" onclick="toggleCart()"></button>
        </div>
        <div class="cart-items-container p-3" id="cartItemsList">
            <!-- Items will be injected here -->
            <div class="text-center py-5 text-muted">
                <i class="fas fa-shopping-basket fs-1 mb-3 opacity-25"></i>
                <p>Keranjang masih kosong</p>
            </div>
        </div>
        <div class="cart-footer p-3 border-top bg-white">
            <div class="d-flex justify-content-between mb-3 fw-bold">
                <span>Total Estimasi:</span>
                <span class="text-danger" id="cartTotalPrice">Rp 0</span>
            </div>
            <button class="btn btn-primary w-100 py-2 fw-bold" onclick="proceedToCheckout()" id="btnCheckout" disabled>CHECKOUT SEKARANG</button>
        </div>
    </div>
    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(!empty($midtransclientkey))
        <script src="{{ config('midtrans.is_production', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $midtransclientkey }}"></script>
    @endif

    <style>
        .cart-sidebar {
            position: fixed; top: 0; right: -400px; width: 400px; height: 100%;
            background: white; z-index: 2000; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: -5px 0 25px rgba(0,0,0,0.1);
            display: flex; flex-direction: column;
        }
        .cart-sidebar.open { right: 0; }
        .cart-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 1999; display: none;
        }
        .cart-items-container { flex-grow: 1; overflow-y: auto; }
        .cart-item-row { display: flex; gap: 15px; margin-bottom: 20px; align-items: center; }
        .cart-item-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        .cart-item-info { flex-grow: 1; }
        .cart-item-title { font-size: 0.85rem; font-weight: 500; margin-bottom: 5px; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .cart-item-price { color: var(--primary-red); font-weight: 700; font-size: 0.9rem; }
        .qty-control { display: flex; align-items: center; gap: 10px; border: 1px solid #ddd; border-radius: 5px; padding: 2px 8px; width: fit-content; }
        .qty-btn { cursor: pointer; color: var(--primary-blue); font-weight: bold; }
    </style>

    <script>
        $(document).ready(function() {
            let baseData = [];
            let allData = [];
            let cart = JSON.parse(localStorage.getItem('cart_retail')) || [];
            let itemsPerPage = 12;
            let currentOffset = 0;
            let currentROID = "{{ $RecOID }}";
            let defaultPlaceholder = "https://images.unsplash.com/photo-1560343090-f0409e92791a?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80";

            updateCartUI();

            // Sliders
            $('.banner-slider').slick({ dots: true, autoplay: true, autoplaySpeed: 4000, arrows: false, fade: true });
            $('.fs-slider').slick({ slidesToShow: 5, slidesToScroll: 2, arrows: true, responsive: [{ breakpoint: 1024, settings: { slidesToShow: 3 } }, { breakpoint: 768, settings: { slidesToShow: 2 } }] });
            $('.best-seller-slider').slick({ slidesToShow: 6, slidesToScroll: 2, arrows: true, responsive: [{ breakpoint: 1024, settings: { slidesToShow: 4 } }, { breakpoint: 768, settings: { slidesToShow: 2 } }] });

            function startTimer() {
                let fsUntil = "{{ count($flashSales) > 0 ? $flashSales[0]->FlashSaleUntil : '' }}";
                let endTime = fsUntil ? new Date(fsUntil).getTime() : new Date().getTime() + (3 * 60 * 60 * 1000);
                setInterval(function() {
                    let now = new Date().getTime();
                    let distance = endTime - now;
                    if (distance < 0) return;
                    $('#timer-h').text(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0'));
                    $('#timer-m').text(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0'));
                    $('#timer-s').text(Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0'));
                }, 1000);
            }
            startTimer();

            fetchProducts("");

            $('.cat-item').on('click', function(e) {
                e.preventDefault();
                $('.cat-item').removeClass('active');
                $(this).addClass('active');
                fetchProducts($(this).data('kode'));
            });

            $('#btnLoadMore').on('click', function() { renderBatch(); });

            function fetchProducts(kodeJenis) {
                $('#productGrid').html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('cat-itemmaster') }}",
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: { 'KodeJenis': kodeJenis, 'RecordOwnerID': currentROID, 'Active': 'Y' },
                    success: function(response) {
                        baseData = response.data;
                        applySearchAndRender();
                    }
                });
            }

            function applySearchAndRender() {
                let keyword = $('#searchInput').val().toLowerCase();
                let activeKategori = $('.cat-item.active').data('kode');
                
                if (keyword || activeKategori) {
                    $('#bannerSection, #flashSaleSection, #bestSellerHeader, #bestSellerSection').slideUp(300);
                    if (keyword) {
                        $('#rekomendasiTitle').html('<i class="fas fa-search me-2"></i> Hasil Pencarian: "' + keyword + '"');
                    } else {
                        $('#rekomendasiTitle').html('<i class="fas fa-tags me-2"></i> Kategori Pilihan');
                    }
                } else {
                    $('#bannerSection, #flashSaleSection, #bestSellerHeader, #bestSellerSection').slideDown(300);
                    $('#rekomendasiTitle').html('<i class="fas fa-thumbs-up me-2"></i> Rekomendasi Untuk Anda');
                }

                if (keyword) {
                    allData = baseData.filter(item => (item.NamaItem || '').toLowerCase().includes(keyword));
                } else {
                    allData = [...baseData];
                }
                
                currentOffset = 0;
                $('#productGrid').empty();
                
                if(allData.length === 0) {
                    $('#productGrid').html('<div class="col-12 text-center py-5 text-muted"><i class="fas fa-box-open fs-1 mb-3 opacity-50"></i><p>Produk tidak ditemukan.</p></div>');
                    $('#loadMoreSection').addClass('d-none');
                } else {
                    renderBatch();
                }
            }

            // Event Listeners for Search Bar
            $('#searchInput').on('keyup', function(e) {
                if (e.key === 'Enter') applySearchAndRender();
            });
            // Auto search when typing (debounce simulation)
            $('#searchInput').on('input', function() {
                applySearchAndRender();
            });
            $('.search-btn').on('click', function() {
                applySearchAndRender();
            });

            function renderBatch() {
                const nextBatch = allData.slice(currentOffset, currentOffset + itemsPerPage);
                let html = '';
                nextBatch.forEach(item => {
                    const imgSrc = item.Gambar ? item.Gambar : defaultPlaceholder;
                    const priceFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(item.HargaJual);
                    html += `
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <div class="product-card" onclick="addToCartFromElement(this)" data-item="${JSON.stringify(item).replace(/"/g, '&quot;')}">
                                <div class="card-img-wrap"><img src="${imgSrc}" class="card-img" onerror="this.src='${defaultPlaceholder}'"></div>
                                <div class="card-content">
                                    <div class="card-title">${item.NamaItem}</div>
                                    <div class="card-price">${priceFormatted}</div>
                                    <div class="card-footer-info"><span>⭐ 4.9</span><span>Terjual 500+</span></div>
                                    <button class="btn btn-sm btn-outline-primary w-100 mt-2 rounded-pill shadow-sm">Beli Sekarang</button>
                                </div>
                            </div>
                        </div>`;
                });
                $('#productGrid').append(html);
                currentOffset += itemsPerPage;
                if (currentOffset < allData.length) $('#loadMoreSection').removeClass('d-none');
                else $('#loadMoreSection').addClass('d-none');
            }

            // Safe cart logic handler from HTML elements
            window.addToCartFromElement = function(el) {
                try {
                    let item = JSON.parse($(el).attr('data-item'));
                    addToCart(item);
                } catch(e) {
                    console.error("Gagal memproses item:", e);
                }
            };

            // Cart Logic
            window.addToCart = function(item) {
                let existing = cart.find(i => i.KodeItem === item.KodeItem);
                if (existing) {
                    existing.qty += 1;
                } else {
                    cart.push({ ...item, qty: 1 });
                }
                saveCart();
                updateCartUI();
                Swal.fire({
                    title: 'Berhasil!',
                    text: `${item.NamaItem} ditambah ke keranjang`,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            };

            window.updateQty = function(kode, change) {
                let item = cart.find(i => i.KodeItem === kode);
                if (item) {
                    item.qty += change;
                    if (item.qty <= 0) cart = cart.filter(i => i.KodeItem !== kode);
                    saveCart();
                    updateCartUI();
                }
            };

            function saveCart() { localStorage.setItem('cart_retail', JSON.stringify(cart)); }

            function updateCartUI() {
                $('#cartCount, #cartSideCount').text(cart.reduce((a, b) => a + b.qty, 0));
                let html = '';
                let total = 0;

                if (cart.length === 0) {
                    html = '<div class="text-center py-5 text-muted"><i class="fas fa-shopping-basket fs-1 mb-3 opacity-25"></i><p>Keranjang masih kosong</p></div>';
                    $('#btnCheckout').prop('disabled', true);
                } else {
                    cart.forEach(item => {
                        const price = item.HargaJual * item.qty;
                        total += price;
                        html += `
                            <div class="cart-item-row animate__animated animate__fadeIn">
                                <img src="${item.Gambar || defaultPlaceholder}" class="cart-item-img">
                                <div class="cart-item-info">
                                    <div class="cart-item-title">${item.NamaItem}</div>
                                    <div class="cart-item-price">Rp ${new Intl.NumberFormat('id-ID').format(item.HargaJual)}</div>
                                    <div class="qty-control mt-2">
                                        <span class="qty-btn" onclick="updateQty('${item.KodeItem}', -1)">-</span>
                                        <span class="fw-bold">${item.qty}</span>
                                        <span class="qty-btn" onclick="updateQty('${item.KodeItem}', 1)">+</span>
                                    </div>
                                </div>
                            </div>`;
                    });
                    $('#btnCheckout').prop('disabled', false);
                }
                $('#cartItemsList').html(html);
                $('#cartTotalPrice').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
            }

            window.toggleCart = function() {
                $('#cartSidebar').toggleClass('open');
                $('#cartOverlay').fadeToggle();
            };

            $('.cart-icon').on('click', toggleCart);

            window.proceedToCheckout = function() {
                @if(session()->has('customer_id'))
                    if (cart.length === 0) {
                        Swal.fire('Keranjang Kosong', 'Silakan pilih produk terlebih dahulu.', 'warning');
                        return;
                    }
                    
                    let btn = $('#btnCheckout');
                    let originalText = btn.html();
                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses...');
                    
                    let totalPrice = cart.reduce((sum, item) => sum + (item.HargaJual * item.qty), 0);
                    
                    $.ajax({
                        url: "{{ route('cat-checkout') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            RecordOwnerID: currentROID,
                            cart: cart,
                            total: totalPrice
                        },
                        success: function(res) {
                            if (res.success) {
                                if (typeof snap !== 'undefined') {
                                    snap.pay(res.snap_token, {
                                        onSuccess: function(result) {
                                            Swal.fire('Berhasil!', 'Pembayaran telah diterima.', 'success').then(() => {
                                                cart = []; saveCart(); updateCartUI();
                                                window.location.href = "{{ url('cat/'.$RecOID.'/status') }}/" + res.order_id;
                                            });
                                        },
                                        onPending: function(result) {
                                            Swal.fire('Menunggu', 'Menunggu pembayaran diselesaikan.', 'info').then(() => {
                                                cart = []; saveCart(); updateCartUI();
                                                window.location.href = "{{ url('cat/'.$RecOID.'/status') }}/" + res.order_id;
                                            });
                                        },
                                        onError: function(result) {
                                            Swal.fire('Gagal', 'Pembayaran gagal diproses.', 'error');
                                            btn.prop('disabled', false).html(originalText);
                                        },
                                        onClose: function() {
                                            Swal.fire('Batal', 'Anda menutup halaman pembayaran.', 'warning');
                                            btn.prop('disabled', false).html(originalText);
                                        }
                                    });
                                } else {
                                    Swal.fire('Error Konfigurasi', 'Sistem pembayaran belum siap (Snap.js tidak ada).', 'error');
                                    btn.prop('disabled', false).html(originalText);
                                }
                            } else {
                                Swal.fire('Gagal!', res.message, 'error');
                                btn.prop('disabled', false).html(originalText);
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
                            btn.prop('disabled', false).html(originalText);
                        }
                    });
                @else
                    toggleCart();
                    showLoginModal();
                    Swal.fire('Silakan Login', 'Anda harus login member dulu untuk melakukan pemesanan.', 'warning');
                @endif
            };

            window.showLoginModal = function() { 
                $('#registerModal').modal('hide');
                new bootstrap.Modal(document.getElementById('loginModal')).show(); 
            };
            window.showRegisterModal = function() { 
                $('#loginModal').modal('hide');
                new bootstrap.Modal(document.getElementById('registerModal')).show(); 
            };

            $('#loginForm').submit(function(e) {
                e.preventDefault();
                let btn = $(this).find('button[type="submit"]');
                let originalText = btn.text();
                btn.prop('disabled', true).text('Memproses...');
                
                $.ajax({
                    url: "{{ route('cat-login') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if(res.success) {
                            Swal.fire('Berhasil!', 'Login sukses, memuat ulang...', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                            btn.prop('disabled', false).text(originalText);
                        }
                    },
                    error: function(err) {
                        Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
                        btn.prop('disabled', false).text(originalText);
                    }
                });
            });

            $('#registerForm').submit(function(e) {
                e.preventDefault();
                let btn = $(this).find('button[type="submit"]');
                let originalText = btn.text();
                btn.prop('disabled', true).text('Memproses...');
                
                $.ajax({
                    url: "{{ route('cat-register') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if(res.success) {
                            Swal.fire('Berhasil!', 'Pendaftaran sukses, memuat ulang...', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                            btn.prop('disabled', false).text(originalText);
                        }
                    },
                    error: function(err) {
                        Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
                        btn.prop('disabled', false).text(originalText);
                    }
                });
            });
        });
    </script>
</body>
</html>