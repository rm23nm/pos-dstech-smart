<?php\n
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlinikMedicalServicesTable extends Migration
{
    public function up()
    {
        Schema::create('klinik_medical_services', function (Blueprint $table) {
            $table->id();
            $table->string('RecordOwnerID')->nullable();
            $table->string('KodeJasa')->nullable();
            $table->string('NamaJasa');
            $table->decimal('Harga', 15, 2)->default(0);
            $table->text('Deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('klinik_medical_services');
    }
}
