@extends('layouts.admin')

@section('title', $user->name . ' - Analytics Details')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li><a href="{{ route('admin.users.index') }}">Users</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">{{ $user->name }}'s Analytics</li>
</ul>

<!-- User Summary Header -->
<div class="card" style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 700;">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading);">{{ $user->name }}</h2>
            <div style="color: var(--text-muted); font-size: 0.85rem;">{{ $user->email }} | User ID: {{ $user->id }}</div>
        </div>
    </div>
    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-user"></i> View Profile
    </a>
</div>

<!-- Tabs Navigation -->
<div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
    <a href="?tab=app-usage" class="btn {{ $activeTab === 'app-usage' ? 'btn-primary' : 'btn-secondary' }}">App Usage</a>
    <a href="?tab=app-block" class="btn {{ $activeTab === 'app-block' ? 'btn-primary' : 'btn-secondary' }}">App Block</a>
    <a href="?tab=focus-session" class="btn {{ $activeTab === 'focus-session' ? 'btn-primary' : 'btn-secondary' }}">Focus Session</a>
    <a href="?tab=activity" class="btn {{ $activeTab === 'activity' ? 'btn-primary' : 'btn-secondary' }}">Activity</a>
    <a href="?tab=goal" class="btn {{ $activeTab === 'goal' ? 'btn-primary' : 'btn-secondary' }}">Goal</a>
    <a href="?tab=emotion" class="btn {{ $activeTab === 'emotion' ? 'btn-primary' : 'btn-secondary' }}">Emotion</a>
    <a href="?tab=streak" class="btn {{ $activeTab === 'streak' ? 'btn-primary' : 'btn-secondary' }}">Streak</a>
</div>

<!-- KPIs Row -->
<div class="grid grid-cols-3" style="margin-bottom: 1.5rem;">
    @foreach($kpis as $kpi)
        <div class="card stat-card">
            <div>
                <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">{{ $kpi['label'] }}</span>
                <div class="stat-value">{{ $kpi['value'] }}</div>
            </div>
            <div class="stat-icon primary">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
        </div>
    @endforeach
</div>

<!-- Log Records Table -->
<div class="card">
    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-heading); margin-bottom: 1.25rem;">Log History</h3>
    <div class="table-wrapper">
        @if($logs->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        @if($activeTab === 'app-usage')
                            <th>App Name</th>
                            <th>Package</th>
                            <th>Started At</th>
                            <th>Ended At</th>
                            <th>Duration</th>
                            <th>Opens</th>
                        @elseif($activeTab === 'app-block')
                            <th>App Name</th>
                            <th>Package</th>
                            <th>Blocked At</th>
                            <th>Status</th>
                            <th>Time Saved</th>
                        @elseif($activeTab === 'focus-session')
                            <th>Started At</th>
                            <th>Ended At</th>
                            <th>Duration</th>
                            <th>Status</th>
                        @elseif($activeTab === 'activity')
                            <th>Activity Title</th>
                            <th>Duration</th>
                            <th>Completed At</th>
                            <th>Status</th>
                        @elseif($activeTab === 'goal')
                            <th>Target</th>
                            <th>Achieved</th>
                            <th>Date</th>
                            <th>Status</th>
                        @elseif($activeTab === 'emotion')
                            <th>Emotion</th>
                            <th>Source App</th>
                            <th>Logged At</th>
                        @elseif($activeTab === 'streak')
                            <th>Current Streak</th>
                            <th>Longest Streak</th>
                            <th>Last Active</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            @if($activeTab === 'app-usage')
                                <td><strong>{{ $log->app_name }}</strong></td>
                                <td><span style="font-family: monospace; font-size: 0.8rem; color: var(--text-muted);">{{ $log->package_name }}</span></td>
                                <td>{{ $log->started_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                <td>{{ $log->ended_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                <td>{{ $log->duration_minutes }} mins</td>
                                <td>{{ $log->opened_count }}</td>
                            @elseif($activeTab === 'app-block')
                                <td><strong>{{ $log->app_name }}</strong></td>
                                <td><span style="font-family: monospace; font-size: 0.8rem; color: var(--text-muted);">{{ $log->package_name }}</span></td>
                                <td>{{ $log->blocked_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                <td><span class="badge {{ $log->success ? 'badge-success' : 'badge-danger' }}">{{ $log->success ? 'Blocked' : 'Attempted' }}</span></td>
                                <td>{{ $log->time_saved_minutes }} mins</td>
                            @elseif($activeTab === 'focus-session')
                                <td>{{ $log->started_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                <td>{{ $log->ended_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                <td>{{ $log->duration_minutes }} mins</td>
                                <td><span class="badge {{ $log->completed ? 'badge-success' : 'badge-danger' }}">{{ $log->completed ? 'Completed' : 'Interrupted' }}</span></td>
                            @elseif($activeTab === 'activity')
                                <td><strong>{{ $log->activity?->activity_title ?? 'N/A' }}</strong></td>
                                <td>{{ $log->duration_minutes }} mins</td>
                                <td>{{ $log->completed_at ? \Carbon\Carbon::parse($log->completed_at)->format('M d, H:i') : 'N/A' }}</td>
                                <td><span class="badge {{ $log->completed ? 'badge-success' : 'badge-warning' }}">{{ $log->completed ? 'Completed' : 'Logged' }}</span></td>
                            @elseif($activeTab === 'goal')
                                <td>{{ $log->target_minutes }} mins</td>
                                <td>{{ $log->achieved_minutes }} mins</td>
                                <td>{{ $log->goal_date }}</td>
                                <td><span class="badge {{ $log->completed ? 'badge-success' : 'badge-danger' }}">{{ $log->completed ? 'Achieved' : 'Incomplete' }}</span></td>
                            @elseif($activeTab === 'emotion')
                                <td><strong>{{ $log->emotion }}</strong></td>
                                <td>{{ $log->app_name ?? 'N/A' }}</td>
                                <td>{{ $log->logged_at?->format('M d, H:i') ?? 'N/A' }}</td>
                            @elseif($activeTab === 'streak')
                                <td>{{ $log->current_streak }} days</td>
                                <td>{{ $log->longest_streak }} days</td>
                                <td>{{ $log->last_completed_date }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top: 1.25rem;">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 2rem; color: var(--text-muted);">No records found for this category.</div>
        @endif
    </div>
</div>
@endsection