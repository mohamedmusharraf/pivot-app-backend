<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * Use user_id for implicit route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'user_id';
    }

    /**
     * Relationships
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function hobbies(): BelongsToMany
    {
        return $this->belongsToMany(
            Hobby::class,
            'user_hobbies',
            'user_id',
            'hobby_id',
            'user_id',
            'id'
        );
    }
}
