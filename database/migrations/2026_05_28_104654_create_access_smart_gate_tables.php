<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessSmartGateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Tabel Access Devices
        Schema::create('access_devices', function (Blueprint $table) {
            $table->id();
            $table->string('KodePartner', 50)->nullable();
            $table->string('name');
            $table->string('ip_address', 50);
            $table->integer('port')->default(4370);
            $table->string('camera_url')->nullable();
            $table->enum('type', ['entrance', 'exit']);
            $table->enum('print_method', ['browser', 'network_ip'])->default('browser');
            $table->string('printer_address')->nullable();
            $table->string('status', 20)->default('disconnected');
            $table->dateTime('last_seen')->nullable();
            $table->timestamps();
        });

        // Tabel Access Parking Rates
        Schema::create('access_parking_rates', function (Blueprint $table) {
            $table->id();
            $table->string('KodePartner', 50)->nullable();
            $table->string('vehicle_type', 50);
            $table->integer('base_rate');
            $table->integer('hourly_rate');
            $table->integer('max_daily_rate')->nullable();
            $table->timestamps();
        });

        // Tabel Access Transactions
        Schema::create('access_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('KodePartner', 50)->nullable();
            $table->string('ticket_number', 50)->unique();
            $table->string('plate_number', 20)->nullable();
            $table->string('vehicle_type', 20)->nullable();
            $table->timestamp('entrance_time')->useCurrent();
            $table->timestamp('exit_time')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->unsignedBigInteger('device_in_id')->nullable();
            $table->unsignedBigInteger('device_out_id')->nullable();
            $table->string('image_in')->nullable();
            $table->string('image_out')->nullable();
            $table->boolean('sync_status')->default(false);
            $table->dateTime('sync_at')->nullable();
            $table->timestamps();
        });

        // Tabel Access Members
        Schema::create('access_members', function (Blueprint $table) {
            $table->id();
            $table->string('KodePartner', 50)->nullable();
            $table->string('plate_number', 20);
            $table->string('card_uid', 50)->nullable()->unique();
            $table->string('name', 100)->nullable();
            $table->string('member_type', 50)->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'expired'])->default('active');
            $table->timestamps();
        });

        // Tabel Access Member Payments
        Schema::create('access_member_payments', function (Blueprint $table) {
            $table->id();
            $table->string('KodePartner', 50)->nullable();
            $table->unsignedBigInteger('member_id');
            $table->integer('amount');
            $table->dateTime('payment_date')->useCurrent();
            $table->integer('period_months')->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->timestamps();
        });

        // Tabel Access Settings
        Schema::create('access_settings', function (Blueprint $table) {
            $table->id();
            $table->string('KodePartner', 50)->nullable();
            $table->string('setting_key', 50)->unique();
            $table->text('setting_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_settings');
        Schema::dropIfExists('access_member_payments');
        Schema::dropIfExists('access_members');
        Schema::dropIfExists('access_transactions');
        Schema::dropIfExists('access_parking_rates');
        Schema::dropIfExists('access_devices');
    }
}
