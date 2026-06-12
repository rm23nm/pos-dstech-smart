<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasTable('karyawan_payroll')) {
    Schema::create('karyawan_payroll', function (Blueprint $table) {
        $table->id();
        $table->string('RecordOwnerID', 50)->nullable();
        $table->integer('user_id');
        $table->double('GajiPokok', 15, 2)->default(0);
        $table->string('KodeAkunGaji', 50)->nullable(); // Akun Beban Gaji Default
        $table->timestamps();
    });
    echo "Table karyawan_payroll created.\n";
} else {
    echo "Table karyawan_payroll already exists.\n";
}
