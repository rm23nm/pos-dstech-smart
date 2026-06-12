<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanKomponenGaji extends Model
{
    use HasFactory;

    protected $table = 'karyawan_komponen_gaji';

    protected $fillable = [
        'RecordOwnerID',
        'user_id',
        'Jenis',
        'NamaKomponen',
        'Sifat',
        'Nominal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
