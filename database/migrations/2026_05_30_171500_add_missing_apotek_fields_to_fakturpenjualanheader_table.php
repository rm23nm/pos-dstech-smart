<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingApotekFieldsToFakturpenjualanheaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fakturpenjualanheader', function (Blueprint $table) {
            if (!Schema::hasColumn('fakturpenjualanheader', 'NoAntrian')) {
                $table->string('NoAntrian')->nullable();
            }
            if (!Schema::hasColumn('fakturpenjualanheader', 'peracikan_status')) {
                $table->string('peracikan_status')->nullable()->default('menunggu');
            }
            if (!Schema::hasColumn('fakturpenjualanheader', 'NoResep')) {
                $table->string('NoResep')->nullable();
            }
            if (!Schema::hasColumn('fakturpenjualanheader', 'NamaDokter')) {
                $table->string('NamaDokter')->nullable();
            }
            if (!Schema::hasColumn('fakturpenjualanheader', 'NamaPasien')) {
                $table->string('NamaPasien')->nullable();
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
            if (Schema::hasColumn('fakturpenjualanheader', 'NoAntrian')) {
                $table->dropColumn('NoAntrian');
            }
            if (Schema::hasColumn('fakturpenjualanheader', 'peracikan_status')) {
                $table->dropColumn('peracikan_status');
            }
            // we will not drop NoResep, NamaDokter, NamaPasien here to be safe as they might have been added manually before
        });
    }
}
