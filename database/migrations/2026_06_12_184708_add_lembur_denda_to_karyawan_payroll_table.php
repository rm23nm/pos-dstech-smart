<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLemburDendaToKaryawanPayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('karyawan_payroll', function (Blueprint $table) {
            $table->decimal('TarifLemburPerJam', 15, 2)->default(0)->after('GajiPokok');
            $table->decimal('TarifDendaPerMenit', 15, 2)->default(0)->after('TarifLemburPerJam');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('karyawan_payroll', function (Blueprint $table) {
            $table->dropColumn(['TarifLemburPerJam', 'TarifDendaPerMenit']);
        });
    }
}
