@extends('layouts.admin')

@section('title', 'App Analytics - Pivot Admin Dashboard')

@section('content')
@php
    $activeTab = request()->get('tab', 'app-usage');

    // Query log items & compute KPIs
    switch ($activeTab) {
        case 'app-usage':
            $logs = \App\Models\AppUsageLogs::with('user')->orderBy('created_at', 'desc')->paginate(10);
            $kpis = [
                ['label' => 'Total Time Logged', 'value' => round(\App\Models\AppUsageLogs::sum('duration_minutes')) . ' mins'],
                ['label' => 'Total Opens', 'value' => \App\Models\AppUsageLogs::sum('opened_count')],
                ['label' => 'Top App By Usage', 'value' => \App\Models\AppUsageLogs::select('app_name')->groupBy('app_name')->orderByRaw('SUM(duration_minutes) DESC')->first()?->app_name ?? 'N/A'],
            ];
            $chartRaw = \App\Models\AppUsageLogs::selectRaw('app_name, SUM(duration_minutes) as sum_duration')->groupBy('app_name')->orderByDesc('sum_duration')->limit(5)->get();
            $chartLabels = $chartRaw->pluck('app_name')->toArray();
            $chartData = $chartRaw->pluck('sum_duration')->toArray();
            $chartTitle = 'Top 5 Apps by Usage Duration (Minutes)';
            $chartType = 'bar';
            break;

        case 'app-block':
            $logs = \App\Models\AppBlockLog::with('user')->orderBy('created_at', 'desc')->paginate(10);
            $kpis = [
                ['label' => 'Total Block Events', 'value' => \App\Models\AppBlockLog::count()],
                ['label' => 'Success Rate', 'value' => (\App\Models\AppBlockLog::count() > 0 ? round((\App\Models\AppBlockLog::where('success', true)->count() / \App\Models\AppBlockLog::count()) * 100, 1) : 0) . '%'],
                ['label' => 'Est. Time Saved', 'value' => \App\Models\AppBlockLog::sum('time_saved_minutes') . ' mins'],
            ];
            $chartLabels = ['Successful Blocks', 'Failed Attempts'];
            $chartData = [
                \App\Models\AppBlockLog::where('success', true)->count(),
                \App\Models\AppBlockLog::where('success', false)->count()
            ];
            $chartTitle = 'App Blocking Success Rate';
            $chartType = 'doughnut';
            break;

        case 'focus-session':
            $logs = \App\Models\FocusSessionLogs::with('user')->orderBy('created_at', 'desc')->paginate(10);
            $kpis = [
                ['label' => 'Total Focus Sessions', 'value' => \App\Models\FocusSessionLogs::count()],
                ['label' => 'Completed Sessions', 'value' => \App\Models\FocusSessionLogs::where('completed', true)->count()],
                ['label' => 'Total Focused Time', 'value' => \App\Models\FocusSessionLogs::sum('duration_minutes') . ' mins'],
            ];
            $chartRaw = \App\Models\FocusSessionLogs::selectRaw('DATE(created_at) as date, SUM(duration_minutes) as minutes')->groupBy('date')->orderBy('date', 'asc')->limit(7)->get();
            $chartLabels = $chartRaw->pluck('date')->toArray();
            $chartData = $chartRaw->pluck('minutes')->toArray();
            $chartTitle = 'Daily Focus Time (Minutes)';
            $chartType = 'line';
            break;

        case 'activity':
            $logs = \App\Models\ActivityLogs::with(['user', 'activity'])->orderBy('created_at', 'desc')->paginate(10);
            $kpis = [
                ['label' => 'Total Logs', 'value' => \App\Models\ActivityLogs::count()],
                ['label' => 'Completion Rate', 'value' => (\App\Models\ActivityLogs::count() > 0 ? round((\App\Models\ActivityLogs::where('completed', true)->count() / \App\Models\ActivityLogs::count()) * 100, 1) : 0) . '%'],
                ['label' => 'Total Activity Minutes', 'value' => \App\Models\ActivityLogs::sum('duration_minutes') . ' mins'],
            ];
            $chartLabels = ['Completed', 'Pending/Incomplete'];
            $chartData = [
                \App\Models\ActivityLogs::where('completed', true)->count(),
                \App\Models\ActivityLogs::where('completed', false)->count()
            ];
            $chartTitle = 'Activity Completion Statistics';
            $chartType = 'doughnut';
            break;

        case 'goal':
            $logs = \App\Models\GoalLogs::with('user')->orderBy('created_at', 'desc')->paginate(10);
            $kpis = [
                ['label' => 'Goals Logged', 'value' => \App\Models\GoalLogs::count()],
                ['label' => 'Achieved Rate', 'value' => (\App\Models\GoalLogs::count() > 0 ? round((\App\Models\GoalLogs::where('completed', true)->count() / \App\Models\GoalLogs::count()) * 100, 1) : 0) . '%'],
                ['label' => 'Avg Achieved', 'value' => round(\App\Models\GoalLogs::avg('achieved_minutes'), 1) . ' mins'],
            ];
            $chartRaw = \App\Models\GoalLogs::orderBy('created_at', 'desc')->limit(7)->get()->reverse();
            $chartLabels = $chartRaw->map(fn($gl) => 'Goal #' . $gl->id)->toArray();
            $chartData = [
                'target' => $chartRaw->pluck('target_minutes')->toArray(),
                'achieved' => $chartRaw->pluck('achieved_minutes')->toArray(),
            ];
            $chartTitle = 'Target vs Achieved Goal Durations';
            $chartType = 'bar-double';
            break;

        case 'emotion':
            $logs = \App\Models\EmotionLogs::with('user')->orderBy('created_at', 'desc')->paginate(10);
            $kpis = [
                ['label' => 'Total Logged States', 'value' => \App\Models\EmotionLogs::count()],
                ['label' => 'Most Frequent State', 'value' => \App\Models\EmotionLogs::select('emotion')->groupBy('emotion')->orderByRaw('COUNT(*) DESC')->first()?->emotion ?? 'N/A'],
                ['label' => 'Logs Today', 'value' => \App\Models\EmotionLogs::whereDate('logged_at', today())->count()],
            ];
            $chartRaw = \App\Models\EmotionLogs::selectRaw('emotion, COUNT(*) as count')->groupBy('emotion')->orderByDesc('count')->get();
            $chartLabels = $chartRaw->pluck('emotion')->toArray();
            $chartData = $chartRaw->pluck('count')->toArray();
            $chartTitle = 'Emotion Distribution';
            $chartType = 'doughnut';
            break;

        case 'streak':
            $logs = \App\Models\StreakLogs::with('user')->orderBy('created_at', 'desc')->paginate(10);
            $kpis = [
                ['label' => 'Streaks Logged', 'value' => \App\Models\StreakLogs::count()],
                ['label' => 'Avg Current Streak', 'value' => round(\App\Models\StreakLogs::avg('current_streak'), 1) . ' days'],
                ['label' => 'Max Streak Recorded', 'value' => (\App\Models\StreakLogs::max('longest_streak') ?? 0) . ' days'],
            ];
            $chartRaw = \App\Models\StreakLogs::with('user')->orderByDesc('longest_streak')->limit(5)->get();
            $chartLabels = $chartRaw->map(fn($st) => $st->user?->name ?? 'User ID: '.$st->user_id)->toArray();
            $chartData = $chartRaw->pluck('longest_streak')->toArray();
            $chartTitle = 'Top 5 User Longest Streaks (Days)';
            $chartType = 'bar';
            break;
    }
@endphp

<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">App Analytics</li>
</ul>

<!-- Filter Tabs Navigation -->
<div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
    <a href="?tab=app-usage" class="btn {{ $activeTab === 'app-usage' ? 'btn-primary' : 'btn-secondary' }}">
        <i class="fa-solid fa-chart-simple"></i> App Usage
    </a>
    <a href="?tab=app-block" class="btn {{ $activeTab === 'app-block' ? 'btn-primary' : 'btn-secondary' }}">
        <i class="fa-solid fa-ban"></i> App Block
    </a>
    <a href="?tab=focus-session" class="btn {{ $activeTab === 'focus-session' ? 'btn-primary' : 'btn-secondary' }}">
        <i class="fa-solid fa-clock"></i> Focus Session
    </a>
    <a href="?tab=activity" class="btn {{ $activeTab === 'activity' ? 'btn-primary' : 'btn-secondary' }}">
        <i class="fa-solid fa-list-check"></i> Activity
    </a>
    <a href="?tab=goal" class="btn {{ $activeTab === 'goal' ? 'btn-primary' : 'btn-secondary' }}">
        <i class="fa-solid fa-bullseye"></i> Goal
    </a>
    <a href="?tab=emotion" class="btn {{ $activeTab === 'emotion' ? 'btn-primary' : 'btn-secondary' }}">
        <i class="fa-solid fa-face-smile"></i> Emotion
    </a>
    <a href="?tab=streak" class="btn {{ $activeTab === 'streak' ? 'btn-primary' : 'btn-secondary' }}">
        <i class="fa-solid fa-fire"></i> Streak
    </a>
</div>

<!-- KPI Summary Cards -->
<div class="grid grid-cols-3" style="margin-bottom: 1.5rem;">
    @foreach($kpis as $kpi)
        <div class="card stat-card">
            <div>
                <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">{{ $kpi['label'] }}</span>
                <div class="stat-value">{{ $kpi['value'] }}</div>
            </div>
            <div class="stat-icon primary">
                <i class="fa-solid fa-chart-line"></i>
            </div>
        </div>
    @endforeach
</div>

<!-- Chart Card -->
<div class="card" style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-heading); margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-light); padding-bottom: 0.5rem;">
        {{ $chartTitle }}
    </h3>
    <div style="position: relative; width: 100%; height: 280px;">
        @if(!empty($chartLabels))
            <canvas id="analyticsChart"></canvas>
        @else
            <div style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
                No analytics data available to render chart
            </div>
        @endif
    </div>
</div>

<!-- Logs Data Table -->
<div class="card">
    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-heading); margin-bottom: 1.25rem;">
        Data Records
    </h3>
    <div class="table-wrapper">
        @if($logs->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        @if($activeTab === 'app-usage')
                            <th>User</th>
                            <th>App Name</th>
                            <th>Package Name</th>
                            <th>Started At</th>
                            <th>Ended At</th>
                            <th>Duration</th>
                            <th>Opens</th>
                        @elseif($activeTab === 'app-block')
                            <th>User</th>
                            <th>App Name</th>
                            <th>Package Name</th>
                            <th>Event Type</th>
                            <th>Blocked At</th>
                            <th>Released At</th>
                            <th>Success</th>
                            <th>Time Saved</th>
                        @elseif($activeTab === 'focus-session')
                            <th>User</th>
                            <th>Started At</th>
                            <th>Ended At</th>
                            <th>Duration</th>
                            <th>Status</th>
                        @elseif($activeTab === 'activity')
                            <th>User</th>
                            <th>Activity</th>
                            <th>Duration</th>
                            <th>Completed At</th>
                            <th>Status</th>
                        @elseif($activeTab === 'goal')
                            <th>User</th>
                            <th>Target</th>
                            <th>Achieved</th>
                            <th>Date</th>
                            <th>Status</th>
                        @elseif($activeTab === 'emotion')
                            <th>User</th>
                            <th>Emotion</th>
                            <th>Source App</th>
                            <th>Logged At</th>
                        @elseif($activeTab === 'streak')
                            <th>User</th>
                            <th>Current Streak</th>
                            <th>Longest Streak</th>
                            <th>Last Active Date</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            @if($activeTab === 'app-usage')
                                <td><strong style="color: var(--text-heading);">{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</strong></td>
                                <td><strong>{{ $log->app_name }}</strong></td>
                                <td><span style="font-family: monospace; font-size: 0.8rem; color: var(--text-muted);">{{ $log->package_name }}</span></td>
                                <td>{{ $log->started_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                <td>{{ $log->ended_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                <td>{{ $log->duration_minutes }} mins</td>
                                <td>{{ $log->opened_count }}</td>
                            @elseif($activeTab === 'app-block')
                                <td><strong style="color: var(--text-heading);">{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</strong></td>
                                <td><strong>{{ $log->app_name }}</strong></td>
                                <td><span style="font-family: monospace; font-size: 0.8rem; color: var(--text-muted);">{{ $log->package_name }}</span></td>
                                <td><span class="badge badge-info">{{ $log->event_type ?? 'block' }}</span></td>
                                <td>{{ $log->blocked_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                <td>{{ $log->released_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $log->success ? 'badge-success' : 'badge-danger' }}">
                                        {{ $log->success ? 'Success' : 'Attempted' }}
                                    </span>
                                </td>
                                <td>{{ $log->time_saved_minutes }} mins</td>
                            @elseif($activeTab === 'focus-session')
                                <td><strong style="color: var(--text-heading);">{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</strong></td>
                                <td>{{ $log->started_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                <td>{{ $log->ended_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                <td>{{ $log->duration_minutes }} mins</td>
                                <td>
                                    <span class="badge {{ $log->completed ? 'badge-success' : 'badge-danger' }}">
                                        {{ $log->completed ? 'Completed' : 'Interrupted' }}
                                    </span>
                                </td>
                            @elseif($activeTab === 'activity')
                                <td><strong style="color: var(--text-heading);">{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</strong></td>
                                <td><strong>{{ $log->activity?->activity_title ?? 'Activity ID: '.$log->activity_id }}</strong></td>
                                <td>{{ $log->duration_minutes }} mins</td>
                                <td>{{ $log->completed_at ? \Carbon\Carbon::parse($log->completed_at)->format('M d, H:i') : 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $log->completed ? 'badge-success' : 'badge-warning' }}">
                                        {{ $log->completed ? 'Completed' : 'Logged' }}
                                    </span>
                                </td>
                            @elseif($activeTab === 'goal')
                                <td><strong style="color: var(--text-heading);">{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</strong></td>
                                <td>{{ $log->target_minutes }} mins</td>
                                <td>{{ $log->achieved_minutes }} mins</td>
                                <td>{{ $log->goal_date }}</td>
                                <td>
                                    <span class="badge {{ $log->completed ? 'badge-success' : 'badge-danger' }}">
                                        {{ $log->completed ? 'Achieved' : 'Incomplete' }}
                                    </span>
                                </td>
                            @elseif($activeTab === 'emotion')
                                <td><strong style="color: var(--text-heading);">{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</strong></td>
                                <td><strong>{{ $log->emotion }}</strong></td>
                                <td>{{ $log->app_name ?? 'N/A' }}</td>
                                <td>{{ $log->logged_at?->format('M d, H:i') ?? 'N/A' }}</td>
                            @elseif($activeTab === 'streak')
                                <td><strong style="color: var(--text-heading);">{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</strong></td>
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
            <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
                No logs found for this filter category.
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if(!empty($chartLabels))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('analyticsChart').getContext('2d');
        const type = '{{ $chartType }}';
        const labels = @json($chartLabels);
        const dataValues = @json($chartData);

        let chartConfig;

        if (type === 'bar-double') {
            chartConfig = {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Target Minutes',
                            data: dataValues.target,
                            backgroundColor: '#3B5838',
                            borderRadius: 6
                        },
                        {
                            label: 'Achieved Minutes',
                            data: dataValues.achieved,
                            backgroundColor: '#808000',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            };
        } else if (type === 'doughnut') {
            chartConfig = {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataValues,
                        backgroundColor: ['#2E7D32', '#D32F2F', '#3B5838', '#808000', '#ED6C02', '#0288D1']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            };
        } else if (type === 'line') {
            chartConfig = {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Focused Minutes',
                        data: dataValues,
                        borderColor: '#3B5838',
                        backgroundColor: 'rgba(59, 88, 56, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            };
        } else {
            chartConfig = {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Metric Value',
                        data: dataValues,
                        backgroundColor: '#3B5838',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            };
        }

        new Chart(ctx, chartConfig);
    });
</script>
@endif
@endpush