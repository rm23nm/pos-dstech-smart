<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

try {
    if (!Schema::hasTable('login_slides')) {
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
        echo "Table login_slides created successfully.";
    } else {
        echo "Table login_slides already exists.";
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
