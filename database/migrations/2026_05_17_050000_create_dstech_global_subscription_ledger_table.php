<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDstechGlobalSubscriptionLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dstech_global_subscription_ledger', function (Blueprint $table) {
            $table->id();
            $table->string('dstech_app_source'); // 'pos', 'smartpro', 'masjidku', 'smartaccess', etc.
            $table->string('dstech_client_id'); // KodePelanggan / KodePartner / email
            $table->string('dstech_client_name');
            $table->string('dstech_client_email')->nullable();
            $table->string('dstech_client_phone')->nullable();
            $table->string('dstech_package_name');
            $table->decimal('dstech_amount', 15, 2)->default(0.00);
            $table->string('dstech_payment_status')->default('paid'); // 'paid', 'pending', 'expired'
            $table->date('dstech_start_date');
            $table->date('dstech_end_date');
            $table->string('dstech_transaction_id')->unique(); // unique transaction ID
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
        Schema::dropIfExists('dstech_global_subscription_ledger');
    }
}
