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

    protected $casts = [
        'price'     => 'decimal:2',
        'total'     => 'integer',
        'remaining' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}
