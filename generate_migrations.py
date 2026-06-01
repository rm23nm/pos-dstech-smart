import os
import datetime

mig_dir = 'database/migrations'
now = datetime.datetime.now()

tables = [
    ('create_klinik_polis_table', '''
use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

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
'''),
    ('create_klinik_medical_services_table', '''
use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

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
'''),
    ('create_klinik_patients_table', '''
use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

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
'''),
    ('create_klinik_doctors_table', '''
use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

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
'''),
    ('create_klinik_appointments_table', '''
use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

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
'''),
    ('create_klinik_medical_records_table', '''
use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

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
''')
]

for idx, (name, content) in enumerate(tables):
    ts = now + datetime.timedelta(minutes=idx)
    filename = f"{ts.strftime('%Y_%m_%d_%H%M%S')}_{name}.php"
    with open(os.path.join(mig_dir, filename), 'w') as f:
        f.write('<?php\\n' + content)
    print(f'Created {filename}')
