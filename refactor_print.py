import re

with open("resources/views/Transaksi/Penjualan/PoS/PrintThermalTiket.blade.php", "r") as f:
    content = f.read()

old_block = """    @if(count($tickets) == 0)
        <div style="text-align: center; margin-top: 50px; font-family: sans-serif;">
            <h3>Tidak ada tiket fisik untuk transaksi ini.</h3>
            <p>(Paket Member / Item Non-Tiket tidak memiliki tiket masuk)</p>
        </div>"""

new_block = """    @if(count($tickets) == 0)
        <div class="ticket">
            @if(!empty($company->icon))
                <img src="{{ str_starts_with($company->icon, 'http') ? $company->icon : asset('storage/' . $company->icon) }}" alt="Logo" class="company-logo">
            @endif
            <div class="company-name">{{ $company->NamaPartner ?? 'Perusahaan' }}</div>
            <div class="company-contact">
                {{ $company->AlamatTagihan ?? '' }}<br>
                Telp: {{ $company->NoHP ?? $company->NoTlp ?? '-' }}
            </div>
            
            <div class="title">Struk Pembelian</div>
            
            <div class="info" style="margin-bottom: 10px;">
                <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
                    <tr>
                        <td>No:</td>
                        <td style="text-align: right;">{{ $header->NoTransaksi }}</td>
                    </tr>
                    <tr>
                        <td>Tgl:</td>
                        <td style="text-align: right;">{{ \Carbon\Carbon::parse($header->TglTransaksi)->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Oleh:</td>
                        <td style="text-align: right;">{{ Auth::user()->name }}</td>
                    </tr>
                    @if($header->KodePelanggan && $header->KodePelanggan != 'CASH')
                    <tr>
                        <td>Pelanggan:</td>
                        <td style="text-align: right;">{{ $header->NamaPelanggan ?? $header->KodePelanggan }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <div style="border-top: 1px dashed #000; margin-bottom: 5px;"></div>

            <table style="width: 100%; font-size: 11px; text-align: left; border-collapse: collapse;">
                @foreach($details as $detail)
                <tr>
                    <td colspan="3">{{ $detail->NamaItem }}</td>
                </tr>
                <tr>
                    <td>{{ $detail->Qty }}x</td>
                    <td style="text-align: right;">{{ number_format($detail->Harga) }}</td>
                    <td style="text-align: right;">{{ number_format($detail->Qty * $detail->Harga) }}</td>
                </tr>
                @endforeach
            </table>

            <div style="border-top: 1px dashed #000; margin-top: 5px; margin-bottom: 5px;"></div>

            <table style="width: 100%; font-size: 11px; font-weight: bold; text-align: left; border-collapse: collapse;">
                <tr>
                    <td>Subtotal:</td>
                    <td style="text-align: right;">Rp {{ number_format($header->TotalTransaksi) }}</td>
                </tr>
                @if($header->TotalPotongan > 0)
                <tr>
                    <td>Diskon:</td>
                    <td style="text-align: right;">- Rp {{ number_format($header->TotalPotongan) }}</td>
                </tr>
                @endif
                @if($header->TotalPajak > 0)
                <tr>
                    <td>Pajak:</td>
                    <td style="text-align: right;">Rp {{ number_format($header->TotalPajak) }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding-top: 5px; font-size: 12px;">Total:</td>
                    <td style="text-align: right; padding-top: 5px; font-size: 12px;">Rp {{ number_format($header->TotalPembelian) }}</td>
                </tr>
                <tr>
                    <td>Bayar:</td>
                    <td style="text-align: right;">Rp {{ number_format($header->TotalPembayaran) }}</td>
                </tr>
                <tr>
                    <td>Kembali:</td>
                    <td style="text-align: right;">Rp {{ number_format($header->Kembalian) }}</td>
                </tr>
            </table>

            <div class="footer">
                Terima kasih atas pembelian Anda!<br>
                Harap simpan struk ini sebagai bukti pembayaran.
            </div>
        </div>"""

content = content.replace(old_block, new_block)

with open("resources/views/Transaksi/Penjualan/PoS/PrintThermalTiket.blade.php", "w") as f:
    f.write(content)

print("Replaced successfully!")
