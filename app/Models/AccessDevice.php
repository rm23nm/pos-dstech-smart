<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessDevice extends Model
{
    use HasFactory;
    
    protected $table = 'access_devices';
    
    protected $fillable = [
        'KodePartner',
        'name',
        'ip_address',
        'port',
        'camera_url',
        'type',
        'print_method',
        'printer_address',
        'status',
        'last_seen'
    ];
    
    protected $casts = [
        'last_seen' => 'datetime',
    ];
}
