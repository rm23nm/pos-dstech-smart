<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccessCallTriggerToTableorderheaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('tableorderheader')) {
            Schema::table('tableorderheader', function (Blueprint $table) {
                if (!Schema::hasColumn('tableorderheader', 'access_call_trigger')) {
                    $table->integer('access_call_trigger')->default(0)->after('kitchen_order_status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tableorderheader', function (Blueprint $table) {
            $table->dropColumn('access_call_trigger');
        });
    }
}
