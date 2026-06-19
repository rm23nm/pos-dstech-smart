<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_key',
        'client_name',
        'valid_until',
        'status',
    ];
}
