<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tier extends Model
{
    use HasFactory;

    protected $table = 'tier';

    protected $fillable = [
        'name',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class,);
    }
}
