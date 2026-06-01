<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlinikBpjsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('klinik_bpjs_settings')) {
            Schema::create('klinik_bpjs_settings', function (Blueprint $table) {
                $table->id();
                $table->string('RecordOwnerID', 50)->nullable();
                $table->string('ConsID')->nullable();
                $table->string('SecretKey')->nullable();
                $table->string('UserKey')->nullable();
                $table->string('BaseUrl')->nullable();
                $table->boolean('isSandbox')->default(true);
                $table->boolean('isActive')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('klinik_bpjs_settings');
    }
}
