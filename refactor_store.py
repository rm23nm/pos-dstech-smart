import re

with open("app/Http/Controllers/TableOrderController.php", "r") as f:
    content = f.read()

# Replace the loop in TableOrderController@store (around line 1800-2000, or wherever it is)
old_loop_2 = """                foreach ($activeMemberships as $am) {
                    if ($am->KategoriPaket !== 'HIBURAN') continue;
                    $valid = false;
                    if ($am->TargetKategori) {
                        if (strtolower(trim($am->TargetKategori)) === $namaGrupClean) $valid = true;
                    } else {
                        if (strpos(strtolower($am->NamaItem), $namaGrupClean) !== false) $valid = true;
                    }
                    if ($valid && $am->maxTimePerPlay > 0) {
                        $maxMinutes = $am->maxTimePerPlay * 60;
                        break;
                    }
                }"""

new_loop_2 = """                foreach ($activeMemberships as $am) {
                    if ($am->KategoriPaket !== 'HIBURAN') continue;
                    $valid = false;
                    
                    if (empty($am->KelompokLampu) || $am->KelompokLampu == $table->KelompokLampu) {
                        $valid = true;
                    }

                    if ($valid && $am->maxTimePerPlay > 0) {
                        $maxMinutes = $am->maxTimePerPlay * 60;
                        break;
                    }
                }"""

content = content.replace(old_loop_2, new_loop_2)

with open("app/Http/Controllers/TableOrderController.php", "w") as f:
    f.write(content)

print("TableOrderController @store logic updated!")
