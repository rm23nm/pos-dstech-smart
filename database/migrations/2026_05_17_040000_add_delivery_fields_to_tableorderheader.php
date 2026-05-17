<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryFieldsToTableorderheader extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom DeliveryType dan DeliveryAddress untuk fitur pengiriman E-Catalog
     */
    public function up()
    {
        Schema::table('tableorderheader', function (Blueprint $table) {
            if (!Schema::hasColumn('tableorderheader', 'DeliveryType')) {
                $table->string('DeliveryType', 20)->nullable()->default('PICKUP')->comment('PICKUP atau DELIVERY');
            }
            if (!Schema::hasColumn('tableorderheader', 'DeliveryAddress')) {
                $table->text('DeliveryAddress')->nullable()->comment('Alamat pengiriman jika DELIVERY');
            }
            if (!Schema::hasColumn('tableorderheader', 'DeliveryCost')) {
                $table->decimal('DeliveryCost', 18, 2)->nullable()->default(0)->comment('Ongkos kirim');
            }
            if (!Schema::hasColumn('tableorderheader', 'DeliveryNotes')) {
                $table->text('DeliveryNotes')->nullable()->comment('Catatan tambahan dari pelanggan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tableorderheader', function (Blueprint $table) {
            $table->dropColumn(['DeliveryType', 'DeliveryAddress', 'DeliveryCost', 'DeliveryNotes']);
        });
    }
}
