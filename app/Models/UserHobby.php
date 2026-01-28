<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHobby extends Model
{
    protected $table = 'user_hobbies';
    protected $fillable = ['user_id', 'hobby_id'];
    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function hobby(): BelongsTo
    {
        return $this->belongsTo(Hobby::class, 'hobby_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
