import re

with open("resources/views/master/memberpackage/index.blade.php", "r") as f:
    content = f.read()

old_btn = """<button class="btn btn-sm btn-warning" onclick="editPaket({{ $p->id }})"><i class="fas fa-edit"></i></button>"""
new_btn = """<button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalPaket" onclick="editPaket({{ $p->id }})"><i class="fas fa-edit"></i></button>"""

content = content.replace(old_btn, new_btn)

with open("resources/views/master/memberpackage/index.blade.php", "w") as f:
    f.write(content)

print("Edit button updated with modal data attributes")
