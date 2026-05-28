<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessTransaction extends Model
{
    use HasFactory;

    protected $table = 'access_transactions';

    protected $fillable = [
        'KodePartner',
        'ticket_number',
        'plate_number',
        'vehicle_type',
        'entrance_time',
        'exit_time',
        'total_amount',
        'payment_method',
        'payment_status',
        'device_in_id',
        'device_out_id',
        'image_in',
        'image_out',
        'sync_status',
        'sync_at'
    ];

    protected $casts = [
        'entrance_time' => 'datetime',
        'exit_time' => 'datetime',
        'sync_at' => 'datetime',
        'sync_status' => 'boolean',
    ];

    public function deviceIn()
    {
        return $this->belongsTo(AccessDevice::class, 'device_in_id');
    }

    public function deviceOut()
    {
        return $this->belongsTo(AccessDevice::class, 'device_out_id');
    }
}
