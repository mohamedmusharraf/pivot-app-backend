<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmotionLogs extends Model
{
    protected $fillable = [
        'user_id',
        'emotion',
        'app_name',
        'logged_at'
    ];
}
