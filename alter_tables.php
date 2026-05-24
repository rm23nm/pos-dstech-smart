<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// 1. member_packages
if (!Schema::hasColumn('member_packages', 'KategoriPaket')) {
    Schema::table('member_packages', function (Blueprint $table) {
        $table->enum('KategoriPaket', ['HIBURAN', 'RETAIL', 'FNB'])->default('HIBURAN')->after('NamaPaket');
        $table->double('DiskonPersen')->default(0)->after('MemberPrice');
        $table->double('MaxGratisOngkir')->default(0)->after('DiskonPersen');
    });
    echo "member_packages altered.\n";
}

// 2. pelanggan
if (!Schema::hasColumn('pelanggan', 'DiskonMemberPersen')) {
    Schema::table('pelanggan', function (Blueprint $table) {
        $table->double('DiskonMemberPersen')->default(0)->after('MemberPrice');
        $table->double('SisaGratisOngkir')->default(0)->after('DiskonMemberPersen');
        $table->integer('PoinLoyalti')->default(0)->after('SisaGratisOngkir');
    });
    echo "pelanggan altered.\n";
}

// 3. company
if (!Schema::hasColumn('company', 'KonversiRupiahKePoin')) {
    Schema::table('company', function (Blueprint $table) {
        $table->double('KonversiRupiahKePoin')->default(10000)->after('PPN');
        $table->double('NilaiTukarPoin')->default(100)->after('KonversiRupiahKePoin');
    });
    echo "company altered.\n";
}
echo "Done.\n";
