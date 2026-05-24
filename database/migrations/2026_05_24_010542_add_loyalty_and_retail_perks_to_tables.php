<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoyaltyAndRetailPerksToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Update member_packages table
        Schema::table('member_packages', function (Blueprint $table) {
            $table->enum('KategoriPaket', ['HIBURAN', 'RETAIL', 'FNB'])->default('HIBURAN')->after('NamaPaket');
            $table->double('DiskonPersen')->default(0)->after('MemberPrice')->comment('Persentase diskon untuk POS Retail/F&B');
            $table->double('MaxGratisOngkir')->default(0)->after('DiskonPersen')->comment('Maksimal nominal gratis ongkir untuk POS Retail/F&B');
        });

        // 2. Update pelanggan table
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->double('DiskonMemberPersen')->default(0)->after('MemberPrice')->comment('Diskon aktif milik member');
            $table->double('SisaGratisOngkir')->default(0)->after('DiskonMemberPersen')->comment('Sisa kuota gratis ongkir member');
            $table->integer('PoinLoyalti')->default(0)->after('SisaGratisOngkir')->comment('Poin loyalti pelanggan');
        });

        // 3. Update company table (settings for points)
        Schema::table('company', function (Blueprint $table) {
            $table->double('KonversiRupiahKePoin')->default(10000)->after('PPN')->comment('Setiap belanja nominal ini dapat 1 Poin');
            $table->double('NilaiTukarPoin')->default(100)->after('KonversiRupiahKePoin')->comment('1 Poin bernilai diskon sebesar nominal ini');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_packages', function (Blueprint $table) {
            $table->dropColumn(['KategoriPaket', 'DiskonPersen', 'MaxGratisOngkir']);
        });

        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropColumn(['DiskonMemberPersen', 'SisaGratisOngkir', 'PoinLoyalti']);
        });

        Schema::table('company', function (Blueprint $table) {
            $table->dropColumn(['KonversiRupiahKePoin', 'NilaiTukarPoin']);
        });
    }
}
