<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCallTriggerToFakturpenjualanheaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fakturpenjualanheader', function (Blueprint $table) {
            if (!Schema::hasColumn('fakturpenjualanheader', 'call_trigger')) {
                $table->integer('call_trigger')->default(0)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fakturpenjualanheader', function (Blueprint $table) {
            if (Schema::hasColumn('fakturpenjualanheader', 'call_trigger')) {
                $table->dropColumn('call_trigger');
            }
        });
    }
}
