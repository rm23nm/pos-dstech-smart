<?php
$file = __DIR__ . '/../resources/views/Transaksi/Penjualan/PoS/TicketingPoS.blade.php';
$content = file_get_contents($file);

// 1. Inject CSS
$css = <<<CSS
    /* Premium Header */
    .pos-header { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border-bottom: 1.5px solid rgba(11, 87, 208, 0.25); padding: 0.5rem 1.5rem; box-shadow: 0 4px 18px rgba(11, 87, 208, 0.08); position: sticky; top: 0; z-index: 1000; height: 68px; }
    .greeting-text { padding: 6px 14px; border-radius: 12px; box-shadow: 0 4px 12px rgba(11, 87, 208, 0.1); display: inline-flex; align-items: center; gap: 6px; height: 38px; }
    .clock-main { background: rgba(255, 255, 255, 0.92); border: 1.5px solid rgba(11, 87, 208, 0.3); border-radius: 50px; padding: 0.3rem 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); height: 38px; display: flex; align-items: center; justify-content: center; }
    .clock .datetime-content ul { display: flex; align-items: center; gap: 2px; margin: 0; padding: 0; list-style: none; }
    .clock .datetime-content ul li { font-weight: 800; font-size: 1.15rem; color: #0b57d0; }
    #Date { font-size: 0.75rem; font-weight: 600; color: #475569; text-align: center; margin-top: 2px; text-transform: uppercase; }
    .btn-icon { cursor:pointer; }
    .dropdown-menu { display: none; }
    .dropdown-menu.show { display: block; }
    .pos-wrapper { height: calc(100vh - 68px) !important; }
CSS;

if (strpos($content, '.pos-header') === false) {
    $content = str_replace('</style>', $css . "\n</style>", $content);
}

// 2. Inject HTML Header
$headerHtml = <<<HTML
<header class="pos-header">
   <div class="container-fluid">
       <div class="row align-items-center" style="height: 52px;">
           <div class="col-xl-4 col-lg-4 col-md-6 d-flex align-items-center" style="position: relative;">
               <div class="logo-container" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%);">
                   @php \$companyData = json_decode(\$company, true) ?? []; @endphp
                   @if(!empty(\$companyData[0]['icon']))
                       <img src="{{ \$companyData[0]['icon'] }}" alt="Logo" style="height: 42px; max-width: 90px; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(9, 76, 180, 0.15); background: white; border: 1.5px solid rgba(9, 76, 180, 0.2); padding: 2px;">
                   @else
                       <img src="{{ asset('images/misc/LogoFront.png') }}" alt="Logo" style="height: 42px; max-width: 90px; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(9, 76, 180, 0.15); background: white; border: 1.5px solid rgba(9, 76, 180, 0.2); padding: 2px;">
                   @endif
               </div>
               <div class="greeting-text" style="margin-left: 105px !important; background: linear-gradient(135deg, rgba(9, 76, 180, 0.06) 0%, rgba(0, 188, 255, 0.06) 100%) !important; border: 1.5px solid rgba(9, 76, 180, 0.2) !important;">
                <span class="font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; color: #094cb4;">WELCOME,</span>
                <span class="font-weight-bolder text-dark" style="font-size: 0.85rem;">{{ Auth::user()->name ?? 'Kasir' }}</span>
               </div>
           </div>
           <div class="col-xl-4 col-lg-5 col-md-6 clock-main">
            <div class="clock">
              <div class="datetime-content">
                <ul>
                    <li id="hours"></li><li id="point1">:</li><li id="min"></li><li id="point">:</li><li id="sec"></li>
                </ul>
              </div>
             <div class="datetime-content"><div id="Date"></div></div>
            </div>
           </div>
           <div class="col-xl-4 col-lg-3 col-md-12 order-lg-last order-second">
            <div class="d-flex justify-content-end align-items-center gap-2">
                <div class="d-none d-md-flex">
                    <span class="badge px-3 py-2 rounded-pill font-weight-bold" style="font-size: 0.72rem; display: inline-flex; align-items: center; gap: 4px; background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(11, 87, 208, 0.25); color: #0b57d0; height: 38px;">
                        Kasir Aktif: Tiket Mode
                    </span>
                </div>
                <div>
                    <div class="btn btn-icon w-auto h-auto d-flex align-items-center py-0 me-3" onclick="showDraftList()" style="background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(11, 87, 208, 0.25); border-radius: 12px; height: 38px; padding: 0 12px !important; position: relative;">
                        <span class="badge badge-pill text-white" id="_draftCount" style="position: absolute; top: -8px; right: -8px; background: #dc2626; border-radius: 50%; padding:2px 6px;">0</span>
                        <i class="fas fa-folder-open text-primary"></i>
                    </div>
                </div>
                <div>
                    <div id="btOpenCustDisplay" class="btn btn-icon w-auto h-auto d-flex align-items-center py-0 me-3" onclick="window.open('/customer-display', '_blank')" style="background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(11, 87, 208, 0.25); border-radius: 12px; height: 38px; padding: 0 12px !important;">
                        <i class="fas fa-desktop text-success"></i>
                    </div>
                </div>
             <div class="dropdown">
                 <div data-bs-toggle="dropdown">
                     <div class="btn btn-icon w-auto h-auto d-flex align-items-center py-0" onclick="document.getElementById('logoutDropdown').classList.toggle('show')" style="background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(11, 87, 208, 0.25); border-radius: 12px; height: 38px; padding: 0 12px !important;">
                         <i class="fas fa-user-circle text-primary"></i>
                     </div>
                 </div>
                 <div class="dropdown-menu dropdown-menu-right" id="logoutDropdown" style="position:absolute; right:0; top:45px; background:white; border:1px solid #ddd; border-radius:8px; padding:10px;">
                     <a href="{{ route('logout') }}" class="dropdown-item text-danger font-bold" style="text-decoration:none;"><i class="fas fa-power-off"></i> Logout</a>
                 </div>
             </div>
            </div>
           </div>
       </div>
   </div>
</header>
HTML;

if (strpos($content, '<header class="pos-header">') === false) {
    $content = str_replace('<div class="pos-wrapper">', $headerHtml . "\n" . '<div class="pos-wrapper">', $content);
}

// 3. Replace Cart Footer
$oldFooter = preg_replace('/\s+/', ' ', '<div class="cart-footer"> <div class="total-row"> <span>TOTAL</span> <span id="cart-total" style="color:#0ea5e9;">Rp 0</span> </div> <button class="btn-pay" onclick="processPayment()"> <i class="fas fa-check-circle"></i> BAYAR SEKARANG </button> </div>');
$newFooter = <<<HTML
        <div class="cart-footer" style="background:#f8fafc; padding:15px; border-top:1px solid #e2e8f0; font-size:14px;">
            <div class="d-flex justify-content-between mb-1 text-slate-600">
                <span>Subtotal</span>
                <span id="cart-subtotal" class="font-bold">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between mb-1 text-slate-600">
                <span>Pajak PPN (<span id="lblPpn">{{ \$company->PPN ?? 0 }}</span>%)</span>
                <span id="cart-ppn" class="font-bold">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between mb-1 text-slate-600">
                <span>Pajak Hiburan (<span id="lblPb1">{{ \$company->PB1 ?? 0 }}</span>%)</span>
                <span id="cart-pb1" class="font-bold">Rp 0</span>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-2 mt-2 pt-2" style="border-top:1px dashed #cbd5e1;">
                <input type="text" id="chkKodeVoucher" class="form-control form-control-sm w-50" placeholder="Kode Voucher">
                <button class="btn btn-sm btn-info text-white font-bold" onclick="checkVoucherTicketing()" id="btnCheckVoucher">Cek</button>
            </div>
            <div class="d-flex justify-content-between mb-2 text-rose-500 font-bold">
                <span>Voucher</span>
                <span>- Rp <input type="text" id="chkVoucher" value="0" readonly style="border:none; background:transparent; width:80px; text-align:right; color:#e11d48; font-weight:bold; padding:0;"></span>
            </div>
            
            <div class="d-flex justify-content-between mb-3" style="font-size:22px; font-weight:800; color:#0f172a; border-top:2px solid #cbd5e1; padding-top:10px;">
                <span>TOTAL</span>
                <span id="cart-total" style="color:#0ea5e9;">Rp 0</span>
            </div>
            <input type="hidden" id="chkSubtotal">
            <input type="hidden" id="chkPpnNominal">
            <input type="hidden" id="chkPb1Nominal">
            <input type="hidden" id="chkGrandTotal">
            
            <div class="d-flex gap-2">
                <button class="btn btn-warning flex-fill font-bold text-white" onclick="simpanDraftTicketing()" style="background:#f59e0b; border:none;"><i class="fas fa-save"></i> Draft (F6)</button>
                <button class="btn-pay flex-fill" style="margin:0; width:auto; flex:2;" onclick="processPayment()">
                    <i class="fas fa-check-circle"></i> BAYAR SEKARANG
                </button>
            </div>
        </div>
HTML;

$contentRegex = preg_replace('/<div class="cart-footer">.*?<\/div>\s*<\/div>/s', $newFooter . "\n    </div>", $content, 1);
if ($contentRegex !== null) {
    $content = $contentRegex;
}

// 4. Inject Clock JS and Draft JS
$jsInject = <<<JS
<script>
    // Clock
    setInterval(function() {
        var date = new Date();
        $('#hours').text((date.getHours() < 10 ? "0" : "") + date.getHours());
        $('#min').text((date.getMinutes() < 10 ? "0" : "") + date.getMinutes());
        $('#sec').text((date.getSeconds() < 10 ? "0" : "") + date.getSeconds());
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $('#Date').text(date.getDate() + " " + monthNames[date.getMonth()] + " " + date.getFullYear());
    }, 1000);
    
    let localDrafts = JSON.parse(localStorage.getItem('ticketDrafts')) || [];
    $('#_draftCount').text(localDrafts.length);

    function simpanDraftTicketing() {
        if(cart.length === 0) {
            Swal.fire('Info', 'Keranjang kosong', 'warning'); return;
        }
        let draftObj = {
            id: Date.now(),
            date: new Date().toLocaleString(),
            pelanggan: $('#pelanggan-select').val(),
            cart: cart
        };
        localDrafts.push(draftObj);
        localStorage.setItem('ticketDrafts', JSON.stringify(localDrafts));
        $('#_draftCount').text(localDrafts.length);
        cart = []; renderCart();
        Swal.fire('Berhasil', 'Tersimpan ke Draft Sementara', 'success');
    }

    function showDraftList() {
        let htm = '<div class="list-group">';
        if(localDrafts.length === 0) htm += '<p>Belum ada draft.</p>';
        localDrafts.forEach((d, i) => {
            htm += `<a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="loadDraft(\${i})">
                Draft \${d.date} - Item: \${d.cart.length}
            </a>`;
        });
        htm += '</div>';
        Swal.fire({
            title: 'Daftar Draft',
            html: htm,
            showCloseButton: true,
            showConfirmButton: false
        });
    }

    function loadDraft(idx) {
        let d = localDrafts[idx];
        cart = d.cart;
        if(d.pelanggan) $('#pelanggan-select').val(d.pelanggan).trigger('change');
        localDrafts.splice(idx, 1);
        localStorage.setItem('ticketDrafts', JSON.stringify(localDrafts));
        $('#_draftCount').text(localDrafts.length);
        renderCart();
        Swal.close();
    }
    
    // Keyboard Shortcut F6
    $(document).keydown(function(e) {
        if (e.which === 117) { // F6
            e.preventDefault();
            simpanDraftTicketing();
        }
    });
</script>
JS;
$content = str_replace('</body>', $jsInject . "\n</body>", $content);

// 5. Update renderCart() JS to compute taxes real-time
$oldRenderCart = "function renderCart() {";
$newRenderCart = <<<JS
function renderCart() {
        let subtotal = 0;
        let html = '';
        if (cart.length === 0) {
            html = '<div style="text-align:center; color:#cbd5e1; margin-top:80px;" id="empty-cart-msg"><i class="fas fa-box-open fa-4x mb-3"></i><p class="font-bold text-slate-400 text-lg">Keranjang masih kosong</p></div>';
        } else {
            cart.forEach(function(item, index) {
                let itemTotal = item.qty * item.price;
                subtotal += itemTotal;
                html += `
                <div class="cart-item">
                    <div>
                        <div class="cart-item-name">\${item.name}</div>
                        <div style="font-size:12px; color:#64748b;">Rp \${formatRp(item.price)} x \${item.qty}</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:15px;">
                        <div class="cart-item-price">Rp \${formatRp(itemTotal)}</div>
                        <div style="display:flex; align-items:center; background:#f1f5f9; border-radius:8px; padding:2px;">
                            <button class="btn-qty" onclick="updateQty(\${index}, -1)">-</button>
                            <input type="text" class="qty-input" value="\${item.qty}" readonly>
                            <button class="btn-qty" onclick="updateQty(\${index}, 1)">+</button>
                        </div>
                        <button class="btn btn-sm btn-danger px-2" style="border-radius:6px;" onclick="removeItem(\${index})"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>`;
            });
        }
        $('#cart-items').html(html);

        // Real-time calculations
        let ppnRate = parseFloat('{{ \$company->PPN ?? 0 }}') || 0;
        let pb1Rate = parseFloat('{{ \$company->PB1 ?? 0 }}') || 0;
        let ppnNominal = subtotal * (ppnRate / 100);
        let pb1Nominal = subtotal * (pb1Rate / 100);
        
        let voucherStr = $('#chkVoucher').val() || "0";
        let voucher = parseFloat(voucherStr.replace(/\./g, '')) || 0;
        
        let grandTotal = subtotal + ppnNominal + pb1Nominal - voucher;
        if(grandTotal < 0) grandTotal = 0;
        
        $('#cart-subtotal').text('Rp ' + formatRp(subtotal));
        $('#cart-ppn').text('Rp ' + formatRp(ppnNominal));
        $('#cart-pb1').text('Rp ' + formatRp(pb1Nominal));
        $('#cart-total').text('Rp ' + formatRp(grandTotal));
        
        $('#chkSubtotal').val(subtotal);
        $('#chkPpnNominal').val(ppnNominal);
        $('#chkPb1Nominal').val(pb1Nominal);
        $('#chkGrandTotal').val(grandTotal);
JS;

$content = preg_replace('/function renderCart\(\) \{.*?(\$\(\'#cart-items\'\)\.html\(html\);|(\$\(\'#cart-total\'\)\.text\(\'Rp \' \+ formatRp\(subtotal\)\);))/s', $newRenderCart, $content);

file_put_contents($file, $content);
echo "Modifications applied successfully.\n";
