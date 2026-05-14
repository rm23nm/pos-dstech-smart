<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllowMonitorAntreanToSubscriptionHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptionheader', function (Blueprint $table) {
            $table->integer('AllowMonitorAntrean')->default(0)->after('AllowKatalogOnline');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptionheader', function (Blueprint $table) {
            $table->dropColumn('AllowMonitorAntrean');
        });
    }
}
