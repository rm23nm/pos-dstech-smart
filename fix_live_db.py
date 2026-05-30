import paramiko
import time

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect('157.66.34.199', port=11587, username='root', password='M4m4cantik@dstechsmart10051987HdqHq345')

code = """
use Illuminate\\Support\\Facades\\Schema;
use Illuminate\\Database\\Schema\\Blueprint;
try {
    if (!Schema::hasColumn('customer_memberships', 'ValidFrom')) {
        Schema::table('customer_memberships', function (Blueprint $table) {
            $table->dateTime('ValidFrom')->nullable()->after('KodePaketMember');
        });
        echo 'Added ValidFrom successfully.';
    } else {
        echo 'ValidFrom already exists.';
    }
} catch (\\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
"""

stdin, stdout, stderr = ssh.exec_command('cd /www/wwwroot/pos.dstechsmart.com && php artisan tinker')
stdin.write(code + "\n")
stdin.write("exit\n")
stdin.flush()

time.sleep(3)

print("OUT:", stdout.read().decode())
print("ERR:", stderr.read().decode())
