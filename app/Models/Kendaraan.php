<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraan';
    protected $primaryKey = 'KodeKendaraan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'KodeKendaraan',
        'KodePelanggan',
        'PlatNomor',
        'Merek',
        'JenisKendaraan',
        'Tipe',
        'Tahun',
        'Warna',
        'NoMesin',
        'NoRangka',
        'NamaSTNK'
    ];
}
