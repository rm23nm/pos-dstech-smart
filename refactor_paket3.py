import os

ctrl_path = 'app/Http/Controllers/TableOrderController.php'
with open(ctrl_path, 'r') as f:
    ctrl_content = f.read()

old_select = """                    COALESCE(tableorderheader.TotalTerbayar, 0) as TotalPembayaran,
                    COALESCE(tableorderheader.NetTotal, 0) as NetTotal,
                    COALESCE(tkelompoklampu.NamaKelompok,'') AS NamaKelompok
                ")"""

new_select = """                    COALESCE(tableorderheader.TotalTerbayar, 0) as TotalPembayaran,
                    COALESCE(tableorderheader.NetTotal, 0) as NetTotal,
                    COALESCE(tkelompoklampu.NamaKelompok,'') AS NamaKelompok,
                    titiklampu.KelompokLampu
                ")"""

ctrl_content = ctrl_content.replace(old_select, new_select)

with open(ctrl_path, 'w') as f:
    f.write(ctrl_content)
