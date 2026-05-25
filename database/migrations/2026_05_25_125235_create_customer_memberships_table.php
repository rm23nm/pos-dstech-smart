<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_memberships', function (Blueprint $table) {
            $table->id();
            $table->string('KodePelanggan', 55);
            $table->string('KodePaketMember', 55);
            $table->dateTime('ValidUntil')->nullable();
            $table->integer('MaxPlay')->default(0);
            $table->integer('Played')->default(0);
            $table->integer('maxTimePerPlay')->default(0);
            $table->integer('RecordOwnerID')->default(0);
            $table->timestamps();
        });

        if (Schema::hasTable('member_packages')) {
            Schema::table('member_packages', function (Blueprint $table) {
                if (!Schema::hasColumn('member_packages', 'TargetKategori')) {
                    $table->string('TargetKategori', 100)->nullable()->after('Tipe');
                }
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
        Schema::dropIfExists('customer_memberships');

        if (Schema::hasTable('member_packages')) {
            Schema::table('member_packages', function (Blueprint $table) {
                if (Schema::hasColumn('member_packages', 'TargetKategori')) {
                    $table->dropColumn('TargetKategori');
                }
            });
        }
    }
}
