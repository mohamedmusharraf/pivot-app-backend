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
        'country',
        'gender',
        'date_of_birth',
        'screen_goal_minutes',
        'onboarding_completed',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'onboarding_completed' => 'boolean',
    ];

    /**
     * Relationships
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
