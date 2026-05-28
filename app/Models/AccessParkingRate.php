<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessParkingRate extends Model
{
    use HasFactory;

    protected $table = 'access_parking_rates';

    protected $fillable = [
        'KodePartner',
        'vehicle_type',
        'base_rate',
        'hourly_rate',
        'max_daily_rate'
    ];
}
