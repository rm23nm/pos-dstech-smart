const fs = require('fs');
const file = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let content = fs.readFileSync(file, 'utf8');

// Change POS background gradient
content = content.replace(
    /background: 'linear-gradient\(135deg, rgba\(15, 23, 42, 0\.95\) 0%, rgba\(30, 41, 59, 0\.8\) 100%\)'/g,
    "background: 'linear-gradient(135deg, rgba(227, 6, 19, 0.9) 0%, rgba(0, 80, 157, 0.95) 100%)'"
);

// Make text brighter by replacing opacity colors
content = content.replace(/rgba\(255,255,255,0\.4\)/g, 'rgba(255,255,255,0.9)');
content = content.replace(/rgba\(255,255,255,0\.5\)/g, 'rgba(255,255,255,0.9)');
content = content.replace(/rgba\(255,255,255,0\.6\)/g, 'rgba(255,255,255,1)');
content = content.replace(/rgba\(255,255,255,0\.05\)/g, 'rgba(255,255,255,0.15)'); // Make input backgrounds slightly more visible
content = content.replace(/rgba\(255,255,255,0\.03\)/g, 'rgba(255,255,255,0.1)'); // Make panel backgrounds more visible

fs.writeFileSync(file, content);
console.log("Updated colors in App.jsx");
