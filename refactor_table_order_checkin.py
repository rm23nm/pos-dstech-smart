import re

with open("app/Http/Controllers/TableOrderController.php", "r") as f:
    content = f.read()

# Replace the TargetKategori and DiskonBelanja from the select
old_select = """->select('customer_memberships.*', 'member_packages.KategoriPaket', 'member_packages.Tipe', 'member_packages.DiskonBelanja', 'itemmaster.NamaItem', 'member_packages.TargetKategori')"""
new_select = """->select('customer_memberships.*', 'member_packages.KategoriPaket', 'member_packages.Tipe', 'member_packages.KelompokLampu', 'itemmaster.NamaItem')"""
content = content.replace(old_select, new_select)

# Replace the second select
old_select2 = """->select('customer_memberships.*', 'member_packages.KategoriPaket', 'itemmaster.NamaItem', 'member_packages.TargetKategori')"""
new_select2 = """->select('customer_memberships.*', 'member_packages.KategoriPaket', 'itemmaster.NamaItem', 'member_packages.KelompokLampu')"""
content = content.replace(old_select2, new_select2)

# Replace the validation loop in storePaket
old_loop = """                $validMembership = null;
                foreach ($activeMemberships as $am) {
                    if ($am->KategoriPaket !== 'HIBURAN') continue;
                    
                    // Validate category
                    if ($am->TargetKategori) {
                        if (strtolower(trim($am->TargetKategori)) === $namaGrupClean) {
                            $validMembership = $am;
                            break;
                        }
                    } else {
                        // Fallback to name checking
                        $namaPaket = strtolower($am->NamaItem);
                        if (strpos($namaPaket, $namaGrupClean) !== false) {
                            $validMembership = $am;
                            break;
                        }
                    }
                }"""

new_loop = """                $validMembership = null;
                foreach ($activeMemberships as $am) {
                    if ($am->KategoriPaket !== 'HIBURAN') continue;
                    
                    // Validate KelompokLampu
                    if (empty($am->KelompokLampu) || $am->KelompokLampu == $table->KelompokLampu) {
                        $validMembership = $am;
                        break;
                    }
                }"""
content = content.replace(old_loop, new_loop)


with open("app/Http/Controllers/TableOrderController.php", "w") as f:
    f.write(content)

print("TableOrderController checkin logic updated!")
