<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RenameKitchenOrderStatusInTableorderheader extends Migration
{
    /**
     * Run the migrations.
     * Rename access_kitchen_order_status -> kitchen_order_status
     * (Menghapus prefix access_ karena POS belum pakai sistem prefix)
     *
     * @return void
     */
    public function up()
    {
        // Cek jika kolom lama (access_kitchen_order_status) masih ada
        $hasOldColumn = DB::select(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
             WHERE TABLE_SCHEMA = DATABASE() 
             AND TABLE_NAME = 'tableorderheader' 
             AND COLUMN_NAME = 'access_kitchen_order_status'"
        );

        // Cek jika kolom baru (kitchen_order_status) belum ada
        $hasNewColumn = DB::select(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
             WHERE TABLE_SCHEMA = DATABASE() 
             AND TABLE_NAME = 'tableorderheader' 
             AND COLUMN_NAME = 'kitchen_order_status'"
        );

        if (!empty($hasOldColumn) && empty($hasNewColumn)) {
            // Rename kolom lama ke kolom baru
            Schema::table('tableorderheader', function (Blueprint $table) {
                $table->renameColumn('access_kitchen_order_status', 'kitchen_order_status');
            });
        } elseif (empty($hasOldColumn) && empty($hasNewColumn)) {
            // Jika keduanya tidak ada, buat kolom baru
            Schema::table('tableorderheader', function (Blueprint $table) {
                $table->integer('kitchen_order_status')->default(0)->after('Status');
            });
        }
        // Jika kitchen_order_status sudah ada, skip (tidak perlu apa-apa)
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('tableorderheader', 'kitchen_order_status')) {
            Schema::table('tableorderheader', function (Blueprint $table) {
                $table->dropColumn('kitchen_order_status');
            });
        }
    }
}
