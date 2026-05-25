<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('demo_email')->nullable();
            $table->string('demo_password')->nullable();
            $table->integer('order_num')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('button_icon')->default('bi-stars');
            $table->string('button_color')->default('btn-outline-primary');
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
        Schema::dropIfExists('login_slides');
    }
}
