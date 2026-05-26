import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    script = """
    require 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    echo "=== fulladmin@gmail.com ===\\n";
    $u = App\Models\User::where('email', 'fulladmin@gmail.com')->first();
    echo "User RecordOwnerID: " . ($u ? $u->RecordOwnerID : 'Not Found') . "\\n";
    if ($u) {
        $c = App\Models\Company::where('KodePartner', $u->RecordOwnerID)->first();
        echo "Company: " . ($c ? $c->NamaPartner : 'Not Found') . "\\n";
    }
    """
    
    cmd = f'cd /www/wwwroot/pos.dstechsmart.com && php -r "{script}"'
    stdin, stdout, stderr = ssh.exec_command(cmd)
    print(stdout.read().decode())
    print(stderr.read().decode())

except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
