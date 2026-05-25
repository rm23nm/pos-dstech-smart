import re

with open("app/Http/Controllers/TableOrderController.php", "r") as f:
    content = f.read()

old_logic = """                  $maxMinutes = 60;
                  foreach ($activeMemberships as $am) {
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

new_logic = """                  $maxMinutes = 60;
                  $validMembershipFound = false;
                  
                  foreach ($activeMemberships as $am) {
                      if ($am->KategoriPaket !== 'HIBURAN') continue;
                      
                      if (empty($am->KelompokLampu) || $am->KelompokLampu == $table->KelompokLampu) {
                          $validMembershipFound = true;
                          if ($am->maxTimePerPlay > 0) {
                              $maxMinutes = $am->maxTimePerPlay * 60;
                          }
                          break;
                      }
                  }
                  
                  if (!$validMembershipFound) {
                      return response()->json(['success' => false, 'message' => 'Member memiliki paket aktif, tetapi tidak ada yang berlaku untuk kategori ' . $table->NamaKelompok . '.']);
                  }"""

content = content.replace(old_logic, new_logic)

with open("app/Http/Controllers/TableOrderController.php", "w") as f:
    f.write(content)

print("TableOrderController store logic updated to reject invalid packages!")
