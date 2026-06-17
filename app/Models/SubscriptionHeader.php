<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionHeader extends BaseModel
{
    use HasFactory;
    protected $table = "subscriptionheader";

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'subscriptiondetail', 'NoTransaksi', 'PermissionID', 'NoTransaksi', 'id');
    }
}
