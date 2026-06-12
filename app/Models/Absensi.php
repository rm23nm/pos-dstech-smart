<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends BaseModel
{
    use HasFactory;
    protected $table = 'absensi';

    protected $fillable = [
        'RecordOwnerID',
        'Tanggal',
        'user_id',
        'KodeShift',
        'JamMasuk',
        'JamPulang',
        'FotoMasuk',
        'FotoPulang',
        'StatusKehadiran',
        'Catatan'
    ];
}
