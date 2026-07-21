<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengePacksWebhook extends Model
{
    protected $table = 'challenge_pack';
     protected $fillable = [
        'app_id',
        'user_id',
        'price',
        'product_id',
        'environment',
        'store',
        'type',
        'transaction_id',
        'total',
        'remaining',
        'status',
    ];
}
