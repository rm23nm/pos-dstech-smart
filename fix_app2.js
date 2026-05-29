const fs = require('fs');
const file = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let content = fs.readFileSync(file, 'utf8');

// Fix double backticks that might have been introduced
content = content.replace(/``\$\{API_BASE\}/g, '`${API_BASE}');
content = content.replace(/\$\{API_BASE\}``/g, '${API_BASE}`');
content = content.replace(/``/g, '`');

fs.writeFileSync(file, content);
console.log("Cleaned up App.jsx");
