<?php\n
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlinikMedicalRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('klinik_medical_records', function (Blueprint $table) {
            $table->id();
            $table->string('RecordOwnerID')->nullable();
            $table->unsignedBigInteger('AppointmentID');
            $table->unsignedBigInteger('PatientID');
            $table->unsignedBigInteger('DoctorID')->nullable();
            $table->date('TanggalPeriksa');
            $table->text('Keluhan')->nullable();
            $table->text('PemeriksaanFisik')->nullable();
            $table->text('Diagnosa')->nullable();
            $table->text('Tindakan')->nullable(); // JSON or text of services
            $table->text('ResepObat')->nullable(); // JSON or text of prescriptions
            $table->text('CatatanTambahan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('klinik_medical_records');
    }
}
