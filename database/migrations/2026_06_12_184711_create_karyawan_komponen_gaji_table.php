<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanKomponenGajiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan_komponen_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('RecordOwnerID')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->enum('Jenis', ['Tunjangan', 'Potongan']);
            $table->string('NamaKomponen');
            $table->enum('Sifat', ['Harian', 'Tetap'])->default('Tetap');
            $table->decimal('Nominal', 15, 2)->default(0);
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
        Schema::dropIfExists('karyawan_komponen_gaji');
    }
}
