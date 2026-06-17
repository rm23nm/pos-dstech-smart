<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlinikPolisTable extends Migration
{
    public function up()
    {
        Schema::create('klinik_polis', function (Blueprint $table) {
            $table->id();
            $table->string('RecordOwnerID')->nullable();
            $table->string('KodePoli')->nullable();
            $table->string('NamaPoli');
            $table->text('Deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('klinik_polis');
    }
}
