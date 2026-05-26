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
    $user = \App\Models\User::where('email', 'fulladmin@gmail.com')->first();
    echo 'User RecordOwnerID: ' . ($user ? $user->RecordOwnerID : 'Not Found') . "\\n";
    if ($user) {
        $company = \App\Models\Company::where('KodePartner', $user->RecordOwnerID)->first();
        echo 'Company: ' . ($company ? $company->NamaPartner : 'Not Found') . "\\n";
    }
    
    echo "--- Demo Accounts ---\\n";
    $demos = ['CL0014', 'demoapotek', 'DEMOGATE'];
    foreach($demos as $demo) {
        $u = \App\Models\User::where('RecordOwnerID', $demo)->first();
        echo $demo . " User Email: " . ($u ? $u->email : 'Not Found') . "\\n";
        $c = \App\Models\Company::where('KodePartner', $demo)->first();
        echo $demo . " Company: " . ($c ? $c->NamaPartner : 'Not Found') . "\\n";
    }
    """
    
    print("=== Artisan Tinker ===")
    cmd = f'cd /www/wwwroot/pos.dstechsmart.com && php artisan tinker --execute="{script}"'
    stdin, stdout, stderr = ssh.exec_command(cmd)
    print(stdout.read().decode())
    print(stderr.read().decode())

except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
