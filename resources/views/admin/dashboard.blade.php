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
<div class="grid grid-cols-4 dash-mb">

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
     ROW 2 — Secondary KPI Cards
══════════════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-4 dash-mb">

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
<div class="grid grid-cols-4 dash-mb">

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
     CLASS-BASED grid so media queries can override it
══════════════════════════════════════════════════════════════════════ --}}
<div class="grid dash-grid-2col dash-mb">

    {{-- Content Overview --}}
    <div class="card">
        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-heading); margin-bottom: 1.25rem;">
            <i class="fa-solid fa-layer-group" style="margin-right: 0.5rem; color: var(--primary);"></i>Content Overview
        </h3>

        @php
            $contentItems = [
                ['label' => 'Total Activities',       'value' => $totalActivities,      'icon' => 'fa-person-running', 'color' => 'var(--primary)'],
                ['label' => 'Hobbies / Categories',   'value' => $totalHobbies,         'icon' => 'fa-heart',          'color' => '#a78bfa'],
                ['label' => 'Research Articles',      'value' => $totalResearchArticles, 'icon' => 'fa-book-open',     'color' => '#f59e0b'],
                ['label' => 'App Usage Logs',         'value' => $totalAppUsageLogs,    'icon' => 'fa-mobile-screen',  'color' => '#10b981'],
                ['label' => 'Activity Logs (Total)',  'value' => $totalActivityLogs,    'icon' => 'fa-chart-bar',      'color' => '#3b82f6'],
            ];
        @endphp

        <div style="display: flex; flex-direction: column; gap: 0.875rem;">
            @foreach($contentItems as $item)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: var(--bg-secondary); border-radius: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.4rem; background: {{ $item['color'] }}20; display: flex; align-items: center; justify-content: center; color: {{ $item['color'] }}; font-size: 0.875rem; flex-shrink: 0;">
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
<div class="grid dash-grid-3col dash-mb">

    @php
        $goalRate      = $totalGoalLogs > 0      ? round(($completedGoals / $totalGoalLogs) * 100) : 0;
        $activityRate  = $totalActivityLogs > 0  ? round(($completedActivityLogs / $totalActivityLogs) * 100) : 0;
        $focusRate     = $totalFocusSessions > 0 ? round(($completedFocusSessions / $totalFocusSessions) * 100) : 0;
        $inviteRate    = $totalInvitations > 0   ? round(($acceptedInvitations / $totalInvitations) * 100) : 0;
        $onboardRate   = $totalProfiles > 0      ? round(($onboardingCompleted / $totalProfiles) * 100) : 0;
        $challengeRate = $totalChallengeLogs > 0 ? round(($completedChallenges / $totalChallengeLogs) * 100) : 0;
    @endphp

    <div class="card dash-ring-card">
        <h3 class="dash-ring-title">
            <i class="fa-solid fa-bullseye" style="color: var(--primary);"></i> Goal Completion
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
        <p class="dash-ring-sub">{{ number_format($completedGoals) }} / {{ number_format($totalGoalLogs) }} goals</p>
    </div>

    <div class="card dash-ring-card">
        <h3 class="dash-ring-title">
            <i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Activity Completion
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
        <p class="dash-ring-sub">{{ number_format($completedActivityLogs) }} / {{ number_format($totalActivityLogs) }} activities</p>
    </div>

    <div class="card dash-ring-card">
        <h3 class="dash-ring-title">
            <i class="fa-solid fa-brain" style="color: #a78bfa;"></i> Focus Session Rate
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
        <p class="dash-ring-sub">{{ number_format($completedFocusSessions) }} / {{ number_format($totalFocusSessions) }} sessions</p>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════════
     ROW 6 — Extra Metrics Strip
══════════════════════════════════════════════════════════════════════ --}}
<div class="grid dash-grid-3col dash-mb">

    <div class="card dash-metric-strip">
        <div class="dash-metric-icon" style="background: #f59e0b20; color: #f59e0b;">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
        <div>
            <div class="dash-metric-value">{{ $inviteRate }}%</div>
            <div class="dash-metric-label">Invitation Accept Rate</div>
            <div class="dash-metric-sub">{{ number_format($acceptedInvitations) }} / {{ number_format($totalInvitations) }} invites</div>
        </div>
    </div>

    <div class="card dash-metric-strip">
        <div class="dash-metric-icon" style="background: #3b82f620; color: #3b82f6;">
            <i class="fa-solid fa-list-check"></i>
        </div>
        <div>
            <div class="dash-metric-value">{{ $onboardRate }}%</div>
            <div class="dash-metric-label">Onboarding Completion</div>
            <div class="dash-metric-sub">{{ number_format($onboardingCompleted) }} / {{ number_format($totalProfiles) }} profiles</div>
        </div>
    </div>

    <div class="card dash-metric-strip">
        <div class="dash-metric-icon" style="background: #ef444420; color: #ef4444;">
            <i class="fa-solid fa-trophy"></i>
        </div>
        <div>
            <div class="dash-metric-value">{{ $challengeRate }}%</div>
            <div class="dash-metric-label">Challenge Completion Rate</div>
            <div class="dash-metric-sub">{{ number_format($completedChallenges) }} / {{ number_format($totalChallengeLogs) }} challenges</div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════════
     ROW 7 — Recent Users Table
══════════════════════════════════════════════════════════════════════ --}}
<div class="card">
    <div class="dash-table-header">
        <div>
            <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--text-heading);">
                <i class="fa-solid fa-clock-rotate-left" style="margin-right: 0.5rem; color: var(--primary);"></i>Users Logged In (Last 7 Days)
            </h3>
            <p style="color: var(--text-muted); font-size: 0.8125rem; margin-top: 0.2rem;">Overview of users who logged into the application during the past 7 days.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="font-size: 0.8125rem; white-space: nowrap; flex-shrink: 0;">
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
     Dashboard-scoped CSS  (all class-based so media queries work)
══════════════════════════════════════════════════════════════════════ --}}
<style>
/* ── spacing helper ────────────────────────────────────────────────── */
.dash-mb { margin-bottom: 1.5rem; }

/* ── Row-4 two-column panel grid ──────────────────────────────────── */
.dash-grid-2col {
    grid-template-columns: 1fr 1fr;
}

/* ── Row-5 & Row-6 three-column grid ─────────────────────────────── */
.dash-grid-3col {
    grid-template-columns: repeat(3, 1fr);
}

/* ── Ring cards ───────────────────────────────────────────────────── */
.dash-ring-card {
    text-align: center;
}
.dash-ring-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-heading);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
}
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
.dash-ring-sub {
    color: var(--text-muted);
    font-size: 0.8rem;
    margin-top: 0.75rem;
}

/* ── Metric-strip cards (Row 6) ───────────────────────────────────── */
.dash-metric-strip {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.dash-metric-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.dash-metric-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text-heading);
}
.dash-metric-label {
    font-size: 0.8rem;
    color: var(--text-muted);
}
.dash-metric-sub {
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* ── Table card header (flex row) ─────────────────────────────────── */
.dash-table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
}

/* ══════════════════════════════════════════════════════════════════
   RESPONSIVE OVERRIDES
══════════════════════════════════════════════════════════════════ */

/* Tablet  ≤ 1024px */
@media (max-width: 1024px) {
    .dash-grid-2col {
        grid-template-columns: 1fr;
    }
    .dash-grid-3col {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Mobile  ≤ 768px */
@media (max-width: 768px) {
    .dash-mb {
        margin-bottom: 1rem;
    }

    /* All dashboard grids collapse to single column */
    .dash-grid-2col,
    .dash-grid-3col {
        grid-template-columns: 1fr !important;
    }

    /* Table header wraps button below title */
    .dash-table-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .dash-table-header .btn {
        width: 100%;
        justify-content: center;
    }

    /* Smaller ring on phones */
    .dash-ring-wrap {
        width: 6rem;
        height: 6rem;
    }

    .dash-ring-label {
        font-size: 1.15rem;
    }

    /* Metric strip stacks icon above text on very small cards */
    .dash-metric-value {
        font-size: 1.25rem;
    }
}

/* Small phones  ≤ 480px */
@media (max-width: 480px) {
    .dash-ring-wrap {
        width: 5.5rem;
        height: 5.5rem;
    }

    .dash-ring-label {
        font-size: 1rem;
    }

    .dash-ring-title {
        font-size: 0.9rem;
    }

    .dash-metric-strip {
        gap: 0.75rem;
    }

    .dash-metric-icon {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1rem;
    }
}
</style>
@endsection