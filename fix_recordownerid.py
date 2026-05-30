import paramiko
import mysql.connector

# Fix Local DB
try:
    mydb = mysql.connector.connect(
      host='localhost',
      user='root',
      password='',
      database='xpos'
    )
    mycursor = mydb.cursor()
    mycursor.execute('ALTER TABLE customer_memberships MODIFY RecordOwnerID VARCHAR(255) NULL')
    mydb.commit()
    print('Local DB updated successfully.')
except Exception as e:
    print('Local DB error:', e)

# Fix Live DB
try:
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect('157.66.34.199', port=11587, username='root', password='M4m4cantik@dstechsmart10051987HdqHq345')

    php_code = """<?php
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);
    $kernel->bootstrap();
    use Illuminate\\Support\\Facades\\DB;
    try {
        DB::statement('ALTER TABLE customer_memberships MODIFY RecordOwnerID VARCHAR(255) NULL');
        echo "Live DB updated successfully.";
    } catch (\\Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    """

    stdin, stdout, stderr = ssh.exec_command("cat << 'EOF' > /www/wwwroot/pos.dstechsmart.com/fix_recordownerid.php\n" + php_code + "\nEOF\n")
    stdout.read()
    
    stdin, stdout, stderr = ssh.exec_command('cd /www/wwwroot/pos.dstechsmart.com && php fix_recordownerid.php')
    print('Live OUT:', stdout.read().decode())
    print('Live ERR:', stderr.read().decode())
except Exception as e:
    print('Live DB error:', e)
