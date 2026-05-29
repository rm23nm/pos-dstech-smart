const fs = require('fs');

// 1. Update Vite Config for WebSocket Proxy
const viteFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/vite.config.js';
let vite = fs.readFileSync(viteFile, 'utf8');

if (!vite.includes("'/socket.io'")) {
    vite = vite.replace(
        /'\/api': 'http:\/\/localhost:4001'/,
        `'/api': 'http://localhost:4001',\n      '/socket.io': {\n        target: 'http://localhost:4001',\n        ws: true\n      }`
    );
    fs.writeFileSync(viteFile, vite);
    console.log("Updated vite.config.js with WebSocket proxy");
}

// 2. Adjust POS Layout in App.jsx so buttons are not cut off
const appFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let app = fs.readFileSync(appFile, 'utf8');

// Reduce CCTV height
app = app.replace(
    /height: '320px'/g,
    "height: '260px'"
);

// Reduce padding in Main Input Card to give more space
app = app.replace(
    /className="premium-glass" style={{ flex: 1, borderRadius: '20px', padding: '1.5rem', display: 'flex', flexDirection: 'column', gap: '1.25rem' }}/,
    `className="premium-glass" style={{ flex: 1, borderRadius: '20px', padding: '1rem', display: 'flex', flexDirection: 'column', gap: '0.75rem' }}`
);

fs.writeFileSync(appFile, app);
console.log("Updated App.jsx with reduced CCTV height and tighter padding");
