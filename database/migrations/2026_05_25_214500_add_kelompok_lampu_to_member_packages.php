<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKelompokLampuToMemberPackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('member_packages')) {
            Schema::table('member_packages', function (Blueprint $table) {
                if (!Schema::hasColumn('member_packages', 'KelompokLampu')) {
                    $table->string('KelompokLampu', 50)->nullable()->after('TargetKategori');
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
        if (Schema::hasTable('member_packages')) {
            Schema::table('member_packages', function (Blueprint $table) {
                if (Schema::hasColumn('member_packages', 'KelompokLampu')) {
                    $table->dropColumn('KelompokLampu');
                }
            });
        }
    }
}
