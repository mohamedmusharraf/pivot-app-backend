<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppBlockLog extends Model
{
    protected $table = 'app_block_logs';

    protected $fillable = [
        'user_id',
        'app_name',
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

    /**
     * Get the user that owns the app block log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
