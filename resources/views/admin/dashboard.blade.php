@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="#">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Dashboard</li>
</ul>

{{-- ══════════════════════════════════════════════════════════════════════
     ROW 1 — Primary KPI Cards
══════════════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-4" style="margin-bottom: 1.5rem;">

    {{-- Total Users --}}
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Total Users</span>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <span style="color: {{ $usersGrowth >= 0 ? 'var(--success)' : 'var(--danger)' }}; font-size: 0.75rem; font-weight: 600;">
                <i class="fa-solid fa-arrow-{{ $usersGrowth >= 0 ? 'up' : 'down' }}"></i>
                {{ $usersGrowth >= 0 ? '+' : '' }}{{ $usersGrowth }}% vs last month
            </span>
        </div>
        <div class="stat-icon primary">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>

    {{-- Active Users --}}
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Active Users</span>
            <div class="stat-value">{{ number_format($activeUsers) }}</div>
            <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600;">
                +{{ $newUsersThisMonth }} new this month
            </span>
        </div>
        <div class="stat-icon success">
            <i class="fa-solid fa-user-check"></i>
        </div>
    </div>

    {{-- Active Subscriptions --}}
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Active Subscriptions</span>
            <div class="stat-value">{{ number_format($activeSubscriptions) }}</div>
            <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600;">
                {{ $totalSubscriptions }} total subscriptions
            </span>
        </div>
        <div class="stat-icon warning">
            <i class="fa-solid fa-credit-card"></i>
        </div>
    </div>

    {{-- Activities Completed --}}
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Activities Completed</span>
            <div class="stat-value">{{ number_format($completedActivityLogs) }}</div>
            <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600;">
                {{ $activityLogsThisMonth }} this month
            </span>
        </div>
        <div class="stat-icon primary">
            <i class="fa-solid fa-circle-check"></i>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════════
     ROW 2 — Secondary KPI Cards (3 cols + sidebar)
══════════════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-4" style="margin-bottom: 1.5rem;">

    {{-- Focus Sessions --}}
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Focus Sessions</span>
            <div class="stat-value">{{ number_format($totalFocusSessions) }}</div>
            <span style="color: var(--success); font-size: 0.75rem; font-weight: 600;">
                {{ number_format($completedFocusSessions) }} completed
            </span>
        </div>
        <div class="stat-icon success">
            <i class="fa-solid fa-brain"></i>
        </div>
    </div>

    {{-- Research Articles --}}
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Research Articles</span>
            <div class="stat-value">{{ number_format($totalResearchArticles) }}</div>
            <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600;">
                Published articles
            </span>
        </div>
        <div class="stat-icon warning">
            <i class="fa-solid fa-book-open"></i>
        </div>
    </div>

    {{-- Goals Completed --}}
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Goals Completed</span>
            <div class="stat-value">{{ number_format($completedGoals) }}</div>
            <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600;">
                of {{ number_format($totalGoalLogs) }} total goals
            </span>
        </div>
        <div class="stat-icon primary">
            <i class="fa-solid fa-bullseye"></i>
        </div>
    </div>

    {{-- Challenges Completed --}}
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Challenges Completed</span>
            <div class="stat-value">{{ number_format($completedChallenges) }}</div>
            <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600;">
                of {{ number_format($totalChallengeLogs) }} total
            </span>
        </div>
        <div class="stat-icon success">
            <i class="fa-solid fa-trophy"></i>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════════
     ROW 3 — More Detail Metrics
══════════════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-4" style="margin-bottom: 1.5rem;">

    {{-- Onboarding Completed --}}
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Onboarding Completed</span>
            <div class="stat-value">{{ number_format($onboardingCompleted) }}</div>
            <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600;">
                of {{ number_format($totalProfiles) }} profiles
            </span>
        </div>
        <div class="stat-icon primary">
            <i class="fa-solid fa-list-check"></i>
        </div>
    </div>

    {{-- Invitations --}}
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Invitations Accepted</span>
            <div class="stat-value">{{ number_format($acceptedInvitations) }}</div>
            <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600;">
                of {{ number_format($totalInvitations) }} sent
            </span>
        </div>
        <div class="stat-icon warning">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
    </div>

    {{-- Avg Current Streak --}}
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Avg. Current Streak</span>
            <div class="stat-value">{{ round($avgStreak, 1) }}<span style="font-size: 1rem; font-weight: 500;"> days</span></div>
            <span style="color: var(--success); font-size: 0.75rem; font-weight: 600;">
                <i class="fa-solid fa-fire"></i> Max: {{ $maxStreak }} days
            </span>
        </div>
        <div class="stat-icon success">
            <i class="fa-solid fa-fire-flame-curved"></i>
        </div>
    </div>

    {{-- Emotion Logs --}}
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Emotion Logs</span>
            <div class="stat-value">{{ number_format($totalEmotionLogs) }}</div>
            <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600;">
                Total mood entries
            </span>
        </div>
        <div class="stat-icon warning">
            <i class="fa-solid fa-face-smile"></i>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════════
     ROW 4 — Summary Panels: Content Overview + Top Hobbies
══════════════════════════════════════════════════════════════════════ --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">

    {{-- Content Overview --}}
    <div class="card">
        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-heading); margin-bottom: 1.25rem;">
            <i class="fa-solid fa-layer-group" style="margin-right: 0.5rem; color: var(--primary);"></i>Content Overview
        </h3>

        @php
            $contentItems = [
                ['label' => 'Total Activities',       'value' => $totalActivities,     'icon' => 'fa-person-running',    'color' => 'var(--primary)'],
                ['label' => 'Hobbies / Categories',   'value' => $totalHobbies,        'icon' => 'fa-heart',             'color' => '#a78bfa'],
                ['label' => 'Research Articles',      'value' => $totalResearchArticles,'icon' => 'fa-book-open',        'color' => '#f59e0b'],
                ['label' => 'App Usage Logs',         'value' => $totalAppUsageLogs,   'icon' => 'fa-mobile-screen',    'color' => '#10b981'],
                ['label' => 'Activity Logs (Total)',  'value' => $totalActivityLogs,   'icon' => 'fa-chart-bar',        'color' => '#3b82f6'],
            ];
        @endphp

        <div style="display: flex; flex-direction: column; gap: 0.875rem;">
            @foreach($contentItems as $item)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: var(--bg-secondary); border-radius: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.4rem; background: {{ $item['color'] }}20; display: flex; align-items: center; justify-content: center; color: {{ $item['color'] }}; font-size: 0.875rem;">
                        <i class="fa-solid {{ $item['icon'] }}"></i>
                    </div>
                    <span style="font-size: 0.875rem; color: var(--text-muted);">{{ $item['label'] }}</span>
                </div>
                <span style="font-weight: 700; color: var(--text-heading); font-size: 1rem;">{{ number_format($item['value']) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Top Hobbies by Activity Count --}}
    <div class="card">
        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-heading); margin-bottom: 1.25rem;">
            <i class="fa-solid fa-star" style="margin-right: 0.5rem; color: #f59e0b;"></i>Top Hobbies by Activities
        </h3>
        @forelse($topHobbies as $hobby)
            @php $pct = $totalActivities > 0 ? round(($hobby->activities_count / $totalActivities) * 100) : 0; @endphp
            <div style="margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem;">
                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--text-heading);">{{ $hobby->name }}</span>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">{{ number_format($hobby->activities_count) }} activities</span>
                </div>
                <div style="background: var(--bg-secondary); border-radius: 9999px; height: 0.45rem; overflow: hidden;">
                    <div style="width: {{ $pct }}%; background: var(--primary); height: 100%; border-radius: 9999px; transition: width 0.6s ease;"></div>
                </div>
            </div>
        @empty
            <p style="color: var(--text-muted); font-size: 0.875rem;">No hobby data available.</p>
        @endforelse
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════════
     ROW 5 — Engagement Breakdown (Progress Rings)
══════════════════════════════════════════════════════════════════════ --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 1.5rem;">

    {{-- Goal Completion Rate --}}
    @php
        $goalRate = $totalGoalLogs > 0 ? round(($completedGoals / $totalGoalLogs) * 100) : 0;
        $activityRate = $totalActivityLogs > 0 ? round(($completedActivityLogs / $totalActivityLogs) * 100) : 0;
        $focusRate = $totalFocusSessions > 0 ? round(($completedFocusSessions / $totalFocusSessions) * 100) : 0;
        $inviteRate = $totalInvitations > 0 ? round(($acceptedInvitations / $totalInvitations) * 100) : 0;
        $onboardRate = $totalProfiles > 0 ? round(($onboardingCompleted / $totalProfiles) * 100) : 0;
        $challengeRate = $totalChallengeLogs > 0 ? round(($completedChallenges / $totalChallengeLogs) * 100) : 0;
    @endphp

    <div class="card" style="text-align: center;">
        <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-heading); margin-bottom: 1rem;">
            <i class="fa-solid fa-bullseye" style="color: var(--primary); margin-right: 0.4rem;"></i>Goal Completion
        </h3>
        <div class="dash-ring-wrap">
            <svg viewBox="0 0 100 100" class="dash-ring" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="40" fill="none" stroke="var(--bg-secondary)" stroke-width="10"/>
                <circle cx="50" cy="50" r="40" fill="none" stroke="var(--primary)" stroke-width="10"
                    stroke-dasharray="{{ round($goalRate * 2.513) }} 251.3"
                    stroke-dashoffset="62.8" stroke-linecap="round"/>
            </svg>
            <div class="dash-ring-label">{{ $goalRate }}%</div>
        </div>
        <p style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.75rem;">{{ number_format($completedGoals) }} / {{ number_format($totalGoalLogs) }} goals</p>
    </div>

    <div class="card" style="text-align: center;">
        <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-heading); margin-bottom: 1rem;">
            <i class="fa-solid fa-circle-check" style="color: #10b981; margin-right: 0.4rem;"></i>Activity Completion
        </h3>
        <div class="dash-ring-wrap">
            <svg viewBox="0 0 100 100" class="dash-ring" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="40" fill="none" stroke="var(--bg-secondary)" stroke-width="10"/>
                <circle cx="50" cy="50" r="40" fill="none" stroke="#10b981" stroke-width="10"
                    stroke-dasharray="{{ round($activityRate * 2.513) }} 251.3"
                    stroke-dashoffset="62.8" stroke-linecap="round"/>
            </svg>
            <div class="dash-ring-label">{{ $activityRate }}%</div>
        </div>
        <p style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.75rem;">{{ number_format($completedActivityLogs) }} / {{ number_format($totalActivityLogs) }} activities</p>
    </div>

    <div class="card" style="text-align: center;">
        <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-heading); margin-bottom: 1rem;">
            <i class="fa-solid fa-brain" style="color: #a78bfa; margin-right: 0.4rem;"></i>Focus Session Rate
        </h3>
        <div class="dash-ring-wrap">
            <svg viewBox="0 0 100 100" class="dash-ring" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="40" fill="none" stroke="var(--bg-secondary)" stroke-width="10"/>
                <circle cx="50" cy="50" r="40" fill="none" stroke="#a78bfa" stroke-width="10"
                    stroke-dasharray="{{ round($focusRate * 2.513) }} 251.3"
                    stroke-dashoffset="62.8" stroke-linecap="round"/>
            </svg>
            <div class="dash-ring-label">{{ $focusRate }}%</div>
        </div>
        <p style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.75rem;">{{ number_format($completedFocusSessions) }} / {{ number_format($totalFocusSessions) }} sessions</p>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════════
     ROW 6 — Extra Metrics Strip
══════════════════════════════════════════════════════════════════════ --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 1.5rem;">

    <div class="card" style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 3rem; height: 3rem; border-radius: 0.6rem; background: #f59e0b20; display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 1.25rem; flex-shrink: 0;">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
        <div>
            <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-heading);">{{ $inviteRate }}%</div>
            <div style="font-size: 0.8rem; color: var(--text-muted);">Invitation Accept Rate</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ number_format($acceptedInvitations) }} / {{ number_format($totalInvitations) }} invites</div>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 3rem; height: 3rem; border-radius: 0.6rem; background: #3b82f620; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 1.25rem; flex-shrink: 0;">
            <i class="fa-solid fa-list-check"></i>
        </div>
        <div>
            <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-heading);">{{ $onboardRate }}%</div>
            <div style="font-size: 0.8rem; color: var(--text-muted);">Onboarding Completion</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ number_format($onboardingCompleted) }} / {{ number_format($totalProfiles) }} profiles</div>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 3rem; height: 3rem; border-radius: 0.6rem; background: #ef444420; display: flex; align-items: center; justify-content: center; color: #ef4444; font-size: 1.25rem; flex-shrink: 0;">
            <i class="fa-solid fa-trophy"></i>
        </div>
        <div>
            <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-heading);">{{ $challengeRate }}%</div>
            <div style="font-size: 0.8rem; color: var(--text-muted);">Challenge Completion Rate</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ number_format($completedChallenges) }} / {{ number_format($totalChallengeLogs) }} challenges</div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════════
     ROW 7 — Recent Users Table
══════════════════════════════════════════════════════════════════════ --}}
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <div>
            <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--text-heading);">
                <i class="fa-solid fa-clock-rotate-left" style="margin-right: 0.5rem; color: var(--primary);"></i>Users Logged In (Last 7 Days)
            </h3>
            <p style="color: var(--text-muted); font-size: 0.8125rem;">Overview of users who logged into the application during the past 7 days.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="font-size: 0.8125rem;">
            View All Users <i class="fa-solid fa-arrow-right" style="margin-left: 0.3rem;"></i>
        </a>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User Profile</th>
                    <th>Provider</th>
                    <th>Status</th>
                    <th>Last Login</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentUsers as $user)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-heading);">{{ $user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->email }}</div>
                    </td>
                    <td>
                        @if(strtolower($user->provider) === 'google')
                        <i class="fa-brands fa-google" style="margin-right: 4px;"></i> Google
                        @elseif(strtolower($user->provider) === 'apple')
                        <i class="fa-brands fa-apple" style="margin-right: 4px;"></i> Apple
                        @else
                        <i class="fa-solid fa-envelope" style="margin-right: 4px;"></i> Email
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ strtolower($user->status) === 'active' ? 'success' : 'warning' }}">
                            {{ ucfirst($user->status ?? 'Active') }}
                        </span>
                    </td>
                    <td>
                        {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--text-muted);">No users logged in during the last 7 days.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span style="color: var(--text-muted); font-size: 0.8125rem;">
            Showing {{ $recentUsers->firstItem() ?? 0 }} to {{ $recentUsers->lastItem() ?? 0 }} of {{ $recentUsers->total() }} entries
        </span>
        {{ $recentUsers->links('partials.pagination') }}
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════
     Dashboard Ring CSS (inline, scoped)
══════════════════════════════════════════════════════════════════════ --}}
<style>
.dash-ring-wrap {
    position: relative;
    width: 7rem;
    height: 7rem;
    margin: 0 auto;
}
.dash-ring {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}
.dash-ring-label {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    font-weight: 800;
    color: var(--text-heading);
}
</style>
@endsection