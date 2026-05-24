<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Waterpark & Ticketing POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<style>
    .pos-container {
        display: flex;
        height: calc(100vh - 100px);
        background-color: #f0f4f8;
        font-family: 'Inter', sans-serif;
    }
    .pos-catalog {
        flex: 2;
        padding: 20px;
        overflow-y: auto;
    }
    .pos-cart {
        flex: 1;
        background: #fff;
        border-left: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        box-shadow: -4px 0 15px rgba(0,0,0,0.05);
    }
    .item-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
    }
    .item-card {
        background: #fff;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 160px;
    }
    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        border-color: #3b82f6;
    }
    .item-card.ticket {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        border-color: #7dd3fc;
    }
    .item-card.ticket:hover {
        border-color: #0ea5e9;
    }
    .item-card.member {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border-color: #86efac;
    }
    .item-card.member:hover {
        border-color: #22c55e;
    }
    .item-img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .item-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 14px;
        margin-bottom: 5px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .item-price {
        color: #3b82f6;
        font-weight: 700;
    }
    .cart-header {
        padding: 20px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .cart-items {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
    }
    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #cbd5e1;
    }
    .cart-item-details {
        flex: 1;
    }
    .cart-item-name {
        font-weight: 600;
        color: #334155;
        font-size: 14px;
    }
    .cart-item-qty {
        display: flex;
        align-items: center;
        margin-top: 8px;
    }
    .btn-qty {
        background: #f1f5f9;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        color: #475569;
    }
    .btn-qty:hover {
        background: #e2e8f0;
    }
    .qty-input {
        width: 40px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 600;
    }
    .cart-item-price {
        font-weight: 700;
        color: #0f172a;
    }
    .cart-footer {
        padding: 20px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }
    .btn-pay {
        width: 100%;
        padding: 15px;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-pay:hover {
        background: #2563eb;
    }
    .rfid-box {
        margin-top: 10px;
        padding: 10px;
        background: #e0f2fe;
        border-radius: 8px;
        border: 1px solid #bae6fd;
    }
    .rfid-input {
        width: 100%;
        padding: 8px;
        border: 1px solid #93c5fd;
        border-radius: 5px;
        margin-top: 5px;
    }
</style>

<div class="pos-container">
    <div class="pos-catalog">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-slate-800 font-bold mb-0"><i class="fas fa-ticket-alt text-blue-500"></i> Tiket Masuk</h4>
            <div>
                <a href="{{ route('billing-new') }}" class="btn btn-sm btn-dark"><i class="fas fa-billiard"></i> Beralih ke POS Hiburan</a>
                <a href="{{ route('fpenjualan-pos') }}" class="btn btn-sm btn-info ml-2" style="background-color: #0284c7; color: white; border: none;"><i class="fas fa-hamburger"></i> Beralih ke POS F&B</a>
            </div>
        </div>
        <div class="item-grid mb-5">
            @foreach($tickets as $item)
            @php
                $isMemberItem = stripos($item->NamaItem, 'member') !== false || stripos($item->NamaItem, 'langganan') !== false || strtoupper($item->KodeJenisItem) === 'MEMBER';
                $itemClass = $isMemberItem ? 'member' : 'ticket';
                $itemType = $isMemberItem ? 'MEMBER' : 'TIKET';
            @endphp
            <div class="item-card {{ $itemClass }}" onclick="addToCart('{{ $item->KodeItem }}', '{{ addslashes($item->NamaItem) }}', {{ $item->HargaJual }}, '{{ $itemType }}')">
                @if($item->Gambar)
                    @php
                        $imgSrc = str_starts_with($item->Gambar, 'http') ? $item->Gambar : asset('assets/img/item/' . $item->Gambar);
                    @endphp
                    <img src="{{ $imgSrc }}" class="item-img" onerror="this.src='https://placehold.co/150x100/bae6fd/0284c7?text={{ $itemType }}'">
                @else
                    <div style="height:100px; background:{{ $isMemberItem ? '#bbf7d0' : '#bae6fd' }}; border-radius:8px; margin-bottom:10px; display:flex; align-items:center; justify-content:center; color:{{ $isMemberItem ? '#15803d' : '#0284c7' }};">
                        <i class="fas {{ $isMemberItem ? 'fa-id-card' : 'fa-ticket-alt' }} fa-3x"></i>
                    </div>
                @endif
                <div class="item-name">{{ $item->NamaItem }}</div>
                <div class="item-price">Rp {{ number_format($item->HargaJual, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>


    </div>

    <div class="pos-cart">
        <div class="cart-header">
            <h5 class="mb-0 font-bold"><i class="fas fa-shopping-cart"></i> Keranjang</h5>
            
            <div class="rfid-box">
                <label style="font-size: 12px; font-weight: 600; color: #0369a1;">Pilih / Scan Member</label>
                
                <select id="pelanggan-select" class="form-select form-select-sm mb-2" style="width: 100%;">
                    <option value="">-- Pelanggan Umum --</option>
                    @foreach($pelanggan as $p)
                        <option value="{{ $p->KodePelanggan }}">{{ $p->NamaPelanggan }}</option>
                    @endforeach
                </select>

                <input type="text" id="rfid-scan" class="rfid-input" placeholder="Atau Tap Kartu Disini..." autofocus>
                <small id="member-name" class="text-success" style="display:none; font-weight:bold; margin-top:5px;"></small>
            </div>
        </div>
        
        <div class="cart-items" id="cart-items">
            <!-- Items will be appended here -->
            <div style="text-align:center; color:#94a3b8; margin-top:50px;" id="empty-cart-msg">
                <i class="fas fa-box-open fa-3x mb-3"></i>
                <p>Keranjang masih kosong</p>
            </div>
        </div>

        <div class="cart-footer">
            <div class="total-row">
                <span>Total</span>
                <span id="cart-total">Rp 0</span>
            </div>
            <button class="btn-pay" onclick="processPayment()"><i class="fas fa-money-bill-wave"></i> Bayar Sekarang</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ env('MIDTRANS_IS_PRODUCTION', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $midtransclientkey }}"></script>

<script>
    let cart = [];
    let memberUid = null;
    let memberName = null;

    // Pelanggan list for offline RFID lookup
    const pelangganList = @json($pelanggan);

    $(document).ready(function() {
        $('#pelanggan-select').select2({
            placeholder: "-- Pilih Pelanggan Umum --",
            allowClear: true
        }).on('change', function() {
            let selectedVal = $(this).val();
            if (selectedVal) {
                let member = pelangganList.find(p => p.KodePelanggan === selectedVal);
                if (member) {
                    memberUid = member.KodePelanggan;
                    memberName = member.NamaPelanggan;
                    $('#member-name').text('✅ Member: ' + memberName).show();
                    $('#rfid-scan').val('');
                }
            } else {
                memberUid = null;
                memberName = null;
                $('#member-name').hide();
                $('#rfid-scan').val('');
            }
        });
    });

    $('#rfid-scan').on('keypress', function(e) {
        if(e.which == 13) {
            let uid = $(this).val().trim();
            if(uid) {
                // Cari di array pelanggan
                let member = pelangganList.find(p => p.RFID_UID == uid || p.Keterangan == uid);
                if(member) {
                    memberUid = member.KodePelanggan;
                    memberName = member.NamaPelanggan;
                    $('#member-name').text('✅ Member: ' + memberName).show();
                    $('#pelanggan-select').val(member.KodePelanggan).trigger('change.select2');
                    Swal.fire('Berhasil', 'Member ' + memberName + ' terdeteksi.', 'success');
                } else {
                    Swal.fire('Gagal', 'RFID tidak terdaftar.', 'error');
                    $(this).val('');
                }
            }
        }
    });

    function addToCart(code, name, price, type) {
        let existing = cart.find(item => item.code === code);
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({
                code: code,
                name: name,
                price: price,
                qty: 1,
                type: type
            });
        }
        renderCart();
    }

    function updateQty(code, change) {
        let item = cart.find(item => item.code === code);
        if (item) {
            item.qty += change;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.code !== code);
            }
        }
        renderCart();
    }

    function renderCart() {
        let html = '';
        let total = 0;
        
        if (cart.length === 0) {
            $('#empty-cart-msg').show();
        } else {
            $('#empty-cart-msg').hide();
            cart.forEach(item => {
                let itemTotal = item.price * item.qty;
                total += itemTotal;
                html += `
                <div class="cart-item">
                    <div class="cart-item-details">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-qty">
                            <button class="btn-qty" onclick="updateQty('${item.code}', -1)">-</button>
                            <input type="text" class="qty-input" value="${item.qty}" readonly>
                            <button class="btn-qty" onclick="updateQty('${item.code}', 1)">+</button>
                        </div>
                    </div>
                    <div class="cart-item-price">Rp ${itemTotal.toLocaleString('id-ID')}</div>
                </div>`;
            });
        }
        
        $('#cart-items').html(html);
        $('#cart-total').text('Rp ' + formatRp(total));

        // Sync to Customer Display
        let taxValue = 0; // Ticketing doesn't calculate tax per item in the cart array, but we can pass 0 for now or calculate it
        let discountValue = parseFloat($('#chkVoucher').val().replace(/\./g, '')) || 0;
        let posDataObj = {
            data: cart.map(c => ({ NamaItem: c.NamaItem, Qty: c.Qty, Harga: c.Harga })),
            Total: total,
            Discount: discountValue,
            Tax: taxValue,
            Net: total - discountValue + taxValue
        };
        localStorage.setItem('PoSData', JSON.stringify(posDataObj));
    }

    function processPayment() {
        if(cart.length === 0) {
            Swal.fire('Kosong', 'Tambahkan item terlebih dahulu', 'warning');
            return;
        }
        
        // Setup Nilai Awal Modal
        let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        let ppnRate = {{ $company->PPN ?? 0 }};
        let pb1Rate = {{ $company->PB1 ?? 0 }};
        
        $('#chkSubtotal').val(total);
        $('#chkSubtotalStr').val(formatRp(total));
        
        $('#chkPpnRate').val(ppnRate);
        $('#chkPb1Rate').val(pb1Rate);
        
        $('#chkVoucher').val(0);
        $('#chkNominalBayar').val('');
        $('#chkNominalBayarStr').val('');
        
        calcCheckout();
        $('#chkMetodeBayar').trigger('change');
        $('#modalCheckout').modal('show');
    }
    
    function calcCheckout() {
        let subtotal = parseFloat($('#chkSubtotal').val()) || 0;
        let ppnRate = parseFloat($('#chkPpnRate').val()) || 0;
        let pb1Rate = parseFloat($('#chkPb1Rate').val()) || 0;
        let voucher = parseFloat($('#chkVoucher').val()) || 0;
        
        let ppnNominal = subtotal * (ppnRate / 100);
        let pb1Nominal = subtotal * (pb1Rate / 100);
        
        $('#chkPpnNominal').val(formatRp(ppnNominal));
        $('#chkPb1Nominal').val(formatRp(pb1Nominal));
        
        let grandTotal = subtotal + ppnNominal + pb1Nominal - voucher;
        if(grandTotal < 0) grandTotal = 0;
        
        $('#chkGrandTotal').val(grandTotal);
        $('#chkGrandTotalStr').text('Rp ' + formatRp(grandTotal));
        
        let bayarStr = $('#chkNominalBayarStr').val().replace(/\./g, '');
        let bayar = parseFloat(bayarStr) || 0;
        let kembalian = bayar - grandTotal;
        
        if(kembalian < 0) kembalian = 0;
        
        $('#chkKembalian').val(kembalian);
        $('#chkKembalianStr').text('Rp ' + formatRp(kembalian));
        
        if (bayar > 0) {
            if (bayar < grandTotal) {
                $('#chkKembalianStr').css('color', '#dc2626');
            } else {
                $('#chkKembalianStr').css('color', '#16a34a');
            }
        } else {
            $('#chkKembalianStr').css('color', '#16a34a');
        }
    }
    
    function checkVoucherCode() {
        let code = $('#chkKodeVoucher').val().trim();
        let subtotal = parseFloat($('#chkSubtotal').val()) || 0;
        
        if (!code) {
            Swal.fire('Perhatian', 'Silakan masukkan kode voucher terlebih dahulu.', 'warning');
            return;
        }
        
        $('#btnCheckVoucher').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '/ticketing-pos/check-voucher',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                KodeVoucher: code,
                Subtotal: subtotal
            },
            success: function(res) {
                $('#btnCheckVoucher').prop('disabled', false).text('Cek');
                if (res.success) {
                    $('#chkVoucher').val(formatRp(res.discount));
                    calcCheckout();
                    Swal.fire('Berhasil', res.message + '<br>Diskon didapat: Rp ' + formatRp(res.discount), 'success');
                } else {
                    $('#chkVoucher').val(0);
                    calcCheckout();
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                $('#btnCheckVoucher').prop('disabled', false).text('Cek');
                Swal.fire('Error', 'Gagal memvalidasi voucher', 'error');
            }
        });
    }

    $(document).ready(function() {
        $('#chkMetodeBayar').on('change', function() {
            let type = $(this).find(':selected').data('type');
            if (type === 'NON TUNAI') {
                $('#chkNominalBayar').val($('#chkGrandTotal').val());
                $('#chkNominalBayarStr').val(formatRp($('#chkGrandTotal').val()));
                $('#chkNominalBayarStr').prop('readonly', true);
                calcCheckout();
            } else {
                $('#chkNominalBayarStr').prop('readonly', false);
                $('#chkNominalBayar').val(0);
                $('#chkNominalBayarStr').val('');
                calcCheckout();
            }
        });

        $('#chkNominalBayarStr').on('keyup', function() {
            // Auto format ribuan
            let val = $(this).val().replace(/[^,\d]/g, '').toString();
            let split = val.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            $(this).val(rupiah);
            calcCheckout();
        });
    });

    function submitCheckout() {
        const btn = $('#btnSubmitCheckout');
        const oldHtml = btn.html();
        
        let bayarStr = $('#chkNominalBayarStr').val().replace(/\./g, '');
        let bayar = parseFloat(bayarStr) || 0;
        let grandTotal = parseFloat($('#chkGrandTotal').val()) || 0;
        
        if (bayar < grandTotal) {
            Swal.fire('Perhatian', 'Nominal uang dibayar kurang dari Grand Total!', 'warning');
            return;
        }

        // Cek jika ada item bertipe MEMBER tapi belum memilih pelanggan
        let hasMemberItem = cart.some(item => item.type === 'MEMBER');
        if (hasMemberItem && (!memberUid || memberUid === '')) {
            Swal.fire('Pelanggan Wajib Dipilih', 'Anda menjual Paket Berlangganan (Member), wajib memilih atau menscan kartu RFID Pelanggan terlebih dahulu!', 'error');
            return;
        }
        
        let payload = {
            _token: '{{ csrf_token() }}',
            items: cart,
            MemberUid: memberUid,
            MemberName: memberName,
            KodePelanggan: memberUid ? memberUid : 'CASH',
            Subtotal: parseFloat($('#chkSubtotal').val()) || 0,
            PPN: parseFloat($('#chkPpnNominal').val().replace(/\./g, '')) || 0,
            PB1: parseFloat($('#chkPb1Nominal').val().replace(/\./g, '')) || 0,
            Potongan: parseFloat($('#chkVoucher').val().replace(/\./g, '')) || 0,
            KodeVoucher: $('#chkKodeVoucher').val(),
            GrandTotal: grandTotal,
            NominalBayar: bayar,
            MetodePembayaranId: $('#chkMetodeBayar').val()
        };
        
        let selectedOption = $('#chkMetodeBayar').find(':selected');
        let tipePembayaran = selectedOption.data('type');

        if (tipePembayaran === 'NON TUNAI') {
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading Midtrans...');
            
            $.ajax({
                url: '/ticketing-pos/create-payment-token',
                type: 'POST',
                data: payload,
                success: function(res) {
                    if (res.snap_token) {
                        btn.prop('disabled', false).html(oldHtml);
                        
                        // Pass token to Customer Display
                        localStorage.setItem('PoSMidtransToken', res.snap_token);

                        snap.pay(res.snap_token, {
                            onSuccess: function(result) {
                                submitCheckoutToStore(payload, btn, oldHtml);
                                localStorage.removeItem('PoSMidtransToken');
                            },
                            onPending: function(result) {
                                Swal.fire('Pending', 'Menunggu pembayaran diselesaikan...', 'info');
                                localStorage.removeItem('PoSMidtransToken');
                            },
                            onError: function(result) {
                                Swal.fire('Gagal', 'Pembayaran gagal!', 'error');
                                localStorage.removeItem('PoSMidtransToken');
                            },
                            onClose: function() {
                                localStorage.removeItem('PoSMidtransToken');
                            }
                        });
                    } else {
                        btn.prop('disabled', false).html(oldHtml);
                        Swal.fire('Gagal', res.message || 'Gagal memanggil Midtrans', 'error');
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html(oldHtml);
                    Swal.fire('Error', 'Gagal request token Midtrans', 'error');
                }
            });
        } else {
            submitCheckoutToStore(payload, btn, oldHtml);
        }
    }

    function submitCheckoutToStore(payload, btn, oldHtml) {
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        $.ajax({
            url: '/ticketing-pos/store',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function(res) {
                btn.prop('disabled', false).html(oldHtml);
                if (res.success) {
                    $('#modalCheckout').modal('hide');
                    let msg = 'No Faktur: ' + res.invoiceNo;
                    if (res.kembalian > 0) msg += '<br>Kembalian: Rp ' + formatRp(res.kembalian);
                    
                    Swal.fire({
                        title: 'Pembayaran Lunas!', 
                        html: msg + '<br><br><b>Barcode tiket masuk otomatis tercetak. Gate Siap!</b>', 
                        icon: 'success'
                    }).then(() => {
                        let printUrl = "{{ url('') }}/ticketing-pos/printthermal/" + res.invoiceNo;
                        window.open(printUrl, '_blank');

                        cart = [];
                        renderCart();
                        $('#rfid-scan').val('');
                        $('#member-name').hide();
                    });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                btn.prop('disabled', false).html(oldHtml);
                Swal.fire('Error', 'Gagal memproses transaksi ke server', 'error');
            }
        });
    }

    function formatRp(angka) {
        return Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
</script>

<!-- Checkout Modal -->
<div class="modal fade" id="modalCheckout" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:99999;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 10px 25px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background:#0ea5e9; color:white; border-radius:12px 12px 0 0;">
                <h5 class="modal-title font-bold"><i class="fas fa-money-bill-wave"></i> Proses Pembayaran</h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" style="background:transparent; border:none; font-size:1.5rem; line-height:1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background:#f8fafc; padding:20px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="text-sm font-bold text-slate-600">Subtotal</label>
                            <input type="hidden" id="chkSubtotal">
                            <input type="text" class="form-control" id="chkSubtotalStr" readonly style="background:#e2e8f0; font-weight:bold;">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-sm font-bold text-slate-600">Pajak (PPN <span id="lblPpn">{{ $company->PPN ?? 0 }}</span>%)</label>
                            <input type="hidden" id="chkPpnRate">
                            <input type="text" class="form-control" id="chkPpnNominal" readonly style="background:#e2e8f0;">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-sm font-bold text-slate-600">Pajak Hiburan (<span id="lblPb1">{{ $company->PB1 ?? 0 }}</span>%)</label>
                            <input type="hidden" id="chkPb1Rate">
                            <input type="text" class="form-control" id="chkPb1Nominal" readonly style="background:#e2e8f0;">
                        </div>
                        <div class="form-group mb-2">
                            <label class="text-sm font-bold text-slate-600">Kode Voucher (Opsional)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="chkKodeVoucher" placeholder="Masukkan kode voucher" style="border-color:#bae6fd;">
                                <button class="btn btn-info text-white" type="button" id="btnCheckVoucher" onclick="checkVoucherCode()" style="font-weight:bold;">Cek</button>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-sm font-bold text-slate-600">Potongan Harga (Rp)</label>
                            <input type="text" class="form-control" id="chkVoucher" value="0" readonly style="background:#e2e8f0; font-weight:bold; color:#dc2626;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 mb-3" style="background:#0f172a; color:white; border-radius:8px; text-align:center;">
                            <div style="font-size:0.8rem; color:#94a3b8;">GRAND TOTAL</div>
                            <input type="hidden" id="chkGrandTotal">
                            <div id="chkGrandTotalStr" style="font-size:1.5rem; font-weight:bold; color:#38bdf8;">Rp 0</div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-sm font-bold text-slate-600">Metode Pembayaran</label>
                            <select class="form-control" id="chkMetodeBayar" style="border-color:#bae6fd; font-weight:bold;">
                                @foreach($metodePembayaran as $mb)
                                    <option value="{{ $mb->id }}" data-type="{{ $mb->TipePembayaran }}">{{ $mb->NamaMetodePembayaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-sm font-bold text-slate-600">Uang Dibayar (Rp)</label>
                            <input type="hidden" id="chkNominalBayar">
                            <input type="text" class="form-control" id="chkNominalBayarStr" placeholder="Contoh: 100.000" style="font-size:1.2rem; font-weight:bold; color:#1e293b; border:2px solid #0ea5e9;">
                        </div>
                        <div class="p-2 text-center" style="background:#dcfce7; border-radius:6px; border:1px solid #bbf7d0;">
                            <div style="font-size:0.8rem; color:#166534; font-weight:bold;">KEMBALIAN</div>
                            <input type="hidden" id="chkKembalian">
                            <div id="chkKembalianStr" style="font-size:1.2rem; font-weight:bold; color:#16a34a;">Rp 0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background:#f1f5f9; border-radius:0 0 12px 12px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSubmitCheckout" onclick="submitCheckout()" style="background:#0ea5e9; border:none; padding:8px 20px; font-weight:bold;"><i class="fas fa-check-circle"></i> Bayar Lunas</button>
            </div>
        </div>
    </div>
</div>
    </body>
</html>

