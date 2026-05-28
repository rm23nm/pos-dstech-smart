<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessSetting extends Model
{
    use HasFactory;

    protected $table = 'access_settings';

    protected $fillable = [
        'KodePartner',
        'setting_key',
        'setting_value'
    ];
}
