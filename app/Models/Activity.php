<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'hobby_id',
        'activity_title',
        'instruction',
        'activity_type',
        'subcategory',
        'duration_minutes',
        'tier',
        'cost',
        'location',
        'age_range',
        'neurodivergent_friendly',
        'neurodivergent_notes',
        'sensory_tags',
        'social_type',
        'energy_level',
        'outcome_tag',
        'mood_match',
    ];

    public function hobby()
    {
        return $this->belongsTo(Hobby::class);
    }

    public function userHobby()
    {
        return $this->belongsTo(UserHobby::class);
    }

    protected $casts = [
        'mood_match' => 'array',
    ];
}
