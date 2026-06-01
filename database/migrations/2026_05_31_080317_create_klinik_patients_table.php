<?php\n
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlinikPatientsTable extends Migration
{
    public function up()
    {
        Schema::create('klinik_patients', function (Blueprint $table) {
            $table->id();
            $table->string('RecordOwnerID')->nullable();
            $table->string('NoRM')->unique();
            $table->string('NIK')->nullable();
            $table->string('NamaPasien');
            $table->date('TanggalLahir')->nullable();
            $table->enum('JenisKelamin', ['L', 'P'])->nullable();
            $table->text('Alamat')->nullable();
            $table->string('NoHP')->nullable();
            $table->string('GolonganDarah')->nullable();
            $table->text('RiwayatAlergi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('klinik_patients');
    }
}
