<?php
$file = __DIR__ . '/../resources/views/Transaksi/Penjualan/PoS/TicketingPoS.blade.php';
$content = file_get_contents($file);

// 1. Remove POS Hiburan button
$content = preg_replace('/<a href="\{\{ route\(\'billing-new\'\) \}\}" class="btn btn-sm btn-dark font-bold"><i class="fas fa-billiard"><\/i> POS Hiburan<\/a>\s*/', '', $content);

// 2. Fix Malformed Numpad and restore layout
// First, find the malformed chunk starting from "<!-- Tactile Dark Touch Keyboard" up to "<!-- KANAN: Keranjang -->"
if (preg_match('/<!-- Tactile Dark Touch Keyboard.*?<!-- KANAN: Keranjang -->/s', $content, $m)) {
    // The correct HTML to replace it
    $correctNumpad = <<<HTML
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
    </div> <!-- Close flex div -->
</div> <!-- Close col-mid -->

<!-- KANAN: Keranjang -->
HTML;
    $content = str_replace($m[0], $correctNumpad, $content);
}

// 3. Add parseFormattedRp function if not exists
if (strpos($content, 'function parseFormattedRp') === false) {
    $jsMissing = <<<JS
<script>
    function parseFormattedRp(val) {
        if (!val) return 0;
        return parseFloat(val.toString().replace(/\./g, '')) || 0;
    }
</script>
JS;
    $content = str_replace('</body>', $jsMissing . "\n</body>", $content);
}

file_put_contents($file, $content);
echo "Layout and JS errors fixed successfully!\n";
