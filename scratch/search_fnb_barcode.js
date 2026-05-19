const fs = require('fs');

const content = fs.readFileSync('D:\\OneDrive\\My Project Aplikasi\\pos.dstechsmart.com\\resources\\views\\Transaksi\\Penjualan\\PoS\\FnBPoS.blade.php', 'utf8');

const lines = content.split('\n');
lines.forEach((line, idx) => {
  if (line.includes('_Barcode') && line.includes('keypress')) {
    console.log(`Line ${idx + 1}: ${line.trim()}`);
  }
});
