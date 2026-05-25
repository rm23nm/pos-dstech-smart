<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasTable('member_packages')) {
    Schema::create('member_packages', function (Blueprint $table) {
        $table->increments('id');
        $table->string('KodePaket', 50);
        $table->string('KategoriPaket', 50);
        $table->string('NamaPaket', 255);
        $table->integer('LimitBulanan')->default(0);
        $table->string('RecordOwnerID', 50);
        $table->timestamps();
        $table->unique(['KodePaket', 'RecordOwnerID']);
    });
    echo "Table member_packages created.\n";
}

if (!Schema::hasTable('customer_memberships')) {
    Schema::create('customer_memberships', function (Blueprint $table) {
        $table->increments('id');
        $table->string('KodePelanggan', 50);
        $table->string('KodePaketMember', 50);
        $table->date('ValidFrom');
        $table->date('ValidUntil');
        $table->integer('MaxPlay')->default(0);
        $table->integer('Played')->default(0);
        $table->string('RecordOwnerID', 50);
        $table->timestamps();
        $table->index('KodePelanggan');
    });
    echo "Table customer_memberships created.\n";
}

echo "Done.\n";
