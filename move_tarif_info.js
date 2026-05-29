const fs = require('fs');

const appFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let app = fs.readFileSync(appFile, 'utf8');

const targetToRemove = `                      {/* Info Tarif Tambahan */}
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

if (app.includes(targetToRemove)) {
    // Remove from original position
    app = app.replace(targetToRemove, '');

    // The buttons block in the left column
    const buttonsBlock = `                      <button className="btn btn-outline" style={{ color: 'white', borderColor: 'rgba(255,255,255,0.1)', padding: '1rem 1.5rem' }} onClick={() => { setPosData(null); setManualPlate(''); if(scanInputRef.current) scanInputRef.current.value = ''; }}>RESET</button>
                    </div>`;

    const replacement = `                      <button className="btn btn-outline" style={{ color: 'white', borderColor: 'rgba(255,255,255,0.1)', padding: '1rem 1.5rem' }} onClick={() => { setPosData(null); setManualPlate(''); if(scanInputRef.current) scanInputRef.current.value = ''; }}>RESET</button>
                    </div>
                    
${targetToRemove}`;

    app = app.replace(buttonsBlock, replacement);
    
    fs.writeFileSync(appFile, app);
    console.log("Successfully moved Tarif info to the left side under buttons");
} else {
    console.log("Could not find the target block to move.");
}
