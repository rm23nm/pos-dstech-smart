const fs = require('fs');

const content = fs.readFileSync('D:\\OneDrive\\My Project Aplikasi\\pos.dstechsmart.com\\resources\\views\\Transaksi\\Penjualan\\PoS\\FnBPoS.blade.php', 'utf8');

const lines = content.split('\n');
lines.forEach((line, idx) => {
  if (line.includes('img') || line.includes('Gambar') || line.includes('placeholder') || line.includes('src') || line.includes('url')) {
    console.log(`Line ${idx + 1}: ${line.trim()}`);
  }
});
