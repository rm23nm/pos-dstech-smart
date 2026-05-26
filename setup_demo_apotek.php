<?php
// Script to set up Demo Apotek account and database schema

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

// 1. Add Columns to fakturpenjualanheader
if (!Schema::hasColumn('fakturpenjualanheader', 'NoResep')) {
    DB::statement('ALTER TABLE fakturpenjualanheader ADD COLUMN NoResep VARCHAR(50) NULL;');
    echo "Added NoResep column to fakturpenjualanheader.\n";
}
if (!Schema::hasColumn('fakturpenjualanheader', 'NamaDokter')) {
    DB::statement('ALTER TABLE fakturpenjualanheader ADD COLUMN NamaDokter VARCHAR(100) NULL;');
    echo "Added NamaDokter column to fakturpenjualanheader.\n";
}
if (!Schema::hasColumn('fakturpenjualanheader', 'NamaPasien')) {
    DB::statement('ALTER TABLE fakturpenjualanheader ADD COLUMN NamaPasien VARCHAR(100) NULL;');
    echo "Added NamaPasien column to fakturpenjualanheader.\n";
}

// Check if JenisUsaha column exists in company table
if (!Schema::hasColumn('company', 'JenisUsaha')) {
    DB::statement("ALTER TABLE company ADD COLUMN JenisUsaha VARCHAR(50) DEFAULT 'Retail';");
    echo "Added JenisUsaha column to company.\n";
}

// 2. Insert Company Demo Apotek
$kodePartner = 'demoapotek';
$exists = DB::table('company')->where('KodePartner', $kodePartner)->exists();
if (!$exists) {
    // Just copy from demoretail
    $demoRetail = DB::table('company')->where('KodePartner', 'demoretail')->first();
    if ($demoRetail) {
        $demoRetailArray = (array) $demoRetail;
        $demoRetailArray['KodePartner'] = $kodePartner;
        $demoRetailArray['NamaPartner'] = 'Demo Apotek';
        $demoRetailArray['Email'] = 'demoapotek@pos.dstechsmart.com';
        $demoRetailArray['JenisUsaha'] = 'Apotek';
        
        DB::table('company')->insert($demoRetailArray);
        echo "Inserted Demo Apotek by cloning demoretail.\n";
    }
    echo "Inserted Demo Apotek to company table.\n";
} else {
    // Update it just in case
    DB::table('company')->where('KodePartner', $kodePartner)->update([
        'JenisUsaha' => 'Apotek'
    ]);
    echo "Updated Demo Apotek company to JenisUsaha = Apotek.\n";
}

// 3. Insert User Demo Apotek
$userEmail = 'demoapotek@pos.dstechsmart.com';
$userExists = DB::table('users')->where('email', $userEmail)->first();
$userId = null;
if (!$userExists) {
    $demoRetailUser = DB::table('users')->where('email', 'demoretail@pos.dstechsmart.com')->first();
    if ($demoRetailUser) {
        $demoUserArray = (array) $demoRetailUser;
        unset($demoUserArray['id']); // let it auto increment
        $demoUserArray['name'] = 'Admin Apotek';
        $demoUserArray['email'] = $userEmail;
        $demoUserArray['RecordOwnerID'] = $kodePartner;
        $demoUserArray['password'] = Hash::make('12345678');
        $userId = DB::table('users')->insertGetId($demoUserArray);
        echo "Inserted user demoapotek by cloning.\n";
    }
} else {
    $userId = $userExists->id;
}

// 4. Assign Role to User
$roleExists = DB::table('roles')->where('RecordOwnerID', $kodePartner)->where('RoleName', 'SuperAdmin')->first();
$roleId = null;
if (!$roleExists) {
    $roleId = DB::table('roles')->insertGetId([
        'RoleName' => 'SuperAdmin',
        'RecordOwnerID' => $kodePartner
    ]);
    echo "Created SuperAdmin role for demoapotek.\n";
} else {
    $roleId = $roleExists->id;
}

// UserRole
$userRoleExists = DB::table('userrole')
    ->where('userid', $userId)
    ->where('roleid', $roleId)
    ->where('RecordOwnerID', $kodePartner)
    ->exists();
if (!$userRoleExists) {
    DB::table('userrole')->insert([
        'userid' => $userId,
        'roleid' => $roleId,
        'RecordOwnerID' => $kodePartner
    ]);
    echo "Assigned SuperAdmin role to demoapotek user.\n";
}

echo "Done!\n";
