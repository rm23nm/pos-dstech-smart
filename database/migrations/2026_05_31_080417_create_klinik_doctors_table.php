<?php\n
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlinikDoctorsTable extends Migration
{
    public function up()
    {
        Schema::create('klinik_doctors', function (Blueprint $table) {
            $table->id();
            $table->string('RecordOwnerID')->nullable();
            $table->string('NamaDokter');
            $table->string('Spesialisasi')->nullable();
            $table->unsignedBigInteger('PoliID')->nullable();
            $table->string('NoSIP')->nullable();
            $table->string('NoHP')->nullable();
            $table->text('JadwalPraktik')->nullable();
            $table->timestamps();
            
            // Note: foreign keys are often omitted in this system, but we keep the column.
        });
    }

    public function down()
    {
        Schema::dropIfExists('klinik_doctors');
    }
}
