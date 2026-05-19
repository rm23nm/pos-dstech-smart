const fs = require('fs');

const content = fs.readFileSync('D:\\OneDrive\\My Project Aplikasi\\pos.dstechsmart.com\\resources\\views\\Transaksi\\Penjualan\\PoS\\NormalPoS_Premium.blade.php', 'utf8');

const lines = content.split('\n');
lines.forEach((line, idx) => {
  if (line.includes('_AllProducts') || line.includes('loadCatalogProducts')) {
    console.log(`Line ${idx + 1}: ${line.trim()}`);
  }
});
