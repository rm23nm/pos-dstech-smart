const fs = require('fs');

const appFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let app = fs.readFileSync(appFile, 'utf8');

// The block to extract starts with `{/* Info Tarif Tambahan */}` and ends before the first `</div>` that is followed by `<div className="pos-summary-card"`.
// Let's just use string literal replacements to be 100% safe.

const blockToRemove = `                      {/* Info Tarif Tambahan */}
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
                      </div>`;

if (app.includes(blockToRemove)) {
    app = app.replace(blockToRemove, '');

    const btnBlock = `                      <button className="btn btn-outline" style={{ color: 'white', borderColor: 'rgba(255,255,255,0.1)', padding: '1rem 1.5rem' }} onClick={() => { setPosData(null); setManualPlate(''); if(scanInputRef.current) scanInputRef.current.value = ''; }}>RESET</button>
                    </div>`;

    if (app.includes(btnBlock)) {
        app = app.replace(btnBlock, btnBlock + '\n' + blockToRemove);
        fs.writeFileSync(appFile, app);
        console.log("Moved successfully.");
    } else {
        console.log("Button block not found.");
    }
} else {
    console.log("Block to remove not found.");
}
