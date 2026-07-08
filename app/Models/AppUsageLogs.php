<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppUsageLogs extends Model
{
    protected $table = 'app_usage_logs';

    protected $fillable = [
        'user_id',
        'app_name',
        'package_name',
        'started_at',
        'ended_at',
        'usage_minutes'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'usage_minutes' => 'integer',
    ];

    /**
     * Get the user that owns the app usage log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
