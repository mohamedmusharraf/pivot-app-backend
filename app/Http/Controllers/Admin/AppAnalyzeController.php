<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AppUsageLogs;
use App\Models\AppBlockLog;
use App\Models\FocusSessionLogs;
use App\Models\ActivityLogs;
use App\Models\GoalLogs;
use App\Models\EmotionLogs;
use App\Models\StreakLogs;
use Illuminate\Http\Request;

class AppAnalyzeController extends Controller
{
    public function index()
    {
        return view('admin.app-analyze.index');
    }

    /**
     * View detailed analytics for a specific individual user.
     */
    public function userAnalytics(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $activeTab = $request->get('tab', 'app-usage');

        // Fetch user specific logs based on tab
        switch ($activeTab) {
            case 'app-usage':
                $logs = AppUsageLogs::where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(10);
                $kpis = [
                    ['label' => 'Total Usage Time', 'value' => round(AppUsageLogs::where('user_id', $userId)->sum('duration_minutes')) . ' mins'],
                    ['label' => 'App Opens', 'value' => AppUsageLogs::where('user_id', $userId)->sum('opened_count')],
                    ['label' => 'Favorite App', 'value' => AppUsageLogs::where('user_id', $userId)->select('app_name')->groupBy('app_name')->orderByRaw('SUM(duration_minutes) DESC')->first()?->app_name ?? 'N/A'],
                ];
                break;

            case 'app-block':
                $logs = AppBlockLog::where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(10);
                $kpis = [
                    ['label' => 'Total Blocks', 'value' => AppBlockLog::where('user_id', $userId)->count()],
                    ['label' => 'Successful Blocks', 'value' => AppBlockLog::where('user_id', $userId)->where('success', true)->count()],
                    ['label' => 'Time Saved', 'value' => AppBlockLog::where('user_id', $userId)->sum('time_saved_minutes') . ' mins'],
                ];
                break;

            case 'focus-session':
                $logs = FocusSessionLogs::where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(10);
                $kpis = [
                    ['label' => 'Focus Sessions', 'value' => FocusSessionLogs::where('user_id', $userId)->count()],
                    ['label' => 'Completed', 'value' => FocusSessionLogs::where('user_id', $userId)->where('completed', true)->count()],
                    ['label' => 'Total Focused Time', 'value' => FocusSessionLogs::where('user_id', $userId)->sum('duration_minutes') . ' mins'],
                ];
                break;

            case 'activity':
                $logs = ActivityLogs::with('activity')->where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(10);
                $kpis = [
                    ['label' => 'Activities Started', 'value' => ActivityLogs::where('user_id', $userId)->count()],
                    ['label' => 'Completed', 'value' => ActivityLogs::where('user_id', $userId)->where('completed', true)->count()],
                    ['label' => 'Total Activity Mins', 'value' => ActivityLogs::where('user_id', $userId)->sum('duration_minutes') . ' mins'],
                ];
                break;

            case 'goal':
                $logs = GoalLogs::where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(10);
                $kpis = [
                    ['label' => 'Goals Set', 'value' => GoalLogs::where('user_id', $userId)->count()],
                    ['label' => 'Achieved', 'value' => GoalLogs::where('user_id', $userId)->where('completed', true)->count()],
                    ['label' => 'Avg Achieved Mins', 'value' => round(GoalLogs::where('user_id', $userId)->avg('achieved_minutes'), 1) . ' mins'],
                ];
                break;

            case 'emotion':
                $logs = EmotionLogs::where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(10);
                $kpis = [
                    ['label' => 'Emotion Check-ins', 'value' => EmotionLogs::where('user_id', $userId)->count()],
                    ['label' => 'Dominant Mood', 'value' => EmotionLogs::where('user_id', $userId)->select('emotion')->groupBy('emotion')->orderByRaw('COUNT(*) DESC')->first()?->emotion ?? 'N/A'],
                ];
                break;

            case 'streak':
                $logs = StreakLogs::where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(10);
                $streak = StreakLogs::where('user_id', $userId)->first();
                $kpis = [
                    ['label' => 'Current Streak', 'value' => ($streak?->current_streak ?? 0) . ' days'],
                    ['label' => 'Longest Streak', 'value' => ($streak?->longest_streak ?? 0) . ' days'],
                    ['label' => 'Last Active', 'value' => $streak?->last_completed_date ?? 'N/A'],
                ];
                break;
        }

        return view('admin.app-analyze.user-show', compact('user', 'logs', 'kpis', 'activeTab'));
    }

    /**
     * Purge log records older than 1 month (30 days).
     */
    public function purgeOldLogs(Request $request)
    {
        $cutoffDate = now()->subMonth();
        $tab = $request->get('tab', 'app-usage');

        $deletedCount = 0;

        switch ($tab) {
            case 'app-usage':
                $deletedCount = AppUsageLogs::where('created_at', '<', $cutoffDate)
                    ->orWhere('started_at', '<', $cutoffDate)
                    ->delete();
                $label = 'App Usage';
                break;

            case 'app-block':
                $deletedCount = AppBlockLog::where('created_at', '<', $cutoffDate)->delete();
                $label = 'App Block';
                break;

            case 'focus-session':
                $deletedCount = FocusSessionLogs::where('created_at', '<', $cutoffDate)->delete();
                $label = 'Focus Session';
                break;

            case 'activity':
                $deletedCount = ActivityLogs::where('created_at', '<', $cutoffDate)->delete();
                $label = 'Activity';
                break;

            case 'goal':
                $deletedCount = GoalLogs::where('created_at', '<', $cutoffDate)->delete();
                $label = 'Goal';
                break;

            case 'emotion':
                $deletedCount = EmotionLogs::where('created_at', '<', $cutoffDate)->delete();
                $label = 'Emotion';
                break;

            case 'streak':
                $deletedCount = StreakLogs::where('created_at', '<', $cutoffDate)->delete();
                $label = 'Streak';
                break;

            case 'all':
            default:
                $usageCount = AppUsageLogs::where('created_at', '<', $cutoffDate)->orWhere('started_at', '<', $cutoffDate)->delete();
                $blockCount = AppBlockLog::where('created_at', '<', $cutoffDate)->delete();
                $focusCount = FocusSessionLogs::where('created_at', '<', $cutoffDate)->delete();
                $activityCount = ActivityLogs::where('created_at', '<', $cutoffDate)->delete();
                $goalCount = GoalLogs::where('created_at', '<', $cutoffDate)->delete();
                $emotionCount = EmotionLogs::where('created_at', '<', $cutoffDate)->delete();
                $streakCount = StreakLogs::where('created_at', '<', $cutoffDate)->delete();

                $deletedCount = $usageCount + $blockCount + $focusCount + $activityCount + $goalCount + $emotionCount + $streakCount;
                $label = 'all category';
                break;
        }

        return redirect()->route('admin.app-analyze.index', ['tab' => $tab])
            ->with('success', "Successfully purged {$deletedCount} {$label} log record(s) older than 1 month.");
    }
}