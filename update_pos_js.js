const fs = require('fs');
const path = require('path');

const posDir = path.join(__dirname, 'resources', 'views', 'Transaksi', 'Penjualan', 'PoS');

function processDir(dir) {
    const files = fs.readdirSync(dir);
    for (const file of files) {
        const fullPath = path.join(dir, file);
        if (fs.statSync(fullPath).isDirectory()) continue;
        if (!fullPath.endsWith('.blade.php')) continue;

        let content = fs.readFileSync(fullPath, 'utf8');
        
        const searchRegex = /if\s*\(\s*data\.snap_token\s*\)\s*\{\s*snap\.pay\(\s*data\.snap_token/;
        if (searchRegex.test(content)) {
            const replacement = `if (data.provider == 'xendit' && data.qr_string) {
                Swal.fire({
                    title: 'Scan QRIS',
                    html: '<img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent(data.qr_string) + '" /><br><br><p>Tunggu hingga Pelanggan berhasil membayar.</p>',
                    showCancelButton: true,
                    confirmButtonText: 'Selesai & Tutup Transaksi',
                    cancelButtonText: 'Batal',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#NomorRefrensiPembayaran').val(data.order_id);
                        SaveData(Status, ButonObject, ButtonDefaultText);
                    } else {
                        Swal.fire('Dibatalkan', 'Transaksi dibatalkan', 'error');
                    }
                });
            } else if (data.snap_token) {
                snap.pay(data.snap_token`;
            
            content = content.replace(searchRegex, replacement);
            fs.writeFileSync(fullPath, content);
            console.log("Updated: " + fullPath);
        }
    }
}

processDir(posDir);
