<?php\n
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlinikAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('klinik_appointments', function (Blueprint $table) {
            $table->id();
            $table->string('RecordOwnerID')->nullable();
            $table->string('NoAntrean');
            $table->date('TanggalDaftar');
            $table->unsignedBigInteger('PatientID');
            $table->unsignedBigInteger('PoliID')->nullable();
            $table->unsignedBigInteger('DoctorID')->nullable();
            $table->enum('Status', ['Menunggu', 'Diperiksa', 'Selesai', 'Batal'])->default('Menunggu');
            $table->text('CatatanPendaftaran')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('klinik_appointments');
    }
}
