<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoCodeRedemption extends Model
{
    protected $fillable = [
        'promo_code_id', 'user_id', 'product_id', 'transaction_id', 'status', 'validated_at', 'redeemed_at',
    ];

    protected function casts(): array
    {
        return [
            'validated_at' => 'datetime',
            'redeemed_at' => 'datetime',
        ];
    }

    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class);
    }
}
