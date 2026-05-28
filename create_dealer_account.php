<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Company;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    DB::statement("SET sql_mode = '';");
    DB::beginTransaction();

    $email = 'dealer@dstechsmart.com';
    $password = 'dealer123';
    
    // Create User
    $user = User::where('email', $email)->first();
    if (!$user) {
        $user = new User();
        $user->name = 'Admin Dealer';
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->RecordOwnerID = 'DEALER-001';
        $user->BranchID = '0';
        $user->save();
        echo "User created.\n";
    } else {
        echo "User already exists.\n";
    }

    // Create Company
    $company = Company::where('KodePartner', 'DEALER-001')->first();
    if (!$company) {
        $company = new Company();
        $company->KodePartner = 'DEALER-001';
        $company->NamaPartner = 'Dealer DS-Tech';
        $company->NamaPIC = 'Admin Dealer';
        $company->NoTlp = '08123456789';
        $company->Email = $email;
        $company->StartSubs = date('Y-m-d');
        $company->EndSubs = date('Y-m-d', strtotime('+1 year'));
        $company->JenisUsaha = 'Dealer';
        $company->AlamatTagihan = '';
        $company->save();
        echo "Company created.\n";
    } else {
        echo "Company already exists.\n";
    }

    // Create Role
    $roleId = DB::table('roles')->insertGetId([
        'RoleName' => 'SuperAdmin',
        'RecordOwnerID' => 'DEALER-001'
    ]);

    // Role User
    $role = DB::table('userrole')->where('userid', $user->id)->first();
    if (!$role) {
        DB::table('userrole')->insert([
            'userid' => $user->id,
            'roleid' => $roleId,
            'RecordOwnerID' => 'DEALER-001'
        ]);
        echo "Role assigned.\n";
    } else {
        echo "Role already assigned.\n";
    }

    DB::commit();
    echo "Account Setup Complete!\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
