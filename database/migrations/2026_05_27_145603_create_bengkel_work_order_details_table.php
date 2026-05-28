<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBengkelWorkOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bengkel_work_order_details', function (Blueprint $table) {
            $table->id();
            $table->string('NoPKB');
            $table->string('KodeItem');
            $table->string('NamaItem');
            $table->decimal('Qty', 10, 2)->default(1);
            $table->decimal('Harga', 15, 2)->default(0);
            $table->decimal('Subtotal', 15, 2)->default(0);
            $table->tinyInteger('StatusGudang')->default(0)->comment('0: Menunggu, 1: Diserahkan');
            $table->string('RecordOwnerID')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bengkel_work_order_details');
    }
}
