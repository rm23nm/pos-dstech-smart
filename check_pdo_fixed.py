import paramiko

host = '157.66.34.199'
port = 11587
user = 'root'
password = 'M4m4cantik@dstechsmart10051987HdqHq345'

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(host, port, user, password)
    
    script = """<?php
    $envFile = '/www/wwwroot/pos.dstechsmart.com/.env';
    $env = parse_ini_file($envFile);
    $pdo = new PDO("mysql:host=".$env['DB_HOST'].";dbname=".$env['DB_DATABASE'], $env['DB_USERNAME'], $env['DB_PASSWORD']);
    
    $stmt = $pdo->prepare("SELECT RecordOwnerID, email FROM users WHERE email = 'fulladmin@gmail.com'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user) {
        echo "User fulladmin: " . $user['RecordOwnerID'] . "\\n";
        $stmt2 = $pdo->prepare("SELECT NamaPartner FROM company WHERE KodePartner = ?");
        $stmt2->execute([$user['RecordOwnerID']]);
        $comp = $stmt2->fetch(PDO::FETCH_ASSOC);
        echo "Company: " . ($comp ? $comp['NamaPartner'] : "NULL") . "\\n";
    } else {
        echo "User fulladmin NOT FOUND\\n";
    }
    
    // Check Demo Accounts
    $demos = ['CL0014', 'demoapotek', 'DEMOGATE'];
    foreach($demos as $d) {
        $stmt2 = $pdo->prepare("SELECT NamaPartner FROM company WHERE KodePartner = ?");
        $stmt2->execute([$d]);
        $comp = $stmt2->fetch(PDO::FETCH_ASSOC);
        echo "Demo Company $d: " . ($comp ? $comp['NamaPartner'] : "NULL") . "\\n";
    }
    """
    
    # Write to a file first, then run it to avoid bash string interpolation errors
    stdin, stdout, stderr = ssh.exec_command('cat << \'EOF\' > /tmp/check_pdo.php\n' + script + '\nEOF\nphp /tmp/check_pdo.php')
    print(stdout.read().decode())
    print(stderr.read().decode())

except Exception as e:
    print(f"Error: {e}")
finally:
    ssh.close()
