<?php
$u = App\Models\User::where('email', 'demoapotek@pos.dstechsmart.com')->first();
echo json_encode($u);
