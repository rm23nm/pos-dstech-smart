import os

view_path = 'resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php'
with open(view_path, 'r') as f:
    view_content = f.read()

css_addition = """    .pp-btn-confirm:hover { opacity: 0.88; transform: translateY(-1px); }
    .pp-btn-confirm:disabled { background: #b0bec5; cursor: not-allowed; box-shadow: none; opacity: 0.7; }"""

view_content = view_content.replace(
    ".pp-btn-confirm:hover { opacity: 0.88; transform: translateY(-1px); }",
    css_addition
)

with open(view_path, 'w') as f:
    f.write(view_content)

os.system('C:\\xampp\\php\\php.exe artisan view:clear')
