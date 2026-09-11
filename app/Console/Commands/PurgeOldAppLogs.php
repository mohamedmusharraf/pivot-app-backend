<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AppUsageLogs;
use App\Models\AppBlockLog;
use App\Models\FocusSessionLogs;
use App\Models\ActivityLogs;
use App\Models\GoalLogs;
use App\Models\EmotionLogs;
use App\Models\StreakLogs;

class PurgeOldAppLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-old-usage-logs {--days=30 : Number of days to retain logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete application usage and analytics logs older than specified days (default 30 days / 1 month)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Purging app analytics records older than {$days} days (before {$cutoffDate->toDateTimeString()})...");

        $usageCount = AppUsageLogs::where('created_at', '<', $cutoffDate)
            ->orWhere('started_at', '<', $cutoffDate)
            ->delete();

        $blockCount = AppBlockLog::where('created_at', '<', $cutoffDate)->delete();
        $focusCount = FocusSessionLogs::where('created_at', '<', $cutoffDate)->delete();
        $activityCount = ActivityLogs::where('created_at', '<', $cutoffDate)->delete();
        $goalCount = GoalLogs::where('created_at', '<', $cutoffDate)->delete();
        $emotionCount = EmotionLogs::where('created_at', '<', $cutoffDate)->delete();
        $streakCount = StreakLogs::where('created_at', '<', $cutoffDate)->delete();

        $totalDeleted = $usageCount + $blockCount + $focusCount + $activityCount + $goalCount + $emotionCount + $streakCount;

        $this->info("Deleted {$usageCount} App Usage logs.");
        $this->info("Deleted {$totalDeleted} total analytics log records older than {$days} days.");

        return Command::SUCCESS;
    }
}
