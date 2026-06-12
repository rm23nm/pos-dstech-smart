<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->string('RecordOwnerID', 50);
            $table->date('Tanggal');
            $table->unsignedBigInteger('user_id'); // Karyawan yang absen
            $table->string('KodeShift', 50)->nullable();
            $table->datetime('JamMasuk')->nullable();
            $table->datetime('JamPulang')->nullable();
            $table->longText('FotoMasuk')->nullable(); // Base64
            $table->longText('FotoPulang')->nullable(); // Base64
            $table->string('StatusKehadiran', 50)->nullable(); // Tepat Waktu, Terlambat
            $table->text('Catatan')->nullable();
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
        Schema::dropIfExists('absensi');
    }
}
