<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMembership extends Model
{
    use HasFactory;

    protected $table = 'customer_memberships';
    
    protected $fillable = [
        'KodePelanggan',
        'KodePaketMember',
        'ValidUntil',
        'MaxPlay',
        'Played',
        'maxTimePerPlay',
        'RecordOwnerID'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'KodePelanggan', 'KodePelanggan')->where('RecordOwnerID', $this->RecordOwnerID);
    }
}
