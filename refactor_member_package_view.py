import re

with open("resources/views/master/memberpackage/index.blade.php", "r") as f:
    content = f.read()

# Add to table header
old_th = """                            <th>Kategori</th>
                            <th>Tipe</th>"""
new_th = """                            <th>Kategori</th>
                            <th>Berlaku Untuk</th>
                            <th>Tipe</th>"""
content = content.replace(old_th, new_th)

# Add to table body
old_td = """                            <td><span class="badge bg-secondary">{{ $p->KategoriPaket }}</span></td>
                            <td>
                                @if($p->Tipe == 'DISCOUNT')"""
new_td = """                            <td><span class="badge bg-secondary">{{ $p->KategoriPaket }}</span></td>
                            <td>
                                @php
                                    $kl = collect($kelompokLampu)->where('KodeKelompok', $p->KelompokLampu)->first();
                                @endphp
                                {{ $kl ? $kl->NamaKelompok : 'Semua Area' }}
                            </td>
                            <td>
                                @if($p->Tipe == 'DISCOUNT')"""
content = content.replace(old_td, new_td)

# Add to modal form
old_form = """                <div class="col-md-4 mb-3">
                    <label>Peruntukan Paket <span class="text-danger">*</span></label>
                    <select name="KategoriPaket" id="KategoriPaket" class="form-control" onchange="toggleRules()" required>
                        <option value="HIBURAN">Khusus Hiburan (Billiard/Futsal)</option>
                        <option value="RETAIL">Khusus Retail</option>
                        <option value="FNB">Khusus F&B</option>
                    </select>
                </div>"""
new_form = """                <div class="col-md-4 mb-3">
                    <label>Peruntukan Paket <span class="text-danger">*</span></label>
                    <select name="KategoriPaket" id="KategoriPaket" class="form-control" onchange="toggleRules()" required>
                        <option value="HIBURAN">Khusus Hiburan (Billiard/Futsal)</option>
                        <option value="RETAIL">Khusus Retail</option>
                        <option value="FNB">Khusus F&B</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label>Berlaku Untuk Lapangan/Meja</label>
                    <select name="KelompokLampu" id="KelompokLampu" class="form-control">
                        <option value="">Semua (Bebas)</option>
                        @foreach($kelompokLampu as $kl)
                            <option value="{{ $kl->KodeKelompok }}">{{ $kl->NamaKelompok }}</option>
                        @endforeach
                    </select>
                </div>"""
content = content.replace(old_form, new_form)

# Add to edit JS
old_js = """            $('#KategoriPaket').val(data.KategoriPaket);
            $('#Tipe').val(data.Tipe);"""
new_js = """            $('#KategoriPaket').val(data.KategoriPaket);
            $('#KelompokLampu').val(data.KelompokLampu);
            $('#Tipe').val(data.Tipe);"""
content = content.replace(old_js, new_js)

# Add to add JS (reset KelompokLampu)
old_js2 = """    function tambahPaket() {
        $('#formPaket')[0].reset();
        $('#paket_id').val('');"""
new_js2 = """    function tambahPaket() {
        $('#formPaket')[0].reset();
        $('#paket_id').val('');
        $('#KelompokLampu').val('');"""
content = content.replace(old_js2, new_js2)


with open("resources/views/master/memberpackage/index.blade.php", "w") as f:
    f.write(content)

print("index.blade.php updated!")
