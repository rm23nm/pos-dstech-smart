const fs = require('fs');

const appFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let app = fs.readFileSync(appFile, 'utf8');

const regexToExtract = /(\s*\{\/\* Info Tarif Tambahan \*\/\}\s*<div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0\.5rem', marginTop: '0\.5rem' }}>[\s\S]*?eMobil\?\.toLocaleString\(\) \|\| 0\}<\/span>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*)/;

const match = app.match(regexToExtract);

if (match) {
    const blockToMove = match[0];
    
    // Remove from original spot
    app = app.replace(blockToMove, '');
    
    // Find the RESET button block
    const resetRegex = /(\s*<button className="btn btn-outline" style={{ color: 'white', borderColor: 'rgba\(255,255,255,0\.1\)', padding: '1rem 1\.5rem' }} onClick=\{\(\) => \{ setPosData\(null\); setManualPlate\(''\); if\(scanInputRef\.current\) scanInputRef\.current\.value = ''; \}\}>RESET<\/button>\s*<\/div>\s*)/;
    
    const btnMatch = app.match(resetRegex);
    
    if (btnMatch) {
        // Strip out the extra </div> at the end of blockToMove because that belonged to the right column's flex container
        // Wait, the original block has:
        // </div> (left column card end)
        // </div> (right column flex gap 1rem wrapper end)
        // Let's NOT use regex parsing for closing tags, let's look precisely at what's in the file.
        // Actually, let's just insert it before the closing </div> of the "Main Input Card".
        
        // The btn block ends with `</div>` which closes the button container. Then there is `</div>` which closes Main Input Card.
        // If we inject right after the button container `</div>`, we inject it inside the Main Input Card.
        
        // Wait, `blockToMove` matched up to the 4th `</div>`.
        // Let's strip the last `</div>` from it so we don't break the layout.
        // Actually, the matched block ends with `</div>\s*</div>\s*</div>\s*</div>\s*`.
        // The first `</div>` closes `display: 'flex'` for denda.
        // The second `</div>` closes `TARIF MOBIL` card.
        // The third `</div>` closes `display: 'grid'` for both cards.
        // The fourth `</div>` closes `premium-glass` column! Wait, my extraction took the closing tag for the `premium-glass` card!
        // So the right side would be broken!
        
        // Let me just restore App.jsx from Git and apply it cleanly, or carefully extract.
        
        // Let's extract exactly:
        // `{/* Info Tarif Tambahan */}` to `<div className="pos-summary-card"`
    }
}
