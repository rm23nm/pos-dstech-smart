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
            $table->string('NoMesin')->nullable();
            $table->string('NoRangka')->nullable();
            $table->string('NamaSTNK')->nullable();
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
