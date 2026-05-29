const fs = require('fs');
const file = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let content = fs.readFileSync(file, 'utf8');

// Fix the broken one first
content = content.replace(/\$\{API_BASE\}\/([^,)]+)/g, '`${API_BASE}/$1`');

// Fix any remaining old bad syntaxes: '` + API_BASE + `/some/path'
content = content.replace(/'` \+ API_BASE \+ `\/([^']+)'/g, '`${API_BASE}/$1`');

fs.writeFileSync(file, content);
console.log("Fixed App.jsx");
