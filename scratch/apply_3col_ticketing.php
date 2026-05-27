<?php

$file = __DIR__ . '/../resources/views/Transaksi/Penjualan/PoS/TicketingPoS.blade.php';
$content = file_get_contents($file);

// Restructure CSS
$newCss = <<<CSS
<style>
    body { background-color: #f8fafc; font-family: 'Inter', sans-serif; margin: 0; overflow: hidden; }
    .pos-wrapper {
        display: flex;
        height: 100vh;
        background-color: #e2e8f0;
        padding: 10px;
        gap: 10px;
    }
    .col-left, .col-mid, .col-right {
        background: #fff;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .col-left { flex: 4.5; }
    .col-mid { flex: 3; }
    .col-right { flex: 3; }

    /* Left Col */
    .header-panel { padding: 15px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .item-grid-container { flex: 1; overflow-y: auto; padding: 20px; }
    .item-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
    }
    .item-card {
        background: #fff; border-radius: 12px; padding: 15px; text-align: center; cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s; border: 1px solid #e2e8f0; display: flex; flex-direction: column;
        justify-content: space-between; min-height: 150px;
    }
    .item-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); border-color: #3b82f6; }
    .item-card.ticket { background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border-color: #7dd3fc; }
    .item-card.member { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-color: #86efac; }
    .item-img { width: 100%; height: 90px; object-fit: cover; border-radius: 8px; margin-bottom: 10px; }
    .item-name { font-weight: 600; color: #1e293b; font-size: 13px; margin-bottom: 5px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .item-price { color: #3b82f6; font-weight: 800; font-size: 14px; }

    /* Mid Col */
    .rfid-box { padding: 15px; background: #e0f2fe; border-radius: 10px; border: 1px dashed #7dd3fc; margin: 20px; text-align: center; }
    .rfid-input { width: 100%; padding: 12px; border: 2px solid #38bdf8; border-radius: 8px; margin-top: 10px; font-size: 16px; text-align: center; font-weight: bold; }
    .rfid-input:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 3px rgba(14,165,233,0.2); }

    /* Right Col */
    .cart-header { padding: 15px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .cart-items { flex: 1; overflow-y: auto; padding: 20px; background: #fff; }
    .cart-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #cbd5e1; }
    .cart-item-name { font-weight: 700; color: #334155; font-size: 14px; }
    .btn-qty { background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 6px; font-weight: bold; cursor: pointer; color: #475569; transition: background 0.2s; }
    .btn-qty:hover { background: #e2e8f0; color: #0f172a; }
    .qty-input { width: 45px; text-align: center; border: none; background: transparent; font-weight: 700; font-size: 15px; }
    .cart-item-price { font-weight: 800; color: #0f172a; font-size: 15px; }
    .cart-footer { padding: 20px; background: #f8fafc; border-top: 1px solid #e2e8f0; }
    .total-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 20px; font-weight: 800; color: #0f172a; }
    .btn-pay { width: 100%; padding: 18px; background: #10b981; color: white; border: none; border-radius: 10px; font-size: 18px; font-weight: 800; cursor: pointer; transition: background 0.2s; box-shadow: 0 4px 12px rgba(16,185,129,0.3); }
    .btn-pay:hover { background: #059669; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(16,185,129,0.4); }
</style>
CSS;

$newHtml = <<<HTML
<div class="pos-wrapper">
    <!-- KIRI: Kategori & Produk -->
    <div class="col-left">
        <div class="header-panel d-flex justify-content-between align-items-center">
            <h4 class="text-slate-800 font-bold mb-0"><i class="fas fa-ticket-alt text-blue-500"></i> Tiket & Member</h4>
            <div>
                <a href="{{ route('billing-new') }}" class="btn btn-sm btn-dark font-bold"><i class="fas fa-billiard"></i> POS Hiburan</a>
                <a href="{{ route('fpenjualan-pos') }}" class="btn btn-sm btn-info ms-2 font-bold" style="background-color: #0284c7; color: white; border: none;"><i class="fas fa-hamburger"></i> POS F&B</a>
            </div>
        </div>
        <div class="item-grid-container">
            <div class="item-grid">
                @foreach(\$tickets as \$item)
                @php
                    \$isMemberItem = stripos(\$item->NamaItem, 'member') !== false || stripos(\$item->NamaItem, 'langganan') !== false || strtoupper(\$item->KodeJenisItem) === 'MEMBER';
                    \$itemClass = \$isMemberItem ? 'member' : 'ticket';
                    \$itemType = \$isMemberItem ? 'MEMBER' : 'TIKET';
                @endphp
                <div class="item-card {{ \$itemClass }}" onclick="addToCart('{{ \$item->KodeItem }}', '{{ addslashes(\$item->NamaItem) }}', {{ \$item->HargaJual }}, '{{ \$itemType }}')">
                    @if(\$item->Gambar)
                        @php
                            \$imgSrc = str_starts_with(\$item->Gambar, 'http') ? \$item->Gambar : asset('assets/img/item/' . \$item->Gambar);
                        @endphp
                        <img src="{{ \$imgSrc }}" class="item-img" onerror="this.src='https://placehold.co/150x100/bae6fd/0284c7?text={{ \$itemType }}'">
                    @else
                        <div style="height:90px; background:{{ \$isMemberItem ? '#bbf7d0' : '#bae6fd' }}; border-radius:8px; margin-bottom:10px; display:flex; align-items:center; justify-content:center; color:{{ \$isMemberItem ? '#15803d' : '#0284c7' }};">
                            <i class="fas {{ \$isMemberItem ? 'fa-id-card' : 'fa-ticket-alt' }} fa-3x"></i>
                        </div>
                    @endif
                    <div class="item-name">{{ \$item->NamaItem }}</div>
                    <div class="item-price">Rp {{ number_format(\$item->HargaJual, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- TENGAH: Scan & Member -->
    <div class="col-mid">
        <div class="header-panel">
            <h5 class="mb-0 font-bold text-slate-800"><i class="fas fa-qrcode text-indigo-500"></i> Identitas & Scan</h5>
        </div>
        <div style="flex: 1; overflow-y: auto;">
            <div class="rfid-box">
                <label style="font-size: 13px; font-weight: 700; color: #0369a1; margin-bottom: 10px; display: block;">Pilih / Cari Pelanggan</label>
                
                <select id="pelanggan-select" class="form-select form-select-lg mb-3" style="width: 100%;">
                    <option value="">-- Pelanggan Umum --</option>
                    @foreach(\$pelanggan as \$p)
                        <option value="{{ \$p->KodePelanggan }}">{{ \$p->NamaPelanggan }}</option>
                    @endforeach
                </select>

                <hr style="border-color: #bae6fd; margin: 20px 0;">

                <label style="font-size: 13px; font-weight: 700; color: #0369a1; display: block;">Atau Tap Kartu RFID Member</label>
                <input type="text" id="rfid-scan" class="rfid-input" placeholder="Tap Kartu Disini..." autofocus>
                
                <div id="member-name" class="mt-3 p-2" style="display:none; font-weight:bold; background:#dcfce7; color:#166534; border-radius:6px; border:1px solid #bbf7d0;"></div>
                
                <button id="btnCheckInGym" class="btn btn-success mt-3 font-bold py-3" style="width: 100%; display: none; font-size:15px; border-radius:8px;" onclick="processGymCheckIn()">
                    <i class="fas fa-running"></i> Check-in Member (Potong Kuota)
                </button>
            </div>

            <div class="text-center mt-5 opacity-25">
                <i class="fas fa-id-card-alt fa-4x mb-3 text-slate-400"></i>
                <p class="text-slate-500 font-bold">Pastikan kursor berada di kotak<br>Tap Kartu saat melakukan scan</p>
            </div>
        </div>
    </div>

    <!-- KANAN: Keranjang -->
    <div class="col-right">
        <div class="cart-header">
            <h5 class="mb-0 font-bold text-slate-800"><i class="fas fa-shopping-cart text-emerald-500"></i> Keranjang</h5>
        </div>
        
        <div class="cart-items" id="cart-items">
            <!-- Items appended via JS -->
            <div style="text-align:center; color:#cbd5e1; margin-top:80px;" id="empty-cart-msg">
                <i class="fas fa-box-open fa-4x mb-3"></i>
                <p class="font-bold text-slate-400 text-lg">Keranjang masih kosong</p>
            </div>
        </div>

        <div class="cart-footer">
            <div class="total-row">
                <span>TOTAL</span>
                <span id="cart-total" style="color:#0ea5e9;">Rp 0</span>
            </div>
            <button class="btn-pay" onclick="processPayment()">
                <i class="fas fa-check-circle"></i> BAYAR SEKARANG
            </button>
        </div>
    </div>
</div>
HTML;

// We will replace `<style>...</style>` and `<div class="pos-container">...</div>`
$content = preg_replace('/<style>.*?<\/style>/s', $newCss, $content, 1);
$content = preg_replace('/<div class="pos-container">.*?<\/div>\s*<\/div>\s*<\/div>/s', $newHtml, $content, 1);

file_put_contents($file, $content);
echo "TicketingPoS updated to 3-column layout!\n";

