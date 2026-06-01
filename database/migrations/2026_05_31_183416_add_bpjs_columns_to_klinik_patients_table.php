<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBpjsColumnsToKlinikPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('klinik_patients', function (Blueprint $table) {
            if (!Schema::hasColumn('klinik_patients', 'KelasRawatBPJS')) {
                $table->string('KelasRawatBPJS')->nullable()->after('NoKartuBPJS');
            }
            if (!Schema::hasColumn('klinik_patients', 'FaskesBPJS')) {
                $table->string('FaskesBPJS')->nullable()->after('KelasRawatBPJS');
            }
            if (!Schema::hasColumn('klinik_patients', 'StatusBPJS')) {
                $table->string('StatusBPJS')->nullable()->after('FaskesBPJS');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('klinik_patients', function (Blueprint $table) {
            if (Schema::hasColumn('klinik_patients', 'KelasRawatBPJS')) {
                $table->dropColumn('KelasRawatBPJS');
            }
            if (Schema::hasColumn('klinik_patients', 'FaskesBPJS')) {
                $table->dropColumn('FaskesBPJS');
            }
            if (Schema::hasColumn('klinik_patients', 'StatusBPJS')) {
                $table->dropColumn('StatusBPJS');
            }
        });
    }
}
