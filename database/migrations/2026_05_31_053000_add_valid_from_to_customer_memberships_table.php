<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValidFromToCustomerMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('customer_memberships')) {
            Schema::table('customer_memberships', function (Blueprint $table) {
                if (!Schema::hasColumn('customer_memberships', 'ValidFrom')) {
                    $table->dateTime('ValidFrom')->nullable()->after('KodePaketMember');
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
        if (Schema::hasTable('customer_memberships')) {
            Schema::table('customer_memberships', function (Blueprint $table) {
                if (Schema::hasColumn('customer_memberships', 'ValidFrom')) {
                    $table->dropColumn('ValidFrom');
                }
            });
        }
    }
}
