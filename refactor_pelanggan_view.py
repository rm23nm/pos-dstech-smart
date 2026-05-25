import re

with open("resources/views/master/BussinessPartner/Pelanggan-Input.blade.php", "r") as f:
    content = f.read()

old_th = """  															<th>Nama Paket</th>
  															<th>Valid Until</th>"""
new_th = """  															<th>Nama Paket</th>
  															<th>Berlaku Untuk</th>
  															<th>Valid Until</th>"""
content = content.replace(old_th, new_th)

old_td = """  																	<td>{{ $cm->NamaItem }}</td>
  																	<td>{{ \Carbon\Carbon::parse($cm->ValidUntil)->format('d-m-Y H:i') }}</td>"""
new_td = """  																	<td>{{ $cm->NamaItem }}</td>
  																	<td>{{ $cm->KelompokLampuNama ? $cm->KelompokLampuNama : 'Semua Area' }}</td>
  																	<td>{{ \Carbon\Carbon::parse($cm->ValidUntil)->format('d-m-Y H:i') }}</td>"""
content = content.replace(old_td, new_td)

with open("resources/views/master/BussinessPartner/Pelanggan-Input.blade.php", "w") as f:
    f.write(content)

print("Pelanggan-Input.blade.php updated")
