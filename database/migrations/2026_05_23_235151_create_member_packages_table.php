<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('member_packages', function (Blueprint $table) {
            $table->id();
            $table->string('KodePaket', 50)->unique();
            $table->string('NamaPaket', 150);
            $table->double('Harga')->default(0);
            $table->enum('Tipe', ['DISCOUNT', 'QUOTA', 'UNLIMITED'])->default('UNLIMITED');
            $table->integer('ValidDays')->default(30)->comment('Masa aktif dalam hari');
            $table->integer('MaxPlay')->default(0)->comment('Batas pertemuan (0 = unlimited)');
            $table->integer('maxTimePerPlay')->default(0)->comment('Batas jam dalam menit (0 = unlimited)');
            $table->double('MemberPrice')->default(0)->comment('Harga diskon jika tipe DISCOUNT');
            $table->string('RecordOwnerID', 50);
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
        Schema::dropIfExists('member_packages');
    }
}
