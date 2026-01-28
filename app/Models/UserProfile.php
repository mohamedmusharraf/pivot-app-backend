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
        'age_range',
        'screen_goal_hours',
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
        return $this->belongsTo(Users::class, 'user_id');
    } 
}
