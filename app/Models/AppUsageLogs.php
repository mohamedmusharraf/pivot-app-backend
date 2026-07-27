<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppUsageLogs extends Model
{
    use HasFactory;

    protected $table = 'app_usage_logs';

    protected $fillable = [
        'user_id',
        'app_name',
        'package_name',
        'started_at',
        'ended_at',
        'duration_minutes',
        'opened_count',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_minutes' => 'integer',
        'opened_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}