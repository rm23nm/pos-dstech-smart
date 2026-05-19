const fs = require('fs');

const premiumPath = 'D:\\OneDrive\\My Project Aplikasi\\pos.dstechsmart.com\\resources\\views\\Transaksi\\Penjualan\\PoS\\NormalPoS_Premium.blade.php';
const fnbPath = 'D:\\OneDrive\\My Project Aplikasi\\pos.dstechsmart.com\\resources\\views\\Transaksi\\Penjualan\\PoS\\FnBPoS.blade.php';

function findLines(filePath, searchTerms) {
  const content = fs.readFileSync(filePath, 'utf8');
  const lines = content.split('\n');
  console.log(`=== ${filePath} ===`);
  lines.forEach((line, idx) => {
    searchTerms.forEach(term => {
      if (line.includes(term)) {
        console.log(`Line ${idx + 1}: ${line.trim()}`);
      }
    });
  });
}

findLines(premiumPath, ['_CatalogSearch', '_Barcode']);
