<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemMovingHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('item_moving_history', function (Blueprint $table) {
            $table->id();
            $table->string('KodeItem');
            $table->date('TglTransaksi');
            $table->string('TipeTransaksi')->comment('ISSUE_BENGKEL, PURCHASE, SALE, etc');
            $table->string('NoTransaksi');
            $table->decimal('Qty', 10, 2)->default(0)->comment('Negatif = keluar, Positif = masuk');
            $table->decimal('Harga', 15, 2)->default(0);
            $table->string('KodeGudang')->nullable();
            $table->string('RecordOwnerID')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('item_moving_history');
    }
}
