<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaxDevicesToOfflineLicenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offline_licenses', function (Blueprint $table) {
            $table->integer('max_devices')->default(1)->after('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offline_licenses', function (Blueprint $table) {
            $table->dropColumn('max_devices');
        });
    }
}
