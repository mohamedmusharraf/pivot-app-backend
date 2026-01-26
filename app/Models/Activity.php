<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'hobby_id',
        'title',
        'description',
        'duration_minutes',
        'energy_level',
        'age_suitability',
        'neurodivergent_friendly',
    ];

    public function hobby()
    {
        return $this->belongsTo(Hobby::class);
    }
}
