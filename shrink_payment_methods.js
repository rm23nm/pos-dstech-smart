const fs = require('fs');

// 1. App.jsx - METODE PEMBAYARAN spacing
const appFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let app = fs.readFileSync(appFile, 'utf8');

const targetApp = `<div style={{ marginTop: '1.5rem' }}>
                      <label style={{ color: 'rgba(255,255,255,0.9)', fontSize: '0.7rem', fontWeight: 600, display: 'block', marginBottom: '1rem' }}>METODE PEMBAYARAN</label>
                      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '1rem' }}>`;

const newApp = `<div style={{ marginTop: '0.5rem' }}>
                      <label style={{ color: 'white', fontSize: '0.75rem', fontWeight: 700, display: 'block', marginBottom: '0.5rem' }}>METODE PEMBAYARAN</label>
                      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '0.5rem' }}>`;

if (app.includes(targetApp)) {
    app = app.replace(targetApp, newApp);
    fs.writeFileSync(appFile, app);
    console.log("App.jsx updated");
} else {
    // If exact match fails, use regex
    let updated = false;
    app = app.replace(
        /<div style={{ marginTop: '1\.5rem' }}>\s*<label style={{ color: 'rgba\(255,255,255,0\.9\)', fontSize: '0\.7rem', fontWeight: 600, display: 'block', marginBottom: '1rem' }}>METODE PEMBAYARAN<\/label>\s*<div style={{ display: 'grid', gridTemplateColumns: 'repeat\(3, 1fr\)', gap: '1rem' }}>/,
        `<div style={{ marginTop: '0.5rem' }}>
                      <label style={{ color: 'white', fontSize: '0.75rem', fontWeight: 700, display: 'block', marginBottom: '0.5rem' }}>METODE PEMBAYARAN</label>
                      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '0.5rem' }}>`
    );
    fs.writeFileSync(appFile, app);
    console.log("App.jsx updated via regex");
}


// 2. index.css - payment-method-btn padding
const cssFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/index.css';
let css = fs.readFileSync(cssFile, 'utf8');

const targetCss = `.payment-method-btn {
  border: 2px solid var(--border);
  background: var(--bg-card);
  padding: 1rem;
  border-radius: 12px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;`;

const newCss = `.payment-method-btn {
  border: 2px solid var(--border);
  background: var(--bg-card);
  padding: 0.5rem;
  border-radius: 12px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;`;

if (css.includes(targetCss)) {
    css = css.replace(targetCss, newCss);
    fs.writeFileSync(cssFile, css);
    console.log("index.css updated");
} else {
    // regex
    css = css.replace(/padding: 1rem;\s*border-radius: 12px;\s*display: flex;\s*flex-direction: column;\s*align-items: center;\s*gap: 0\.5rem;/g, 'padding: 0.5rem;\n  border-radius: 12px;\n  display: flex;\n  flex-direction: column;\n  align-items: center;\n  gap: 0.25rem;');
    fs.writeFileSync(cssFile, css);
    console.log("index.css updated via regex");
}
