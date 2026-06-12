<?php
$emails = ['demotiket@pos.dstechsmart.com','demoapotek@pos.dstechsmart.com','demoresto@pos.dstechsmart.com','demogate@pos.dstechsmart.com','demolaundry@pos.dstechsmart.com'];

foreach($emails as $e) {
    $u = App\Models\User::where('email', $e)->first();
    if($u) {
        echo $e . ' -> RoleID: ' . $u->RoleID . "\n";
    }
}
