<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tier_id',
        'type',
        'environment',
        'active',
        'store',
        'product_id',
        'revenuecat_user_id',
        'started_at',
        'expires_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class,);
    }

    public function tier()
    {
        return $this->belongsTo(Tier::class);
    }
}
