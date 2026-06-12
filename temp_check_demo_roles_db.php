<?php
use Illuminate\Support\Facades\DB;

$roles = DB::table('roles')->whereIn('RecordOwnerID', ['demoapotek', 'demoresto', 'demogate'])->get();
echo "Roles:\n";
echo json_encode($roles) . "\n\n";

$roleuser = DB::table('roleuser')->whereIn('RecordOwnerID', ['demoapotek', 'demoresto', 'demogate'])->get();
echo "RoleUser:\n";
echo json_encode($roleuser) . "\n\n";
