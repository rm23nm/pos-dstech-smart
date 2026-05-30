<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeRecordOwnerIdTypeInCustomerMemberships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('customer_memberships')) {
            // Using DB::statement because modifying columns requires doctrine/dbal which might not be installed,
            // or we can use change() if it is. We'll use DB::statement for safety.
            DB::statement('ALTER TABLE customer_memberships MODIFY RecordOwnerID VARCHAR(255) NULL');
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
            DB::statement('ALTER TABLE customer_memberships MODIFY RecordOwnerID INT NOT NULL DEFAULT 0');
        }
    }
}
