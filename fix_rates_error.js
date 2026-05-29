const fs = require('fs');

const appFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let app = fs.readFileSync(appFile, 'utf8');

if (!app.includes('const [motorRate')) {
    app = app.replace(
        /const \[globalAppName, setGlobalAppName\].*?;/,
        `$&
  const [motorRate, setMotorRate] = useState({base: 2000, hourly: 1000});
  const [mobilRate, setMobilRate] = useState({base: 5000, hourly: 2000});`
    );

    const loadRatesScript = `
      try {
        const rateRes = await axios.get(\`\${API_BASE}/api/parking/rates\`);
        const rates = rateRes.data;
        const motor = rates.find(r => r.vehicle_type === 'Motor');
        const mobil = rates.find(r => r.vehicle_type === 'Mobil');
        if (motor) setMotorRate({ base: motor.base_rate, hourly: motor.hourly_rate });
        if (mobil) setMobilRate({ base: mobil.base_rate, hourly: mobil.hourly_rate });
      } catch (err) {
        console.error('Failed to load rates');
      }
    `;

    app = app.replace(
        /setTenantData\(res\.data\);\s*}/,
        `setTenantData(res.data);\n        }\n${loadRatesScript}`
    );
}

// Ensure settings UI uses the correct layout for Rates setting
const oldRatesUI = `                  <div className="card" style={{ background: 'rgba(0,0,0,0.03)' }}>
                    <h4>Motor</h4>
                    <div style={{ fontSize: '0.8rem', color: 'var(--text-muted)' }}>Dasar: Rp 2.000 | Per Jam: Rp 1.000</div>
                  </div>
                  <div className="card" style={{ background: 'rgba(0,0,0,0.03)' }}>
                    <h4>Mobil</h4>
                    <div style={{ fontSize: '0.8rem', color: 'var(--text-muted)' }}>Dasar: Rp 5.000 | Per Jam: Rp 2.000</div>
                  </div>`;

if (app.includes(oldRatesUI)) {
    const newRatesUI = `                  <div className="card" style={{ background: 'rgba(0,0,0,0.03)', border: '1px solid rgba(0, 80, 157, 0.2)' }}>
                    <h4 style={{ color: 'var(--primary)' }}>Tarif Motor</h4>
                    <div style={{ display: 'flex', gap: '0.5rem', marginTop: '0.5rem' }}>
                      <input type="number" id="base-motor" placeholder="Dasar" className="card" style={{ flex: 1, padding: '0.4rem', fontSize: '0.9rem' }} defaultValue={motorRate?.base} />
                      <input type="number" id="hourly-motor" placeholder="Per Jam" className="card" style={{ flex: 1, padding: '0.4rem', fontSize: '0.9rem' }} defaultValue={motorRate?.hourly} />
                      <button className="btn btn-primary" style={{ fontSize: '0.7rem' }} onClick={async () => {
                        const b = document.getElementById('base-motor').value;
                        const h = document.getElementById('hourly-motor').value;
                        try {
                          await axios.post(\`\${API_BASE}/api/parking/rates\`, { vehicle_type: 'Motor', base_rate: b, hourly_rate: h }, { headers: { 'Tenant-Id': currentUser?.tenant_id || 1 } });
                          alert('Tarif Motor Updated!');
                        } catch (err) { alert('Gagal'); }
                      }}>SET</button>
                    </div>
                  </div>
                  <div className="card" style={{ background: 'rgba(0,0,0,0.03)', border: '1px solid rgba(0, 80, 157, 0.2)' }}>
                    <h4 style={{ color: 'var(--primary)' }}>Tarif Mobil</h4>
                    <div style={{ display: 'flex', gap: '0.5rem', marginTop: '0.5rem' }}>
                      <input type="number" id="base-mobil" placeholder="Dasar" className="card" style={{ flex: 1, padding: '0.4rem', fontSize: '0.9rem' }} defaultValue={mobilRate?.base} />
                      <input type="number" id="hourly-mobil" placeholder="Per Jam" className="card" style={{ flex: 1, padding: '0.4rem', fontSize: '0.9rem' }} defaultValue={mobilRate?.hourly} />
                      <button className="btn btn-primary" style={{ fontSize: '0.7rem' }} onClick={async () => {
                        const b = document.getElementById('base-mobil').value;
                        const h = document.getElementById('hourly-mobil').value;
                        try {
                          await axios.post(\`\${API_BASE}/api/parking/rates\`, { vehicle_type: 'Mobil', base_rate: b, hourly_rate: h }, { headers: { 'Tenant-Id': currentUser?.tenant_id || 1 } });
                          alert('Tarif Mobil Updated!');
                        } catch (err) { alert('Gagal'); }
                      }}>SET</button>
                    </div>
                  </div>`;
    app = app.replace(oldRatesUI, newRatesUI);
}

fs.writeFileSync(appFile, app);
console.log("Fixed motorRate error");
