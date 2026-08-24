<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Activity;
use App\Models\ActivityLogs;
use App\Models\Research;
use App\Models\Hobby;
use App\Models\UserProfile;
use App\Models\FocusSessionLogs;
use App\Models\StreakLogs;
use App\Models\EmotionLogs;
use App\Models\GoalLogs;
use App\Models\ChallengeLog;
use App\Models\Invitation;
use App\Models\AppUsageLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Users ──────────────────────────────────────────────────────────
        $totalUsers        = User::count();
        $activeUsers       = User::where('status', 'active')->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year)
                                 ->count();
        $newUsersLastMonth = User::whereMonth('created_at', now()->subMonth()->month)
                                 ->whereYear('created_at', now()->subMonth()->year)
                                 ->count();
        $usersGrowth       = $newUsersLastMonth > 0
                             ? round((($newUsersThisMonth - $newUsersLastMonth) / $newUsersLastMonth) * 100, 1)
                             : 0;

        // ── Subscriptions ──────────────────────────────────────────────────
        $activeSubscriptions = Subscription::where('active', true)->count();
        $totalSubscriptions  = Subscription::count();

        // ── Activities ─────────────────────────────────────────────────────
        $totalActivities         = Activity::count();
        $totalActivityLogs       = ActivityLogs::count();
        $completedActivityLogs   = ActivityLogs::where('completed', true)->count();
        $activityLogsThisMonth   = ActivityLogs::whereMonth('created_at', now()->month)
                                               ->whereYear('created_at', now()->year)
                                               ->count();

        // ── Research Articles ──────────────────────────────────────────────
        $totalResearchArticles = Research::count();

        // ── Hobbies ────────────────────────────────────────────────────────
        $totalHobbies = Hobby::count();

        // ── User Profiles ──────────────────────────────────────────────────
        $totalProfiles            = UserProfile::count();
        $onboardingCompleted      = UserProfile::where('onboarding_completed', true)->count();

        // ── Focus Sessions ─────────────────────────────────────────────────
        $totalFocusSessions     = FocusSessionLogs::count();
        $completedFocusSessions = FocusSessionLogs::where('completed', true)->count();

        // ── Streaks ────────────────────────────────────────────────────────
        $avgStreak = StreakLogs::avg('current_streak') ?? 0;
        $maxStreak = StreakLogs::max('longest_streak') ?? 0;

        // ── Emotion Logs ───────────────────────────────────────────────────
        $totalEmotionLogs = EmotionLogs::count();

        // ── Goal Logs ──────────────────────────────────────────────────────
        $totalGoalLogs     = GoalLogs::count();
        $completedGoals    = GoalLogs::where('completed', true)->count();

        // ── Challenge Logs ─────────────────────────────────────────────────
        $totalChallengeLogs     = ChallengeLog::count();
        $completedChallenges    = ChallengeLog::where('status', 'completed')->count();

        // ── Invitations ────────────────────────────────────────────────────
        $totalInvitations    = Invitation::count();
        $acceptedInvitations = Invitation::where('status', 'accepted')->count();

        // ── App Usage ──────────────────────────────────────────────────────
        $totalAppUsageLogs = AppUsageLogs::count();

        // ── Recent Users (last 7 days) ─────────────────────────────────────
        $recentUsers = User::where('last_login_at', '>=', now()->subDays(7))
                           ->orderBy('last_login_at', 'desc')
                           ->paginate(10);

        // ── New Users per day (last 7 days) for mini chart ─────────────────
        $newUsersChart = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // ── Top Hobbies ────────────────────────────────────────────────────
        $topHobbies = Hobby::withCount('activities')
                           ->orderBy('activities_count', 'desc')
                           ->limit(7)
                           ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'newUsersThisMonth',
            'usersGrowth',
            'activeSubscriptions',
            'totalSubscriptions',
            'totalActivities',
            'totalActivityLogs',
            'completedActivityLogs',
            'activityLogsThisMonth',
            'totalResearchArticles',
            'totalHobbies',
            'totalProfiles',
            'onboardingCompleted',
            'totalFocusSessions',
            'completedFocusSessions',
            'avgStreak',
            'maxStreak',
            'totalEmotionLogs',
            'totalGoalLogs',
            'completedGoals',
            'totalChallengeLogs',
            'completedChallenges',
            'totalInvitations',
            'acceptedInvitations',
            'totalAppUsageLogs',
            'recentUsers',
            'newUsersChart',
            'topHobbies'
        ));
    }
}