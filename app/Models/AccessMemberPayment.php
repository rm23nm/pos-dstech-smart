<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessMemberPayment extends Model
{
    use HasFactory;

    protected $table = 'access_member_payments';

    protected $fillable = [
        'KodePartner',
        'member_id',
        'amount',
        'payment_date',
        'period_months',
        'payment_method'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(AccessMember::class, 'member_id');
    }
}
