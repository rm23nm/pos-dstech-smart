<?php

namespace App\Models;

class SettingAbsensi extends BaseModel
{
    protected $table = 'setting_absensi';

    protected $fillable = [
        'RecordOwnerID',
        'ToleransiTelat',
        'DendaTelatPerMenit',
        'UpahLemburPerJam',
        'TitikKordinatKantor',
        'RadiusBebasAbsen',
    ];
}
