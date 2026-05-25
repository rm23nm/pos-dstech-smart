import re

with open("app/Http/Controllers/TableOrderController.php", "r") as f:
    content = f.read()

old_query = """              $titiklampu = DB::table('titiklampu')
                  ->selectRaw("
                      titiklampu.id,
                      titiklampu.Status,
                      titiklampu.RecordOwnerID,
                      titiklampu.ControllerID,"""

new_query = """              $titiklampu = DB::table('titiklampu')
                  ->selectRaw("
                      titiklampu.id,
                      titiklampu.Status,
                      titiklampu.RecordOwnerID,
                      titiklampu.ControllerID,
                      titiklampu.KelompokLampu,"""

content = content.replace(old_query, new_query)

with open("app/Http/Controllers/TableOrderController.php", "w") as f:
    f.write(content)

print("TableOrderController updated: Included KelompokLampu in ReadTitikLampuStatus")
