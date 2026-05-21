<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamConnection extends Model
{
    protected $fillable = [
        'user_id',
        'connected_user_id'
    ];
}
