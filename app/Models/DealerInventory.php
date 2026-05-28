<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerInventory extends BaseModel
{
    use HasFactory;
    
    protected $table = 'dealer_inventory';
    
    protected $fillable = [
        'KodeItem',
        'NoRangka',
        'NoMesin',
        'Warna',
        'Tahun',
        'HargaBeli',
        'Status',
        'RecordOwnerID',
        'created_at',
        'updated_at'
    ];
    
    public function item()
    {
        return $this->belongsTo(ItemMaster::class, 'KodeItem', 'KodeItem')->where('RecordOwnerID', $this->RecordOwnerID);
    }
}
