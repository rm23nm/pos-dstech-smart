import re

with open("resources/views/master/memberpackage/index.blade.php", "r") as f:
    content = f.read()

# Make sure we didn't break the form serialization or something.
# I'll just add a console.log to editPaket to help debug if it fails on their browser.
old_js = """    function editPaket(id) {
        $.get('/master/memberpackage/' + id, function(data) {"""

new_js = """    function editPaket(id) {
        console.log("Editing package: ", id);
        $.get('/master/memberpackage/' + id, function(data) {
            console.log("Data received: ", data);"""

content = content.replace(old_js, new_js)

old_submit = """    $('#formPaket').on('submit', function(e) {
        e.preventDefault();
        $('#btnSave').prop('disabled', true).text('Menyimpan...');"""

new_submit = """    $('#formPaket').on('submit', function(e) {
        e.preventDefault();
        console.log("Submitting form...");
        $('#btnSave').prop('disabled', true).text('Menyimpan...');"""

content = content.replace(old_submit, new_submit)

with open("resources/views/master/memberpackage/index.blade.php", "w") as f:
    f.write(content)

print("Debug logs added to index.blade.php")
