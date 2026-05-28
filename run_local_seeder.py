import subprocess
import sys

# Run tinker command locally using XAMPP PHP
php_exe = r'C:\xampp\php\php.exe'
artisan = r'd:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\artisan'

# Read the PHP script
with open(r'd:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\create_demo_bengkel.php', 'r') as f:
    script = f.read()

# Use tinker --execute to run script
cmd = [php_exe, artisan, 'tinker', '--execute', f"require 'create_demo_bengkel.php';"]
result = subprocess.run(
    cmd,
    capture_output=True,
    text=True,
    cwd=r'd:\OneDrive\My Project Aplikasi\pos.dstechsmart.com'
)
print("STDOUT:", result.stdout)
print("STDERR:", result.stderr)
