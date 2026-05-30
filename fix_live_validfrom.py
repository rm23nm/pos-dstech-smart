import paramiko

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect('157.66.34.199', port=11587, username='root', password='M4m4cantik@dstechsmart10051987HdqHq345')

php_code = """<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);
$kernel->bootstrap();
use Illuminate\\Support\\Facades\\Schema;
use Illuminate\\Database\\Schema\\Blueprint;
if (!Schema::hasColumn('customer_memberships', 'ValidFrom')) {
    Schema::table('customer_memberships', function (Blueprint $table) {
        $table->dateTime('ValidFrom')->nullable()->after('KodePaketMember');
    });
    echo "Added ValidFrom.";
} else {
    echo "ValidFrom exists.";
}
"""

stdin, stdout, stderr = ssh.exec_command("cat << 'EOF' > /www/wwwroot/pos.dstechsmart.com/fix_validfrom.php\n" + php_code + "\nEOF\n")
out = stdout.read().decode()
err = stderr.read().decode()

stdin, stdout, stderr = ssh.exec_command('cd /www/wwwroot/pos.dstechsmart.com && php fix_validfrom.php')
print('OUT:', stdout.read().decode())
print('ERR:', stderr.read().decode())
