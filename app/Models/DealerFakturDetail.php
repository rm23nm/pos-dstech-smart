<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerFakturDetail extends BaseModel
{
    use HasFactory;
    
    protected $table = 'dealer_faktur_detail';
    
    protected $fillable = [
        'NoTransaksi',
        'KodeItem',
        'NoRangka',
        'Harga',
        'Diskon',
        'SubTotal',
        'NamaSTNK',
        'KTPSTNK',
        'AlamatSTNK',
        'RecordOwnerID',
        'created_at',
        'updated_at'
    ];

    public function item()
    {
        return $this->belongsTo(ItemMaster::class, 'KodeItem', 'KodeItem')->where('RecordOwnerID', $this->RecordOwnerID);
    }
}
