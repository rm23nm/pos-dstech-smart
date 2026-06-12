<?php

namespace App\Models;

class KaryawanPayroll extends BaseModel
{
    protected $table = 'karyawan_payroll';

    protected $fillable = [
        'user_id',
        'RecordOwnerID',
        'GajiPokok',
        'KodeAkunGaji'
    ];
}
