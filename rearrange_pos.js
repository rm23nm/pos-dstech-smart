const fs = require('fs');

const appFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let app = fs.readFileSync(appFile, 'utf8');

// The block for PROSES TRANSAKSI and RESET
const buttonsBlock = `                    <div style={{ marginTop: 'auto', display: 'flex', gap: '1rem' }}>
                      <button className="btn btn-premium" style={{ flex: 1, padding: '1rem' }} onClick={async () => {
                        const id = scanInputRef.current?.value;
                        if (!id) return;
                        try {
                          const res = await axios.post(\`\${API_BASE}/api/parking/calculate\`, { identifier: id });
                          setPosData(res.data);
                          setManualPlate(res.data.transaction.plate_number || '');
                        } catch (err) { alert('Data tidak ditemukan'); }
                      }}>PROSES TRANSAKSI</button>
                      <button className="btn btn-outline" style={{ color: 'white', borderColor: 'rgba(255,255,255,0.1)', padding: '1rem 1.5rem' }} onClick={() => { setPosData(null); setManualPlate(''); if(scanInputRef.current) scanInputRef.current.value = ''; }}>RESET</button>
                    </div>`;

const newButtonsBlock = `                    <div style={{ display: 'flex', gap: '1rem' }}>
                      <button className="btn btn-premium" style={{ flex: 1, padding: '1rem' }} onClick={async () => {
                        const id = scanInputRef.current?.value;
                        if (!id) return;
                        try {
                          const res = await axios.post(\`\${API_BASE}/api/parking/calculate\`, { identifier: id });
                          setPosData(res.data);
                          setManualPlate(res.data.transaction.plate_number || '');
                        } catch (err) { alert('Data tidak ditemukan'); }
                      }}>PROSES TRANSAKSI</button>
                      <button className="btn btn-outline" style={{ color: 'white', borderColor: 'rgba(255,255,255,0.1)', padding: '1rem 1.5rem' }} onClick={() => { setPosData(null); setManualPlate(''); if(scanInputRef.current) scanInputRef.current.value = ''; }}>RESET</button>
                    </div>`;

// 1. Remove buttons from original position
app = app.replace(buttonsBlock, '');

// 2. The block for Ringkasan Tagihan
const ringkasanBlock = `                    <div style={{ display: 'flex', alignItems: 'center', gap: '12px', paddingBottom: '1rem', borderBottom: '1px solid rgba(255,255,255,0.15)' }}>
                      <DollarSign size={24} color="var(--primary)" />
                      <h2 style={{ color: 'white', fontSize: '1.25rem', fontWeight: 700, margin: 0 }}>RINGKASAN TAGIHAN</h2>
                    </div>
  
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
                      <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,1)' }}>
                        <span>Tarif Dasar</span>
                        <span style={{ color: 'white', fontWeight: 600 }}>Rp {posData?.total_amount?.toLocaleString() || 0}</span>
                      </div>
                      <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,1)' }}>
                        <span>Denda Tiket Hilang</span>
                        <span style={{ color: 'white', fontWeight: 600 }}>Rp 0</span>
                      </div>
                    </div>`;

const newRingkasanBlock = `
                    <div style={{ display: 'flex', alignItems: 'center', gap: '12px', paddingBottom: '0.5rem', borderBottom: '1px solid rgba(255,255,255,0.15)', marginTop: '0.5rem' }}>
                      <DollarSign size={18} color="var(--primary)" />
                      <h2 style={{ color: 'white', fontSize: '1rem', fontWeight: 700, margin: 0 }}>RINGKASAN TAGIHAN & TARIF</h2>
                    </div>
  
                    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
                      <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem', background: 'rgba(255,255,255,0.05)', padding: '10px', borderRadius: '8px' }}>
                        <div style={{ fontSize: '0.8rem', fontWeight: 700, color: 'var(--primary)', marginBottom: '5px' }}>INFO MOTOR</div>
                        <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,0.9)', fontSize: '0.75rem' }}>
                          <span>Tarif Dasar</span>
                          <span style={{ color: 'white', fontWeight: 600 }}>Rp {motorRate?.base?.toLocaleString()}</span>
                        </div>
                        <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,0.9)', fontSize: '0.75rem' }}>
                          <span>Per Jam</span>
                          <span style={{ color: 'white', fontWeight: 600 }}>Rp {motorRate?.hourly?.toLocaleString()}</span>
                        </div>
                        <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,0.9)', fontSize: '0.75rem' }}>
                          <span>Maksimal</span>
                          <span style={{ color: 'white', fontWeight: 600 }}>Tidak Terbatas</span>
                        </div>
                        <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,0.9)', fontSize: '0.75rem' }}>
                          <span>Denda Hilang</span>
                          <span style={{ color: 'var(--danger)', fontWeight: 600 }}>Rp {lostTicketFineMotor?.toLocaleString()}</span>
                        </div>
                      </div>

                      <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem', background: 'rgba(255,255,255,0.05)', padding: '10px', borderRadius: '8px' }}>
                        <div style={{ fontSize: '0.8rem', fontWeight: 700, color: 'var(--primary)', marginBottom: '5px' }}>INFO MOBIL</div>
                        <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,0.9)', fontSize: '0.75rem' }}>
                          <span>Tarif Dasar</span>
                          <span style={{ color: 'white', fontWeight: 600 }}>Rp {mobilRate?.base?.toLocaleString()}</span>
                        </div>
                        <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,0.9)', fontSize: '0.75rem' }}>
                          <span>Per Jam</span>
                          <span style={{ color: 'white', fontWeight: 600 }}>Rp {mobilRate?.hourly?.toLocaleString()}</span>
                        </div>
                        <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,0.9)', fontSize: '0.75rem' }}>
                          <span>Maksimal</span>
                          <span style={{ color: 'white', fontWeight: 600 }}>Tidak Terbatas</span>
                        </div>
                        <div style={{ display: 'flex', justifyContent: 'space-between', color: 'rgba(255,255,255,0.9)', fontSize: '0.75rem' }}>
                          <span>Denda Hilang</span>
                          <span style={{ color: 'var(--danger)', fontWeight: 600 }}>Rp {lostTicketFineMobil?.toLocaleString()}</span>
                        </div>
                      </div>
                    </div>
`;

// Remove ringkasan from right column
app = app.replace(ringkasanBlock, '');

// Now we need to insert `newButtonsBlock` and `newRingkasanBlock` at the end of the Left Column Main Input Card.
// The end of Main Input Card is after the `DURASI PARKIR` div block.

const targetInsert = `                        <div style={{ fontSize: '1.25rem', fontWeight: 800, color: 'var(--primary)' }}>{posData ? \`\${posData.duration_hours} Jam\` : '0 Jam'}</div>
                      </div>
                    </div>`;

app = app.replace(targetInsert, targetInsert + '\n' + newButtonsBlock + '\n' + newRingkasanBlock);

fs.writeFileSync(appFile, app);
console.log("Updated App.jsx successfully");
