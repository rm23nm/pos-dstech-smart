<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerLeasing extends BaseModel
{
    use HasFactory;
    
    protected $table = 'dealer_leasing';
    protected $primaryKey = 'KodeLeasing';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'KodeLeasing',
        'NamaLeasing',
        'RecordOwnerID',
        'created_at',
        'updated_at'
    ];
}
