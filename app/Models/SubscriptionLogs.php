<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionLogs extends Model
{
    protected $table = 'subscriptions_log';

    protected $fillable = [
        'user_id',
        'tier_id',
        'active',
        'store',
        'product_id',
        'revenuecat_user_id',
        'type',
        'environment',
        'started_at',
        'expires_at'
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');  
    }
}
