import re

with open("resources/views/Transaksi/Penjualan/PoS/PrintThermalTiket.blade.php", "r") as f:
    content = f.read()

old_block = """                @if($header->TotalPotongan > 0)
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
                @endif"""

new_block = """                @if(isset($header->Potongan) && $header->Potongan > 0)
                <tr>
                    <td>Diskon:</td>
                    <td style="text-align: right;">- Rp {{ number_format($header->Potongan) }}</td>
                </tr>
                @endif
                @if(isset($header->Pajak) && $header->Pajak > 0)
                <tr>
                    <td>Pajak:</td>
                    <td style="text-align: right;">Rp {{ number_format($header->Pajak) }}</td>
                </tr>
                @endif"""

content = content.replace(old_block, new_block)

with open("resources/views/Transaksi/Penjualan/PoS/PrintThermalTiket.blade.php", "w") as f:
    f.write(content)

print("Replaced successfully!")
