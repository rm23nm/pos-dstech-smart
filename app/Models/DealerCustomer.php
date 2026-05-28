<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerCustomer extends BaseModel
{
    use HasFactory;
    
    protected $table = 'dealer_customers';
    protected $primaryKey = 'KodePelanggan';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'KodePelanggan',
        'NamaPelanggan',
        'Alamat',
        'NoHP',
        'NIK',
        'RecordOwnerID',
        'created_at',
        'updated_at'
    ];
}
