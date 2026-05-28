<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerFakturHeader extends BaseModel
{
    use HasFactory;
    
    protected $table = 'dealer_faktur_header';
    protected $primaryKey = 'NoTransaksi';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'NoTransaksi',
        'TglTransaksi',
        'KodePelanggan',
        'TotalTransaksi',
        'Potongan',
        'Pajak',
        'TotalNetto',
        'TotalPembayaran',
        'StatusPembayaran',
        'JenisPenjualan',
        'KodeLeasing',
        'UangMuka',
        'Tenor',
        'Cicilan',
        'KodeSales',
        'RecordOwnerID',
        'created_at',
        'updated_at'
    ];

    public function customer()
    {
        return $this->belongsTo(DealerCustomer::class, 'KodePelanggan', 'KodePelanggan')->where('RecordOwnerID', $this->RecordOwnerID);
    }

    public function leasing()
    {
        return $this->belongsTo(DealerLeasing::class, 'KodeLeasing', 'KodeLeasing')->where('RecordOwnerID', $this->RecordOwnerID);
    }

    public function details()
    {
        return $this->hasMany(DealerFakturDetail::class, 'NoTransaksi', 'NoTransaksi')->where('RecordOwnerID', $this->RecordOwnerID);
    }
}
