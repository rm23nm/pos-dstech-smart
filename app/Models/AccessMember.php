<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessMember extends Model
{
    use HasFactory;

    protected $table = 'access_members';

    protected $fillable = [
        'KodePartner',
        'plate_number',
        'card_uid',
        'name',
        'member_type',
        'expiry_date',
        'status'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];
}
