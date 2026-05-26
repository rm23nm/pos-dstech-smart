import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    stdin, stdout, stderr = ssh.exec_command("php -r \"require __DIR__.'/www/wwwroot/pos.dstechsmart.com/vendor/autoload.php'; \\$app = require_once __DIR__.'/www/wwwroot/pos.dstechsmart.com/bootstrap/app.php'; \\$kernel = \\$app->make(Illuminate\\Contracts\\Console\\Kernel::class); \\$kernel->bootstrap(); try { \\$res = App\\Models\\InvoicePenggunaHeader::first(); echo 'Success: '.\\$res->NoTransaksi; } catch (\\Exception \\$e) { echo 'Error: '.\\$e->getMessage(); }\"")
    print("STDOUT:", stdout.read().decode('utf-8'))
    print("STDERR:", stderr.read().decode('utf-8'))
    
    ssh.close()
except Exception as e:
    print(e)
