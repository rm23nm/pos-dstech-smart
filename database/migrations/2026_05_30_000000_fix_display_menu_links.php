<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixDisplayMenuLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permission')
            ->where('PermissionName', 'Queue Lapangan')
            ->update(['Link' => 'queue']);
            
        DB::table('permission')
            ->where('PermissionName', 'Queue Antrian FNB')
            ->update(['Link' => 'queue']);
            
        DB::table('permission')
            ->where('PermissionName', 'Monitor Counter (Recall)')
            ->update(['Link' => 'countermonitor']);
            
        DB::table('permission')
            ->where('PermissionName', 'Customer Display POS')
            ->update(['Link' => 'customerdisplay']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No down needed for this data patch
    }
}
