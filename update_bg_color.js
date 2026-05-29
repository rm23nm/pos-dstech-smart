const fs = require('fs');
const file = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let content = fs.readFileSync(file, 'utf8');

// Replace the POS background gradient to match the Login background
content = content.replace(
    /background: 'linear-gradient\(135deg, rgba\(227, 6, 19, 0\.9\) 0%, rgba\(0, 80, 157, 0\.95\) 100%\)'/g,
    "background: 'linear-gradient(135deg, rgba(0, 80, 157, 0.95) 0%, rgba(227, 6, 19, 0.9) 100%)'"
);

// Apply it to the main dashboard as well just in case they meant the whole application background.
content = content.replace(
    /'linear-gradient\(135deg, #f1f5f9 0%, #e2e8f0 100%\)'/g,
    "'linear-gradient(135deg, rgba(0, 80, 157, 0.1), rgba(227, 6, 19, 0.1))'"
);

fs.writeFileSync(file, content);
console.log("Updated colors in App.jsx");
