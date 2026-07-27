<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppBlockLog extends Model
{
    use HasFactory;

    protected $table = 'app_block_logs';

    protected $fillable = [
        'user_id',
        'app_name',
        'package_name',
        'blocked_at',
        'released_at',
        'attempted',
        'success',
        'time_saved_minutes',
    ];

    protected $casts = [
        'blocked_at' => 'datetime',
        'released_at' => 'datetime',
        'attempted' => 'boolean',
        'success' => 'boolean',
        'time_saved_minutes' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}