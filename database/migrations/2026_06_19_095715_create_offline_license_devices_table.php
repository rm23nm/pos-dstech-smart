<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfflineLicenseDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_license_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offline_license_id');
            $table->string('hardware_id');
            $table->string('device_name')->nullable();
            $table->timestamps();

            $table->foreign('offline_license_id')->references('id')->on('offline_licenses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offline_license_devices');
    }
}
