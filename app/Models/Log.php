<?php
namespace app\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['level', 'message', 'context', 'extra', 'channel'];

    protected $casts = [
        'context' => 'array',
        'extra'   => 'array',
    ];
}
