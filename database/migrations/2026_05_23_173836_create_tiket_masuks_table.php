<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiketMasuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiket_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('NoTransaksi')->nullable();
            $table->string('BarcodeTiket')->unique();
            $table->tinyInteger('Status')->default(0)->comment('0 = Belum Dipakai, 1 = Sudah Dipakai');
            $table->dateTime('WaktuPakai')->nullable();
            $table->string('RecordOwnerID')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tiket_masuk');
    }
}
