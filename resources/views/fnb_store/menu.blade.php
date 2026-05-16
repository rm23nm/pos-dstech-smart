<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - {{ $company->NamaPartner ?? 'FnB Store' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff4757;
            --primary-light: #ff6b81;
            --dark: #2f3542;
            --light: #f1f2f6;
            --success: #2ed573;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8f9fa;
            color: var(--dark);
            padding-bottom: 90px; /* Space for bottom bar */
        }

        /* Premium Header */
        .header-store {
            background: white;
            padding: 20px 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .user-welcome {
            font-size: 0.85rem;
            color: #747d8c;
        }

        .store-name {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--dark);
        }

        /* Category Scroll */
        .category-scroll {
            display: flex;
            overflow-x: auto;
            padding: 15px;
            background: white;
            white-space: nowrap;
            gap: 12px;
            scrollbar-width: none;
            position: sticky;
            top: 78px;
            z-index: 999;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
        }

        .category-scroll::-webkit-scrollbar { display: none; }

        .category-pill {
            padding: 8px 20px;
            background: #f1f2f6;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #57606f;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid transparent;
        }

        .category-pill.active {
            background: var(--dark);
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Menu Cards */
        .menu-item {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            transition: transform 0.3s;
            height: 100%;
            border: 1px solid #f1f2f6;
        }

        .menu-item:hover {
            transform: translateY(-5px);
        }

        .menu-img {
            height: 140px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .menu-info {
            padding: 15px;
        }

        .menu-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu-desc {
            font-size: 0.75rem;
            color: #747d8c;
            height: 35px;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .menu-price {
            font-weight: 800;
            color: var(--primary);
            font-size: 1.1rem;
        }

        /* Quantity Control */
        .qty-control {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f1f2f6;
            border-radius: 12px;
            padding: 4px;
        }

        .btn-qty {
            width: 32px;
            height: 32px;
            background: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            color: var(--dark);
        }

        .btn-qty:active { transform: scale(0.9); }

        .qty-val {
            font-weight: 700;
            margin: 0 10px;
        }

        /* Bottom Bar */
        .bottom-checkout {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 15px 20px;
            box-shadow: 0 -5px 25px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1001;
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }

        .total-label { font-size: 0.8rem; color: #747d8c; }
        .total-val { font-size: 1.3rem; font-weight: 800; color: var(--dark); display: block; }

        .btn-checkout-main {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 15px;
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(255, 71, 87, 0.3);
            transition: all 0.3s;
        }

        .btn-checkout-main:disabled {
            background: #ced4da;
            box-shadow: none;
        }

        /* Modal Customization */
        .modal-content { border-radius: 25px; border: none; }
        .modal-header { border-bottom: none; padding: 25px 25px 10px; }
        .modal-body { padding: 10px 25px 25px; }

        .cart-item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed #f1f2f6;
        }

        .payment-card {
            border: 2px solid #f1f2f6;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .payment-card.active {
            border-color: var(--primary);
            background: #fffafa;
        }

        .payment-card i { font-size: 1.5rem; margin-right: 15px; color: var(--primary); }

        @media (min-width: 992px) {
            .container { max-width: 900px; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header-store">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <span class="user-welcome">Halo, <strong>{{ session('customer_name') }}</strong></span>
                <div class="store-name">{{ $company->NamaPartner }}</div>
            </div>
            <form action="{{ $id ? route('fnb-store.logout', ['id' => $id]) : route('fnb-store.logout.custom') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="category-scroll">
        <div class="category-pill active" onclick="filterCategory('all', this)">Semua</div>
        @foreach(collect($menus)->pluck('category')->unique() as $cat)
            <div class="category-pill" onclick="filterCategory('{{ \Illuminate\Support\Str::slug($cat) }}', this)">{{ $cat }}</div>
        @endforeach
    </div>

    <!-- Menu Content -->
    <div class="container mt-4">
        <div class="row g-3">
            @foreach($menus as $menu)
                <div class="col-6 col-md-4 menu-item-container" data-category="{{ \Illuminate\Support\Str::slug($menu['category']) }}">
                    <div class="menu-item">
                        <div class="menu-img" style="background-image: url('{{ $menu['image'] }}');"></div>
                        <div class="menu-info">
                            <div class="menu-title">{{ $menu['name'] }}</div>
                            <div class="menu-desc">{{ $menu['description'] }}</div>
                            <div class="d-flex flex-column gap-2 mt-auto">
                                <div class="menu-price">Rp {{ number_format($menu['price'], 0, ',', '.') }}</div>
                                <div class="qty-control" 
                                     data-id="{{ $menu['id'] }}" 
                                     data-name="{{ $menu['name'] }}" 
                                     data-price="{{ $menu['price'] }}">
                                    <button class="btn-qty" onclick="updateQty(this, -1)">-</button>
                                    <span class="qty-val">0</span>
                                    <button class="btn-qty" onclick="updateQty(this, 1)">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="bottom-checkout">
        <div class="total-info">
            <span class="total-label">Total Pembelian</span>
            <span class="total-val" id="displayTotal">Rp 0</span>
        </div>
        <button class="btn-checkout-main" id="btnCheckout" disabled onclick="openCheckoutModal()">
            CHECKOUT <i class="fas fa-arrow-right ms-2"></i>
        </button>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="fw-bold">Ringkasan Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="cartItemsList" class="mb-4"></div>
                    
                    <div class="d-flex justify-content-between fw-bold h5 mb-4 pt-3 border-top">
                        <span>Total Bayar</span>
                        <span class="text-primary" id="modalTotal">Rp 0</span>
                    </div>

                    <h6 class="fw-bold mb-3">Metode Pembayaran Online</h6>
                    @foreach($paymentMethods as $pm)
                        <div class="payment-card active" onclick="selectPayment('{{ $pm->id }}', this)">
                            <i class="fas fa-qrcode"></i>
                            <div>
                                <div class="fw-bold">{{ $pm->NamaMetodePembayaran }}</div>
                                <div class="small text-muted">Otomatis & Terverifikasi</div>
                            </div>
                        </div>
                    @endforeach

                    <button class="btn btn-premium w-100 py-3 mt-3 btn-checkout-main" onclick="processPayment()">
                        BAYAR SEKARANG
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @if(env('MIDTRANS_IS_PRODUCTION', 'false') == 'true')
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $midtransclientkey }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransclientkey }}"></script>
    @endif

    <script>
        let cart = {};
        let selectedPaymentId = '{{ $paymentMethods[0]->id ?? "" }}';

        function filterCategory(slug, btn) {
            $('.category-pill').removeClass('active');
            $(btn).addClass('active');

            if(slug === 'all') {
                $('.menu-item-container').fadeIn();
            } else {
                $('.menu-item-container').hide();
                $(`.menu-item-container[data-category="${slug}"]`).fadeIn();
            }
        }

        function updateQty(btn, change) {
            const container = $(btn).closest('.qty-control');
            const id = container.data('id');
            const name = container.data('name');
            const price = parseInt(container.data('price'));
            const qtySpan = container.find('.qty-val');

            let currentQty = parseInt(qtySpan.text());
            let newQty = currentQty + change;
            if(newQty < 0) newQty = 0;

            qtySpan.text(newQty);

            if(newQty > 0) {
                cart[id] = { id, name, price, qty: newQty };
            } else {
                delete cart[id];
            }

            updateBottomBar();
        }

        function updateBottomBar() {
            let total = 0;
            let count = 0;
            for(let id in cart) {
                total += cart[id].price * cart[id].qty;
                count += cart[id].qty;
            }

            $('#displayTotal').text('Rp ' + total.toLocaleString('id-ID'));
            $('#btnCheckout').prop('disabled', count === 0);
        }

        function openCheckoutModal() {
            let total = 0;
            const list = $('#cartItemsList');
            list.empty();

            for(let id in cart) {
                let item = cart[id];
                total += item.price * item.qty;
                list.append(`
                    <div class="cart-item-row">
                        <div>
                            <div class="fw-bold">${item.name}</div>
                            <div class="small text-muted">${item.qty} x Rp ${item.price.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="fw-bold">Rp ${(item.qty * item.price).toLocaleString('id-ID')}</div>
                    </div>
                `);
            }

            $('#modalTotal').text('Rp ' + total.toLocaleString('id-ID'));
            $('#checkoutModal').modal('show');
        }

        function selectPayment(id, el) {
            selectedPaymentId = id;
            $('.payment-card').removeClass('active');
            $(el).addClass('active');
        }

        function processPayment() {
            const btn = $('.btn-checkout-main');
            btn.prop('disabled', true).text('MEMPROSES...');

            // Implementasi Midtrans Ajax ke Controller
            Swal.fire({
                title: 'Mohon Tunggu',
                text: 'Menyiapkan gerbang pembayaran...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // Simulasi panggil controller (akan saya implementasi fungsinya di Controller setelah ini)
            $.ajax({
                url: '{{ $id ? route("fnb-store.checkout", ["id" => $id]) : route("fnb-store.checkout.custom") }}',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    cart: cart,
                    payment_id: selectedPaymentId,
                    total: calculateTotal()
                },
                success: function(res) {
                    Swal.close();
                    if(res.snap_token) {
                        snap.pay(res.snap_token, {
                            onSuccess: function(result) { window.location.href = '{{ $id ? "/fnb-store/$id" : "" }}/status/' + res.order_id; },
                            onPending: function(result) { window.location.href = '{{ $id ? "/fnb-store/$id" : "" }}/status/' + res.order_id; },
                            onError: function(result) { Swal.fire('Error', 'Pembayaran gagal', 'error'); btn.prop('disabled', false).text('BAYAR SEKARANG'); }
                        });
                    } else {
                        Swal.fire('Error', res.message || 'Gagal menyiapkan pembayaran', 'error');
                        btn.prop('disabled', false).text('BAYAR SEKARANG');
                    }
                }
            });
        }

        function calculateTotal() {
            let t = 0;
            for(let id in cart) t += cart[id].price * cart[id].qty;
            return t;
        }
    </script>
</body>
</html>
