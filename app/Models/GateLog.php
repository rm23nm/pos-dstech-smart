<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GateLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'access_type',
        'status',
        'message',
        'RecordOwnerID',
    ];
}
