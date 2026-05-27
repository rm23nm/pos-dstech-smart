<?php
$apotekFile = __DIR__ . '/../resources/views/Transaksi/Penjualan/PoS/ApotekPoS.blade.php';
$ticketingFile = __DIR__ . '/../resources/views/Transaksi/Penjualan/PoS/TicketingPoS.blade.php';

$apotekContent = file_get_contents($apotekFile);
$ticketingContent = file_get_contents($ticketingFile);

// 1. Extract Numpad CSS
$cssExtract = '';
if (preg_match('/(\.numpad-grid \{.*?\n\t*\}\s*\n\t*\.btn-numpad \{.*?\n\t*\}\s*\n\t*\.btn-numpad:hover \{.*?\n\t*\}\s*\n\t*\.btn-numpad:active \{.*?\n\t*\}\s*\n\t*\.numpad-num \{.*?\n\t*\}\s*\n\t*\.numpad-sub \{.*?\n\t*\}\s*\n\t*\.btn-numpad-action \{.*?\n\t*\}\s*\n\t*\.btn-numpad-action:hover \{.*?\n\t*\}\s*\n\t*\.btn-numpad-action:active \{.*?\n\t*\}\s*\n\t*\.btn-numpad-action\.active \{.*?\n\t*\}\s*\n\t*\.alphapad-grid \{.*?\n\t*\}\s*\n\t*\.btn-alpha \{.*?\n\t*\}\s*\n\t*\.btn-alpha:hover \{.*?\n\t*\}\s*\n\t*\.btn-alpha:active \{.*?\n\t*\})/s', $apotekContent, $m)) {
    $cssExtract = $m[1];
} else {
    // Hardcode the CSS if regex fails
    $cssExtract = <<<CSS
        .numpad-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; padding: 6px 8px; flex: 1; }
        .btn-numpad { background: #f8fafc !important; border: 1.5px solid #e2e8f0 !important; border-radius: 12px !important; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 0.2rem !important; transition: all 0.15s; box-shadow: 0 4px 6px rgba(0,0,0,0.02) !important; color: #1e293b !important; height: 55px; }
        .btn-numpad:hover { background: #fff !important; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(11, 87, 208, 0.1) !important; border-color: rgba(11, 87, 208, 0.3) !important; color: #0b57d0 !important; }
        .btn-numpad:active { transform: translateY(1px); box-shadow: none !important; background: #f1f5f9 !important; }
        .numpad-num { font-size: 1.25rem; font-weight: 900; line-height: 1; }
        .numpad-sub { font-size: 0.5rem; font-weight: 700; color: #94a3b8; margin-top: 2px; letter-spacing: 0.5px; }
        .btn-numpad:hover .numpad-sub { color: #0b57d0; opacity: 0.8; }
        .btn-numpad-action { background: #e2e8f0 !important; border: 1.5px solid #cbd5e1 !important; border-radius: 12px !important; font-weight: 800 !important; font-size: 0.8rem !important; color: #475569 !important; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02) !important; }
        .btn-numpad-action:hover { background: #cbd5e1 !important; color: #1e293b !important; }
        .btn-numpad-action:active { transform: scale(0.95); }
        .btn-numpad-action.active { background: #0b57d0 !important; color: white !important; border-color: #0b57d0 !important; box-shadow: 0 4px 12px rgba(11, 87, 208, 0.3) !important; }
        .alphapad-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; padding: 6px 8px; flex: 1; }
        .btn-alpha { background: #f8fafc !important; border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important; font-weight: 800 !important; font-size: 0.9rem !important; color: #334155 !important; height: 42px; display: flex; align-items: center; justify-content: center; padding: 0 !important; transition: all 0.15s; }
        .btn-alpha:hover { background: #fff !important; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(11, 87, 208, 0.1) !important; border-color: rgba(11, 87, 208, 0.3) !important; color: #0b57d0 !important; }
        .btn-alpha:active { transform: translateY(1px); box-shadow: none !important; background: #f1f5f9 !important; }
CSS;
}

if (strpos($ticketingContent, '.numpad-grid') === false) {
    $ticketingContent = str_replace('</style>', $cssExtract . "\n</style>", $ticketingContent);
}

// 2. Extract Numpad HTML
$htmlExtract = '';
if (preg_match('/(<!-- Tactile Dark Touch Keyboard \(Alfamart layout style\) -->.*?)(?=<!--)/s', $apotekContent, $m)) {
    $htmlExtract = $m[1];
} else {
    // regex failed, but we can hardcode the numpad HTML since it's standard
    $htmlExtract = <<<HTML
                    <!-- Tactile Dark Touch Keyboard (Alfamart layout style) -->
                    <div class="card card-custom bg-white border-0 p-1 px-2 mb-0 mt-3" style="flex: 1 1 auto !important; height: auto !important; min-height: 380px !important; border-radius: 14px !important; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                        <div class="d-flex align-items-center justify-content-between mb-1 mt-1 px-2">
                            <h4 class="font-weight-bold text-dark mb-0" style="font-size: 0.85rem;">Touch Keyboard</h4>
                            <div class="btn-group" role="group" style="background: #e2e8f0; border-radius: 8px; padding: 2px;">
                                <button type="button" class="btn active text-white" id="btnToggleNum" onclick="toggleKeypadMode('NUM')" style="background:#0b57d0; font-size: 0.8rem !important; font-weight: 900 !important; border-radius: 6px; border: none; padding: 4px 10px !important;">123</button>
                                <button type="button" class="btn" id="btnToggleAlpha" onclick="toggleKeypadMode('ALPHA')" style="font-size: 0.8rem !important; font-weight: 900 !important; border-radius: 6px; border: none; padding: 4px 10px !important;">ABC</button>
                            </div>
                        </div>
                        
                        <div id="keypadIndicatorContainer" class="d-flex gap-1 mb-1 px-2">
                            <div class="flex-grow-1">
                                <div id="_activeInputIndicator" class="badge text-primary w-100 py-2 font-weight-bold" style="background:#e0f2fe; font-size: 0.85rem; border-radius: 12px;">Kuantitas (Qty)</div>
                            </div>
                        </div>

                        <!-- NUMPAD VIEW -->
                        <div class="numpad-grid" id="numpadViewContainer">
                            <button type="button" class="btn btn-numpad" onclick="pressNumpad('7')"><span class="numpad-num">7</span><span class="numpad-sub">PQRS</span></button>
                            <button type="button" class="btn btn-numpad" onclick="pressNumpad('8')"><span class="numpad-num">8</span><span class="numpad-sub">TUV</span></button>
                            <button type="button" class="btn btn-numpad" onclick="pressNumpad('9')"><span class="numpad-num">9</span><span class="numpad-sub">WXYZ</span></button>
                            <button type="button" class="btn btn-numpad-action active" id="_btnNumpadQty" onclick="switchActiveInput('QTY')">Qty</button>
                            
                            <button type="button" class="btn btn-numpad" onclick="pressNumpad('4')"><span class="numpad-num">4</span><span class="numpad-sub">GHI</span></button>
                            <button type="button" class="btn btn-numpad" onclick="pressNumpad('5')"><span class="numpad-num">5</span><span class="numpad-sub">JKL</span></button>
                            <button type="button" class="btn btn-numpad" onclick="pressNumpad('6')"><span class="numpad-num">6</span><span class="numpad-sub">MNO</span></button>
                            <button type="button" class="btn btn-numpad-action" id="_btnNumpadVoucher" onclick="switchActiveInput('VOUCHER')">Voucher</button>
                            
                            <button type="button" class="btn btn-numpad" onclick="pressNumpad('1')"><span class="numpad-num">1</span><span class="numpad-sub">ABC</span></button>
                            <button type="button" class="btn btn-numpad" onclick="pressNumpad('2')"><span class="numpad-num">2</span><span class="numpad-sub">DEF</span></button>
                            <button type="button" class="btn btn-numpad" onclick="pressNumpad('3')"><span class="numpad-num">3</span><span class="numpad-sub">GHI</span></button>
                            <button type="button" class="btn btn-numpad-action" id="_btnNumpadBayar" onclick="switchActiveInput('BAYAR')">Bayar</button>
                            
                            <button type="button" class="btn btn-numpad" onclick="pressNumpad('0')"><span class="numpad-num">0</span><span class="numpad-sub">__</span></button>
                            <button type="button" class="btn btn-numpad" onclick="pressNumpad('000')"><span class="numpad-num">000</span><span class="numpad-sub">__</span></button>
                            <button type="button" class="btn btn-numpad" onclick="pressNumpad('.')"><span class="numpad-num">.</span><span class="numpad-sub">Dot</span></button>
                            <button type="button" class="btn btn-numpad-action" style="background: #ef4444 !important; color: white !important;" onclick="pressNumpad('DEL')"><i class="fas fa-backspace"></i> DEL</button>
                        </div>
                        
                        <!-- ALPHAPAD VIEW -->
                        <div class="alphapad-grid" id="alphapadViewContainer" style="display:none;">
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('Q')">Q</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('W')">W</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('E')">E</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('R')">R</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('T')">T</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('Y')">Y</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('U')">U</button>
                            
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('I')">I</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('O')">O</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('P')">P</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('A')">A</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('S')">S</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('D')">D</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('F')">F</button>
                            
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('G')">G</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('H')">H</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('J')">J</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('K')">K</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('L')">L</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('Z')">Z</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('X')">X</button>
                            
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('C')">C</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('V')">V</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('B')">B</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('N')">N</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('M')">M</button>
                            <button type="button" class="btn btn-alpha" onclick="pressAlpha('-')">-</button>
                            <button type="button" class="btn btn-alpha" style="background:#ef4444 !important; color:white !important;" onclick="pressAlpha('DEL')"><i class="fas fa-backspace"></i></button>
                            
                            <button type="button" class="btn btn-alpha" style="grid-column: span 5;" onclick="pressAlpha(' ')">SPACE</button>
                            <button type="button" class="btn btn-alpha" style="grid-column: span 2; background:#10b981 !important; color:white !important;" onclick="pressAlpha('ENTER')">ENTER</button>
                        </div>
                    </div>
HTML;
}

$ticketingContent = preg_replace('/<div class="text-center mt-5 opacity-25">.*?<\/div>/s', $htmlExtract, $ticketingContent);

// 3. Inject JS logic for Numpad specific to TicketingPoS
$jsLogic = <<<JS
<script>
    let activeInputMode = 'QTY';
    
    function toggleKeypadMode(mode) {
        if(mode === 'NUM') {
            $('#numpadViewContainer').show();
            $('#alphapadViewContainer').hide();
            $('#btnToggleNum').addClass('active text-white').css('background', '#0b57d0');
            $('#btnToggleAlpha').removeClass('active text-white').css('background', 'transparent');
        } else {
            $('#numpadViewContainer').hide();
            $('#alphapadViewContainer').show();
            $('#btnToggleAlpha').addClass('active text-white').css('background', '#0b57d0');
            $('#btnToggleNum').removeClass('active text-white').css('background', 'transparent');
        }
    }
    
    function switchActiveInput(type) {
        activeInputMode = type;
        $('.btn-numpad-action').removeClass('active text-white').css('background', '#e2e8f0');
        if(type === 'QTY') {
            $('#_activeInputIndicator').text('Kuantitas (Qty)');
            $('#_btnNumpadQty').addClass('active text-white').css('background', '#0b57d0');
        } else if(type === 'VOUCHER') {
            $('#_activeInputIndicator').text('Kode Voucher');
            $('#_btnNumpadVoucher').addClass('active text-white').css('background', '#0b57d0');
        } else if(type === 'BAYAR') {
            $('#_activeInputIndicator').text('Nominal Bayar (Checkout)');
            $('#_btnNumpadBayar').addClass('active text-white').css('background', '#0b57d0');
        }
    }
    
    function pressNumpad(val) {
        if (activeInputMode === 'QTY') {
            if (cart.length > 0) {
                let lastIndex = cart.length - 1;
                let currentQty = cart[lastIndex].qty.toString();
                if (val === 'DEL') {
                    currentQty = currentQty.slice(0, -1);
                    if(currentQty === '') currentQty = '1';
                } else if (val === '0' || val === '000') {
                    if(currentQty !== '0') currentQty += val;
                } else if (val === '.') {
                    // ignore
                } else {
                    if (currentQty === '1' || currentQty === '0') currentQty = val;
                    else currentQty += val;
                }
                cart[lastIndex].qty = parseInt(currentQty) || 1;
                renderCart();
            }
        } else if (activeInputMode === 'VOUCHER') {
            let el = $('#chkKodeVoucher');
            let current = el.val();
            if(val === 'DEL') el.val(current.slice(0, -1));
            else if(val !== '.' && val !== '000') el.val(current + val);
        } else if (activeInputMode === 'BAYAR') {
            let el = $('#chkNominalBayar');
            let currentStr = (el.val() || "").replace(/[^0-9]/g, '');
            if(val === 'DEL') currentStr = currentStr.slice(0, -1);
            else if(val !== '.') currentStr += val;
            
            if(currentStr === '') currentStr = '0';
            el.val(currentStr);
            if(typeof HitungKembalian === 'function') HitungKembalian(); // trigger checkout calculation if modal is open
        }
    }
    
    function pressAlpha(val) {
        if (activeInputMode === 'VOUCHER') {
            let el = $('#chkKodeVoucher');
            if (val === 'DEL') el.val(el.val().slice(0, -1));
            else if (val === 'ENTER') $('#btnCheckVoucher').click();
            else el.val(el.val() + val);
        }
    }
</script>
JS;

$ticketingContent = str_replace('</body>', $jsLogic . "\n</body>", $ticketingContent);

file_put_contents($ticketingFile, $ticketingContent);
echo "Numpad added successfully to TicketingPoS!\n";
