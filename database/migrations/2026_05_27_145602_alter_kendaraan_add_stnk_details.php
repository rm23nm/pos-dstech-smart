<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterKendaraanAddStnkDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            if (!Schema::hasColumn('kendaraan', 'NoMesin')) {
                $table->string('NoMesin')->nullable();
            }
            if (!Schema::hasColumn('kendaraan', 'NoRangka')) {
                $table->string('NoRangka')->nullable();
            }
            if (!Schema::hasColumn('kendaraan', 'NamaSTNK')) {
                $table->string('NamaSTNK')->nullable();
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
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropColumn(['NoMesin', 'NoRangka', 'NamaSTNK']);
        });
    }
}
