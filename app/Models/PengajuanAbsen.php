<?php

namespace App\Models;

class PengajuanAbsen extends BaseModel
{
    protected $table = 'pengajuan_absen';

    protected $fillable = [
        'user_id',
        'RecordOwnerID',
        'JenisPengajuan',
        'TanggalMulai',
        'TanggalSelesai',
        'Keterangan',
        'BuktiDokumen',
        'StatusApproval',
        'ApprovedBy',
    ];
}
