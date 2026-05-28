<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

if (!Schema::hasTable('bengkel_work_order_details')) {
    Schema::create('bengkel_work_order_details', function (Blueprint $table) {
        $table->id();
        $table->string('NoPKB', 50);
        $table->integer('NoUrut');
        $table->string('KodeItem', 50);
        $table->double('Qty');
        $table->string('Satuan', 20)->nullable();
        $table->double('Harga');
        $table->double('Discount')->default(0);
        $table->double('HargaNet');
        $table->string('RecordOwnerID', 50);
        $table->timestamps();
    });
    echo "Tabel bengkel_work_order_details berhasil dibuat.\n";
} else {
    echo "Tabel bengkel_work_order_details sudah ada.\n";
}
