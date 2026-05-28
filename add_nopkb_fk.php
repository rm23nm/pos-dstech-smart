<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

if (!Schema::hasColumn('fakturpenjualanheader', 'NoPKB')) {
    Schema::table('fakturpenjualanheader', function (Blueprint $table) {
        $table->string('NoPKB', 50)->nullable()->after('NoTransaksi');
    });
    echo "Added NoPKB to fakturpenjualanheader.\n";
} else {
    echo "NoPKB already exists.\n";
}
