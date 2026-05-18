<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class DstechGlobalSubscriptionLedger extends BaseModel
{
    use HasFactory;

    protected $table = 'dstech_global_subscription_ledger';

    protected $fillable = [
        'dstech_app_source',
        'dstech_client_id',
        'dstech_client_name',
        'dstech_client_email',
        'dstech_client_phone',
        'dstech_package_name',
        'dstech_amount',
        'dstech_payment_status',
        'dstech_start_date',
        'dstech_end_date',
        'dstech_transaction_id',
    ];
}
