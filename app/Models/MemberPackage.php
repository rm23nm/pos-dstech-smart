<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPackage extends Model
{
    use HasFactory;

    protected $table = 'member_packages';
    
    protected $fillable = [
        'KodePaket',
        'NamaPaket',
        'Harga',
        'Tipe',
        'ValidDays',
        'MaxPlay',
        'maxTimePerPlay',
        'MemberPrice',
        'RecordOwnerID'
    ];
}
