<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percent',
        'offer_tag',
        'max_redemptions',
        'assigned_user_id',
        'one_per_user',
        'applicable_tier',
        'applicable_product_id',
        'expires_at',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'one_per_user' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'assigned_user_id');
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(PromoCodeRedemption::class);
    }
}
