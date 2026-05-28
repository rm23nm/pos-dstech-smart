<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$kode  = 'DEMO-BENGKEL-001';
$email = 'demobengkel@pos.dstechsmart.com';

// Get user
$user = DB::table('users')->where('email', $email)->first();
$userID = $user->id;
echo "UserID: " . $userID . PHP_EOL;

// =====================================================
// 1. Copy role setup from a working demo (demoresto)
//    or create a new owner role
// =====================================================

// Cek dulu apakah ada role template dari akun lain yang sama strukturnya
// Kita ambil referensi dari fulladmin/owner yang sudah jalan
$refUser = DB::table('users')->where('email', 'demoresto@pos.dstechsmart.com')->first();
$refRecordOwnerID = $refUser ? $refUser->RecordOwnerID : null;

// Get reference role
$refUserRole = DB::table('userrole')->where('userid', $refUser->id)->first();
$refRoleID = $refUserRole ? $refUserRole->roleid : null;

// Get reference role detail
$refRole = $refRoleID ? DB::table('roles')->where('id', $refRoleID)->first() : null;

echo "Reference role: " . ($refRole ? $refRole->RoleName : 'not found') . PHP_EOL;

// Get ALL permissions for the reference role
$refPerms = DB::table('permissionrole')
    ->where('RecordOwnerID', $refRecordOwnerID)
    ->get();

echo "Reference permissions count: " . count($refPerms) . PHP_EOL;

// =====================================================
// 2. Create new role for demo bengkel
// =====================================================
$existingRole = DB::table('roles')->where('RecordOwnerID', $kode)->first();
if ($existingRole) {
    $newRoleID = $existingRole->id;
    echo "Role already exists: " . $newRoleID . PHP_EOL;
} else {
    $newRoleID = DB::table('roles')->insertGetId([
        'RoleName'      => 'Owner',
        'RecordOwnerID' => $kode,
        'created_at'    => now(),
        'updated_at'    => now(),
    ]);
    echo "Role created: " . $newRoleID . PHP_EOL;
}

// =====================================================
// 3. Assign role to user
// =====================================================
$existingUserRole = DB::table('userrole')->where('userid', $userID)->first();
if ($existingUserRole) {
    echo "UserRole already exists." . PHP_EOL;
} else {
    DB::table('userrole')->insert([
        'userid'        => $userID,
        'roleid'        => $newRoleID,
        'RecordOwnerID' => $kode,
    ]);
    echo "UserRole assigned." . PHP_EOL;
}

// =====================================================
// 4. Copy permissions from reference to new role
// =====================================================
$existingPerms = DB::table('permissionrole')->where('RecordOwnerID', $kode)->count();
if ($existingPerms > 0) {
    echo "Permissions already exist: " . $existingPerms . PHP_EOL;
} else {
    $inserted = 0;
    foreach ($refPerms as $perm) {
        DB::table('permissionrole')->insert([
            'roleid'        => $newRoleID,
            'permissionid'  => $perm->permissionid,
            'RecordOwnerID' => $kode,
        ]);
        $inserted++;
    }
    echo $inserted . " permissions copied." . PHP_EOL;
}

// =====================================================
// 5. Setup mastercontroller (menu) jika diperlukan
// =====================================================
$mcCount = DB::table('mastercontroller')->where('RecordOwnerID', $kode)->count();
echo "MasterController records: " . $mcCount . PHP_EOL;

// Copy mastercontroller dari referensi jika kosong
if ($mcCount === 0) {
    $refMC = DB::table('mastercontroller')->where('RecordOwnerID', $refRecordOwnerID)->get();
    echo "Reference MC count: " . count($refMC) . PHP_EOL;
    
    if (count($refMC) > 0) {
        foreach ($refMC as $mc) {
            $arr = (array)$mc;
            unset($arr['id']);
            $arr['RecordOwnerID'] = $kode;
            $arr['created_at'] = now();
            $arr['updated_at'] = now();
            DB::table('mastercontroller')->insert($arr);
        }
        echo count($refMC) . " mastercontroller records copied." . PHP_EOL;
    }
}

echo "===========================================" . PHP_EOL;
echo "DONE! Roles and permissions configured." . PHP_EOL;
echo "===========================================" . PHP_EOL;
