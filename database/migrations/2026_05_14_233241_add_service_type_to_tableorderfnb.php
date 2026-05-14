<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceTypeToTableorderfnb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tableorderfnb', function (Blueprint $table) {
            $table->string('ServiceType', 20)->default('DINE_IN')->after('isCompleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tableorderfnb', function (Blueprint $table) {
            $table->dropColumn('ServiceType');
        });
    }
}
