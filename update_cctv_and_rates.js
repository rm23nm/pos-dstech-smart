const fs = require('fs');

const appFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let app = fs.readFileSync(appFile, 'utf8');

// 1. Increase CCTV size
app = app.replace(
    /height: '180px'/g,
    "height: '320px'"
);

// 2. Insert Rates info into Ringkasan Tagihan
const ratesUI = `                      <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,1)', paddingBottom: '0.5rem', borderBottom: '1px dashed rgba(255,255,255,0.2)' }}>
                        <span>Denda Tiket Hilang</span>
                        <span style={{ color: 'white', fontWeight: 600 }}>Rp 0</span>
                      </div>
                      
                      {/* Info Tarif Tambahan */}
                      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0.5rem', marginTop: '0.5rem' }}>
                        <div style={{ background: 'rgba(255,255,255,0.05)', padding: '10px', borderRadius: '8px', border: '1px solid rgba(255,255,255,0.1)' }}>
                          <div style={{ fontSize: '0.75rem', fontWeight: 700, color: 'var(--primary)', marginBottom: '8px', textAlign: 'center' }}>TARIF MOTOR</div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.75rem', color: 'rgba(255,255,255,0.9)', marginBottom: '4px' }}>
                            <span>Dasar</span><span style={{color:'white', fontWeight:600}}>Rp {motorRate?.base?.toLocaleString() || 0}</span>
                          </div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.75rem', color: 'rgba(255,255,255,0.9)', marginBottom: '4px' }}>
                            <span>Per Jam</span><span style={{color:'white', fontWeight:600}}>Rp {motorRate?.hourly?.toLocaleString() || 0}</span>
                          </div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.75rem', color: 'rgba(255,255,255,0.9)', marginBottom: '4px' }}>
                            <span>Maksimal</span><span style={{color:'white', fontWeight:600}}>Tak Terbatas</span>
                          </div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.75rem', color: 'rgba(255,255,255,0.9)' }}>
                            <span>Denda</span><span style={{color:'var(--danger)', fontWeight:600}}>Rp {lostTicketFineMotor?.toLocaleString() || 0}</span>
                          </div>
                        </div>

                        <div style={{ background: 'rgba(255,255,255,0.05)', padding: '10px', borderRadius: '8px', border: '1px solid rgba(255,255,255,0.1)' }}>
                          <div style={{ fontSize: '0.75rem', fontWeight: 700, color: 'var(--primary)', marginBottom: '8px', textAlign: 'center' }}>TARIF MOBIL</div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.75rem', color: 'rgba(255,255,255,0.9)', marginBottom: '4px' }}>
                            <span>Dasar</span><span style={{color:'white', fontWeight:600}}>Rp {mobilRate?.base?.toLocaleString() || 0}</span>
                          </div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.75rem', color: 'rgba(255,255,255,0.9)', marginBottom: '4px' }}>
                            <span>Per Jam</span><span style={{color:'white', fontWeight:600}}>Rp {mobilRate?.hourly?.toLocaleString() || 0}</span>
                          </div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.75rem', color: 'rgba(255,255,255,0.9)', marginBottom: '4px' }}>
                            <span>Maksimal</span><span style={{color:'white', fontWeight:600}}>Tak Terbatas</span>
                          </div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.75rem', color: 'rgba(255,255,255,0.9)' }}>
                            <span>Denda</span><span style={{color:'var(--danger)', fontWeight:600}}>Rp {lostTicketFineMobil?.toLocaleString() || 0}</span>
                          </div>
                        </div>
                      </div>
                    </div>`;

const searchPattern = /<div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba\(255,255,255,1\)' }}>\s*<span>Denda Tiket Hilang<\/span>\s*<span style={{ color: 'white', fontWeight: 600 }}>Rp 0<\/span>\s*<\/div>\s*<\/div>/;

if (!app.includes('TARIF MOTOR')) {
    app = app.replace(searchPattern, ratesUI);
}

fs.writeFileSync(appFile, app);
console.log("Updated App.jsx with larger CCTV and Rates UI");
