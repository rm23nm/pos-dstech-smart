<?php
use Illuminate\Support\Facades\DB;
 = DB::table('users')->where('email', 'demoklinik@pos.dstechsmart.com')->first();
if (!) { echo 'User not found'; exit; }

 = session('menu_list', []);
echo 'Menu in session: ' . count() . '
';

 = DB::table('userrole')->where('userid', ->id)->first();
 = DB::table('permissionrole')
    ->join('permission', 'permission.id', '=', 'permissionrole.permissionid')
    ->where('permissionrole.roleid', ->roleid)
    ->where('permissionrole.RecordOwnerID', ->RecordOwnerID)
    ->select('permission.PermissionName', 'permission.Level', 'permission.MenuInduk', 'permission.Status')
    ->get();

echo 'Permissions assigned: ' . count() . '
';
 = ->where('Level', '1')->count();
 = ->where('Level', '2')->count();
echo 'L1: ' .  . ', L2: ' .  . '
';
