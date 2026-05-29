const fs = require('fs');

const appFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let app = fs.readFileSync(appFile, 'utf8');

// 1. CCTV height
app = app.replace(
    /height: '260px'/g,
    "height: '200px'"
);

// 2. Info Tarif (Left Column) text size
// I used `0.75rem` for font sizes in Info Tarif. I'll bump them to `0.85rem` or `0.9rem`.
app = app.replace(/fontSize: '0\.75rem'/g, "fontSize: '0.9rem'");

// 3. Uang Diterima & Kembalian Box
const uangDiterimaPattern = /<div style={{ marginTop: '1rem', background: 'rgba\(0,0,0,0\.3\)', padding: '1rem', borderRadius: '16px', border: '1px solid rgba\(255,255,255,0\.15\)' }}>\s*<div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0\.5rem' }}>\s*<label style={{ color: 'rgba\(255,255,255,0\.9\)', fontSize: '0\.8rem', fontWeight: 600 }}>UANG DITERIMA<\/label>[\s\S]*?<div style={{ marginTop: '1\.5rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center', paddingTop: '1rem', borderTop: '1px dashed rgba\(255,255,255,0\.1\)' }}>\s*<span style={{ color: 'rgba\(255,255,255,0\.9\)', fontWeight: 600 }}>KEMBALIAN<\/span>\s*<span id="premium-change" style={{ fontSize: '1\.75rem', fontWeight: 900, color: 'var\(--primary\)' }}>Rp 0<\/span>\s*<\/div>\s*<\/div>/;

const newUangDiterima = `<div style={{ marginTop: '0.5rem', background: 'rgba(0,0,0,0.3)', padding: '0.75rem', borderRadius: '16px', border: '1px solid rgba(255,255,255,0.2)' }}>
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.2rem' }}>
                          <label style={{ color: 'white', fontSize: '0.85rem', fontWeight: 700 }}>UANG DITERIMA</label>
                          <span style={{ color: 'var(--primary)', fontSize: '0.7rem', fontWeight: 700 }}>F1: Rp 10rb · F2: Rp 50rb</span>
                        </div>
                        <input 
                          type="number" 
                          className="pos-input" 
                          style={{ width: '100%', textAlign: 'right', fontSize: '2rem', fontWeight: 700, border: 'none', background: 'transparent', color: 'white' }}
                          placeholder="0"
                          onChange={(e) => {
                            const cash = parseInt(e.target.value) || 0;
                            const change = cash - (posData?.total_amount || 0);
                            const disp = document.getElementById('premium-change');
                            if (disp) disp.innerText = change >= 0 ? \`Rp \${change.toLocaleString()}\` : 'Rp 0';
                          }}
                        />
                        <div style={{ marginTop: '0.2rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center', paddingTop: '0.5rem', borderTop: '1px dashed rgba(255,255,255,0.2)' }}>
                          <span style={{ color: 'white', fontWeight: 700, fontSize: '0.85rem' }}>KEMBALIAN</span>
                          <span id="premium-change" style={{ fontSize: '2rem', fontWeight: 900, color: 'var(--primary)' }}>Rp 0</span>
                        </div>
                      </div>`;

if (app.match(uangDiterimaPattern)) {
    app = app.replace(uangDiterimaPattern, newUangDiterima);
} else {
    console.log("Uang Diterima pattern not matched");
}

fs.writeFileSync(appFile, app);
console.log("App.jsx layout adjusted");
