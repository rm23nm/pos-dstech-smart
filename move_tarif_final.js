const fs = require('fs');

const appFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let app = fs.readFileSync(appFile, 'utf8');

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
    // 1. Remove it
    app = app.replace(blockToRemove, '');
    
    // 2. Insert it after the RESET button's parent div
    const parts = app.split(/RESET<\/button>\s*<\/div>/);
    if (parts.length > 1) {
        // parts[0] + "RESET</button>\n                    </div>\n" + blockToRemove + parts[1]
        // But wait! We need to make sure we only split on the POS reset button.
        // Let's use replace with regex.
        app = app.replace(
            /(RESET<\/button>\s*<\/div>)/,
            `$1\n${blockToRemove}`
        );
        fs.writeFileSync(appFile, app);
        console.log("Moved successfully.");
    } else {
        console.log("Could not split by reset button.");
    }
} else {
    console.log("Block to remove not found.");
}
