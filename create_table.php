<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasTable('bengkel_bookings')) {
        Schema::create('bengkel_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('RecordOwnerID', 50);
            $table->date('TglBooking');
            $table->time('JamBooking');
            $table->string('PlatNomor', 20);
            $table->string('KodePelanggan', 50)->nullable();
            $table->string('NamaPelanggan', 100);
            $table->string('NoHP', 20);
            $table->text('Keluhan')->nullable();
            $table->integer('StatusBooking')->default(0)->comment('0=Pending, 1=Confirmed, 2=Selesai/Masuk PKB, 3=Batal');
            $table->timestamps();
        });
        echo "Table bengkel_bookings created successfully.";
    } else {
        echo "Table bengkel_bookings already exists.";
    }
} catch (\Exception $e) {
    ob_clean();
    echo "ERROR MESSAGE: " . $e->getMessage();
}
