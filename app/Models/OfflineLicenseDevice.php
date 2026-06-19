<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineLicenseDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'offline_license_id',
        'hardware_id',
        'device_name',
    ];

    public function offlineLicense()
    {
        return $this->belongsTo(OfflineLicense::class);
    }
}
