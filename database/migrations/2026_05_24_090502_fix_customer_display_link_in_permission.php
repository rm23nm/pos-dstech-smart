<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE permission SET Link = 'fpenjualan/custdisplay_new' WHERE Link = 'fpenjualan-custdisplay'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("UPDATE permission SET Link = 'fpenjualan-custdisplay' WHERE Link = 'fpenjualan/custdisplay_new'");
    }
};
