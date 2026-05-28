<?php
use Illuminate\Support\Facades\DB;

try {
    $res = [];
    $users = DB::table('users')->where('RecordOwnerID', 'DEMO-BENGKEL-001')->get();
    $res['users'] = $users;
    echo json_encode($res, JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    ob_clean();
    echo $e->getMessage();
}
