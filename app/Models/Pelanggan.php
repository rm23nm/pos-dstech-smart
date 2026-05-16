<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 
class Pelanggan extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $table = 'pelanggan';
    protected $primaryKey = 'PelangganID'; // Ensure this matches your PK
 
    protected $fillable = [
        'KodePelanggan',
        'NamaPelanggan',
        'KodeGrupPelanggan',
        'LimitPiutang',
        'ProvID',
        'KotaID',
        'KelID',
        'KecID',
        'Email',
        'password', // Added for Login
        'NoTlp1',
        'NoTlp2',
        'Alamat',
        'Keterangan',
        'Status',
        'isPaidMembership',
        'MaxPlay',
        'Played',
        'MemberPrice',
        'maxTimePerPlay',
        'ValidUntil',
        'TglBerlanggananPaketBulanan',
        'RecordOwnerID',
        'created_at',
        'updated_at'
    ];
 
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
