<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKitchenOrderStatusToTableorderheaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tableorderheader', function (Blueprint $table) {
            $table->integer('kitchen_order_status')->default(0)->after('Status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tableorderheader', function (Blueprint $table) {
            $table->dropColumn('kitchen_order_status');
        });
    }
}
