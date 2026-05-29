const fs = require('fs');

const appFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let app = fs.readFileSync(appFile, 'utf8');

const targetContent = `                    <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
                      <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,1)' }}>
                        <span>Tarif Dasar</span>
                        <span style={{ color: 'white', fontWeight: 600 }}>Rp {posData?.total_amount?.toLocaleString() || 0}</span>
                      </div>
                      <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,1)' }}>
                        <span>Denda Tiket Hilang</span>
                        <span style={{ color: 'white', fontWeight: 600 }}>Rp 0</span>
                      </div>
                    </div>`;

const newContent = `                    <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
                      <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,1)', fontSize: '0.9rem' }}>
                        <span>Tarif Dasar Transaksi</span>
                        <span style={{ color: 'white', fontWeight: 600 }}>Rp {posData?.total_amount?.toLocaleString() || 0}</span>
                      </div>
                      <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,1)', fontSize: '0.9rem', paddingBottom: '0.5rem', borderBottom: '1px dashed rgba(255,255,255,0.2)' }}>
                        <span>Denda Tiket Hilang</span>
                        <span style={{ color: 'white', fontWeight: 600 }}>Rp 0</span>
                      </div>
                      
                      {/* Info Tarif Tambahan */}
                      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0.5rem', marginTop: '0.5rem' }}>
                        <div style={{ background: 'rgba(255,255,255,0.05)', padding: '8px', borderRadius: '8px' }}>
                          <div style={{ fontSize: '0.7rem', fontWeight: 700, color: 'var(--primary)', marginBottom: '4px' }}>TARIF MOTOR</div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.7rem', color: 'rgba(255,255,255,0.8)' }}>
                            <span>Dasar</span><span style={{color:'white', fontWeight:600}}>Rp {motorRate?.base?.toLocaleString() || 0}</span>
                          </div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.7rem', color: 'rgba(255,255,255,0.8)' }}>
                            <span>Berikutnya</span><span style={{color:'white', fontWeight:600}}>Rp {motorRate?.hourly?.toLocaleString() || 0}</span>
                          </div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.7rem', color: 'rgba(255,255,255,0.8)' }}>
                            <span>Maksimal</span><span style={{color:'white', fontWeight:600}}>Tidak Terbatas</span>
                          </div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.7rem', color: 'rgba(255,255,255,0.8)' }}>
                            <span>Denda</span><span style={{color:'var(--danger)', fontWeight:600}}>Rp {lostTicketFineMotor?.toLocaleString() || 0}</span>
                          </div>
                        </div>

                        <div style={{ background: 'rgba(255,255,255,0.05)', padding: '8px', borderRadius: '8px' }}>
                          <div style={{ fontSize: '0.7rem', fontWeight: 700, color: 'var(--primary)', marginBottom: '4px' }}>TARIF MOBIL</div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.7rem', color: 'rgba(255,255,255,0.8)' }}>
                            <span>Dasar</span><span style={{color:'white', fontWeight:600}}>Rp {mobilRate?.base?.toLocaleString() || 0}</span>
                          </div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.7rem', color: 'rgba(255,255,255,0.8)' }}>
                            <span>Berikutnya</span><span style={{color:'white', fontWeight:600}}>Rp {mobilRate?.hourly?.toLocaleString() || 0}</span>
                          </div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.7rem', color: 'rgba(255,255,255,0.8)' }}>
                            <span>Maksimal</span><span style={{color:'white', fontWeight:600}}>Tidak Terbatas</span>
                          </div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.7rem', color: 'rgba(255,255,255,0.8)' }}>
                            <span>Denda</span><span style={{color:'var(--danger)', fontWeight:600}}>Rp {lostTicketFineMobil?.toLocaleString() || 0}</span>
                          </div>
                        </div>
                      </div>
                    </div>`;

app = app.replace(targetContent, newContent);

fs.writeFileSync(appFile, app);
console.log("Added rates to Ringkasan Tagihan in App.jsx");
