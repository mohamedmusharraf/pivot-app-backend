<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'user_id',
        'country_id',
        'gender',
        'date_of_birth',
        'set_your_goal',
        'weekly_goal_minutes',
        'onboarding_completed',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'onboarding_completed' => 'boolean',
        'weekly_goal_minutes' => 'integer',
    ];

    /**
     * Relationships
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
