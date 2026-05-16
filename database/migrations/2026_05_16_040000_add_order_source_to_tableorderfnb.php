<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('tableorderfnb')) {
            Schema::table('tableorderfnb', function (Blueprint $table) {
                if (!Schema::hasColumn('tableorderfnb', 'OrderSource')) {
                    $table->string('OrderSource', 50)->nullable()->default('POS')->after('ServiceType');
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
        if (Schema::hasTable('tableorderfnb')) {
            Schema::table('tableorderfnb', function (Blueprint $table) {
                if (Schema::hasColumn('tableorderfnb', 'OrderSource')) {
                    $table->dropColumn('OrderSource');
                }
            });
        }
    }
};
