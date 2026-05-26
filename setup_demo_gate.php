<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

$kodePartner = 'DEMOGATE';
$userEmail = 'demogate@pos.dstechsmart.com';
$now = Carbon::now('Asia/Jakarta');

try {
    DB::beginTransaction();

    // 1. Company
    $companyExists = DB::table('company')->where('KodePartner', $kodePartner)->first();
    if (!$companyExists) {
        // Clone from DEMORETAIL
        $demoRetail = DB::table('company')->where('KodePartner', 'DEMORETAIL')->first();
        if ($demoRetail) {
            $compArray = (array) $demoRetail;
            $compArray['KodePartner'] = $kodePartner;
            $compArray['NamaPartner'] = 'Demo Waterpark & Gate';
            $compArray['JenisUsaha'] = 'TiketGate';
            $compArray['Email'] = $userEmail;
            $compArray['NoTlp'] = '08123456789';
            $compArray['created_at'] = $now;
            $compArray['updated_at'] = $now;
            DB::table('company')->insert($compArray);
            echo "Inserted Demo Gate to company table.\n";
        }
    } else {
        DB::table('company')->where('KodePartner', $kodePartner)->update([
            'JenisUsaha' => 'TiketGate',
            'NamaPartner' => 'Demo Waterpark & Gate'
        ]);
        echo "Updated Demo Gate company.\n";
    }

    // 2. User
    $userExists = DB::table('users')->where('email', $userEmail)->first();
    $userId = null;
    if (!$userExists) {
        $demoRetailUser = DB::table('users')->where('email', 'demoretail@pos.dstechsmart.com')->first();
        if ($demoRetailUser) {
            $demoUserArray = (array) $demoRetailUser;
            unset($demoUserArray['id']); // let it auto increment
            $demoUserArray['name'] = 'Admin Gate';
            $demoUserArray['email'] = $userEmail;
            $demoUserArray['RecordOwnerID'] = $kodePartner;
            $demoUserArray['password'] = Hash::make('12345678');
            $userId = DB::table('users')->insertGetId($demoUserArray);
            echo "Inserted user demogate.\n";
        }
    } else {
        $userId = $userExists->id;
        DB::table('users')->where('id', $userId)->update([
            'RecordOwnerID' => $kodePartner,
            'password' => Hash::make('12345678'),
            'name' => 'Admin Gate'
        ]);
        echo "Updated user demogate.\n";
    }

    // 3. Spatie Roles
    $roleName = 'SuperAdmin';
    $role = DB::table('roles')->where('RoleName', $roleName)->where('RecordOwnerID', $kodePartner)->first();
    $roleId = null;
    if (!$role) {
        $roleId = DB::table('roles')->insertGetId([
            'RoleName' => $roleName,
            'RecordOwnerID' => $kodePartner,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        echo "Created SuperAdmin role for demogate.\n";
    } else {
        $roleId = $role->id;
    }
    
    DB::table('userrole')->where('userid', $userId)->delete();
    DB::table('userrole')->insert([
        'roleid' => $roleId,
        'userid' => $userId,
        'RecordOwnerID' => $kodePartner
    ]);
    echo "Assigned SuperAdmin role to demogate user.\n";
    


    DB::commit();
    echo "Done!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
