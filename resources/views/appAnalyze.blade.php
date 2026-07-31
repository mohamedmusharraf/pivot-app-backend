<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Usage & Behavior Analytics</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-color: #0b0f19;
            --surface-color: rgba(20, 26, 45, 0.7);
            --surface-border: rgba(255, 255, 255, 0.08);
            --text-main: #f1f5f9;
            --text-muted: #64748b;
            --primary: #3b82f6;
            --primary-glow: rgba(59, 130, 246, 0.15);
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --glass-blur: blur(16px);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.08) 0px, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            padding: 2rem;
            line-height: 1.5;
            background-attachment: fixed;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--surface-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 2.25rem;
            font-weight: 800;
            background: linear-gradient(to right, #60a5fa, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.03em;
        }

        .header p {
            color: var(--text-muted);
            margin-top: 0.25rem;
            font-size: 0.95rem;
        }

        /* Dashboard Layout */
        .layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 2rem;
        }

        @media (max-width: 1024px) {
            .layout {
                grid-template-columns: 1fr;
            }
        }

        /* Sidebar Navigation */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .tab-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.25rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--surface-border);
            border-radius: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .tab-btn:hover {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .tab-btn.active {
            color: var(--text-main);
            background: var(--primary-glow);
            border-color: var(--primary);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.1);
        }

        .tab-btn svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Main Content Panel */
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* KPIs Row */
        .kpi-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
        }

        .kpi-card {
            background: var(--surface-color);
            border: 1px solid var(--surface-border);
            border-radius: 1rem;
            padding: 1.25rem;
            backdrop-filter: var(--glass-blur);
            transition: all 0.3s ease;
        }

        .kpi-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .kpi-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .kpi-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-main);
        }

        /* Chart & Details Row */
        .chart-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .chart-card {
            background: var(--surface-color);
            border: 1px solid var(--surface-border);
            border-radius: 1rem;
            padding: 1.5rem;
            backdrop-filter: var(--glass-blur);
        }

        .chart-card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            color: var(--text-main);
            border-bottom: 1px solid var(--surface-border);
            padding-bottom: 0.5rem;
        }

        .chart-wrapper {
            position: relative;
            width: 100%;
            height: 260px;
        }

        /* Table Card */
        .table-card {
            background: var(--surface-color);
            border: 1px solid var(--surface-border);
            border-radius: 1rem;
            padding: 1.5rem;
            backdrop-filter: var(--glass-blur);
            overflow: hidden;
        }

        .table-card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            color: var(--text-main);
        }

        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.9rem;
        }

        th {
            padding: 1rem;
            color: var(--text-muted);
            font-weight: 600;
            border-bottom: 2px solid var(--surface-border);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--surface-border);
            color: var(--text-main);
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.01);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .badge-primary {
            background: rgba(59, 130, 246, 0.15);
            color: var(--primary);
            border: 1px solid rgba(59, 130, 246, 0.25);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        /* Pagination styles */
        .pagination {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--surface-border);
            color: var(--text-main);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .pagination a:hover, .pagination .active {
            background: var(--primary);
            border-color: var(--primary);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    @php
        $activeTab = request()->get('tab', 'app-usage');

        // Query log items & compute KPIs
        switch ($activeTab) {
            case 'app-usage':
                $logs = \App\Models\AppUsageLogs::with('user')->orderBy('created_at', 'desc')->paginate(15);
                $kpis = [
                    ['label' => 'Total Time Logged', 'value' => round(\App\Models\AppUsageLogs::sum('duration_minutes')) . ' mins'],
                    ['label' => 'Total Opens', 'value' => \App\Models\AppUsageLogs::sum('opened_count')],
                    ['label' => 'Top App By Usage', 'value' => \App\Models\AppUsageLogs::select('app_name')->groupBy('app_name')->orderByRaw('SUM(duration_minutes) DESC')->first()?->app_name ?? 'N/A'],
                ];
                // Chart: top 5 apps usage
                $chartRaw = \App\Models\AppUsageLogs::selectRaw('app_name, SUM(duration_minutes) as sum_duration')->groupBy('app_name')->orderByDesc('sum_duration')->limit(5)->get();
                $chartLabels = $chartRaw->pluck('app_name')->toArray();
                $chartData = $chartRaw->pluck('sum_duration')->toArray();
                $chartTitle = 'Top 5 Apps by Usage Duration (Minutes)';
                $chartType = 'bar';
                break;

            case 'app-block':
                $logs = \App\Models\AppBlockLog::with('user')->orderBy('created_at', 'desc')->paginate(15);
                $kpis = [
                    ['label' => 'Total Block Events', 'value' => \App\Models\AppBlockLog::count()],
                    ['label' => 'Success Rate', 'value' => (\App\Models\AppBlockLog::count() > 0 ? round((\App\Models\AppBlockLog::where('success', true)->count() / \App\Models\AppBlockLog::count()) * 100, 1) : 0) . '%'],
                    ['label' => 'Est. Time Saved', 'value' => \App\Models\AppBlockLog::sum('time_saved_minutes') . ' mins'],
                ];
                // Chart: Success vs Failed blocks
                $chartLabels = ['Successful Blocks', 'Failed Attempts'];
                $chartData = [
                    \App\Models\AppBlockLog::where('success', true)->count(),
                    \App\Models\AppBlockLog::where('success', false)->count()
                ];
                $chartTitle = 'App Blocking Success Rate';
                $chartType = 'doughnut';
                break;

            case 'focus-session':
                $logs = \App\Models\FocusSessionLogs::with('user')->orderBy('created_at', 'desc')->paginate(15);
                $kpis = [
                    ['label' => 'Total Focus Sessions', 'value' => \App\Models\FocusSessionLogs::count()],
                    ['label' => 'Completed Sessions', 'value' => \App\Models\FocusSessionLogs::where('completed', true)->count()],
                    ['label' => 'Total Focused Time', 'value' => \App\Models\FocusSessionLogs::sum('duration_minutes') . ' mins'],
                ];
                // Chart: focus minutes over time (last 7 days)
                $chartRaw = \App\Models\FocusSessionLogs::selectRaw('DATE(created_at) as date, SUM(duration_minutes) as minutes')->groupBy('date')->orderBy('date', 'asc')->limit(7)->get();
                $chartLabels = $chartRaw->pluck('date')->toArray();
                $chartData = $chartRaw->pluck('minutes')->toArray();
                $chartTitle = 'Daily Focus Time (Minutes)';
                $chartType = 'line';
                break;

            case 'activity':
                $logs = \App\Models\ActivityLogs::with(['user', 'activity'])->orderBy('created_at', 'desc')->paginate(15);
                $kpis = [
                    ['label' => 'Total Logs', 'value' => \App\Models\ActivityLogs::count()],
                    ['label' => 'Completion Rate', 'value' => (\App\Models\ActivityLogs::count() > 0 ? round((\App\Models\ActivityLogs::where('completed', true)->count() / \App\Models\ActivityLogs::count()) * 100, 1) : 0) . '%'],
                    ['label' => 'Total Activity Minutes', 'value' => \App\Models\ActivityLogs::sum('duration_minutes') . ' mins'],
                ];
                // Chart: completed vs incomplete
                $chartLabels = ['Completed', 'Pending/Incomplete'];
                $chartData = [
                    \App\Models\ActivityLogs::where('completed', true)->count(),
                    \App\Models\ActivityLogs::where('completed', false)->count()
                ];
                $chartTitle = 'Activity Completion Statistics';
                $chartType = 'doughnut';
                break;

            case 'goal':
                $logs = \App\Models\GoalLogs::with('user')->orderBy('created_at', 'desc')->paginate(15);
                $kpis = [
                    ['label' => 'Goals Logged', 'value' => \App\Models\GoalLogs::count()],
                    ['label' => 'Achieved Rate', 'value' => (\App\Models\GoalLogs::count() > 0 ? round((\App\Models\GoalLogs::where('completed', true)->count() / \App\Models\GoalLogs::count()) * 100, 1) : 0) . '%'],
                    ['label' => 'Avg Achieved', 'value' => round(\App\Models\GoalLogs::avg('achieved_minutes'), 1) . ' mins'],
                ];
                // Chart: target vs achieved (last 7 logs)
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
                $logs = \App\Models\EmotionLogs::with('user')->orderBy('created_at', 'desc')->paginate(15);
                $kpis = [
                    ['label' => 'Total Logged States', 'value' => \App\Models\EmotionLogs::count()],
                    ['label' => 'Most Frequent State', 'value' => \App\Models\EmotionLogs::select('emotion')->groupBy('emotion')->orderByRaw('COUNT(*) DESC')->first()?->emotion ?? 'N/A'],
                    ['label' => 'Logs Today', 'value' => \App\Models\EmotionLogs::whereDate('logged_at', today())->count()],
                ];
                // Chart: Emotion distribution
                $chartRaw = \App\Models\EmotionLogs::selectRaw('emotion, COUNT(*) as count')->groupBy('emotion')->orderByDesc('count')->get();
                $chartLabels = $chartRaw->pluck('emotion')->toArray();
                $chartData = $chartRaw->pluck('count')->toArray();
                $chartTitle = 'Emotion Distribution';
                $chartType = 'doughnut';
                break;

            case 'streak':
                $logs = \App\Models\StreakLogs::with('user')->orderBy('created_at', 'desc')->paginate(15);
                $kpis = [
                    ['label' => 'Streaks Logged', 'value' => \App\Models\StreakLogs::count()],
                    ['label' => 'Avg Current Streak', 'value' => round(\App\Models\StreakLogs::avg('current_streak'), 1) . ' days'],
                    ['label' => 'Max Streak Recorded', 'value' => (\App\Models\StreakLogs::max('longest_streak') ?? 0) . ' days'],
                ];
                // Chart: Top 5 Streaks
                $chartRaw = \App\Models\StreakLogs::with('user')->orderByDesc('longest_streak')->limit(5)->get();
                $chartLabels = $chartRaw->map(fn($st) => $st->user?->name ?? 'User ID: '.$st->user_id)->toArray();
                $chartData = $chartRaw->pluck('longest_streak')->toArray();
                $chartTitle = 'Top 5 User Longest Streaks (Days)';
                $chartType = 'bar';
                break;
        }
    @endphp

    <div class="container">
        <div class="header">
            <div>
                <h1>App Analytics Dashboard</h1>
                <p>Monitor user behavior, focus performance, usage logs, and habit streaks</p>
            </div>
        </div>

        <div class="layout">
            <!-- Sidebar Navigation -->
            <div class="sidebar">
                <a href="?tab=app-usage" class="tab-btn {{ $activeTab === 'app-usage' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    App Usage Log
                </a>
                <a href="?tab=app-block" class="tab-btn {{ $activeTab === 'app-block' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    App Block Log
                </a>
                <a href="?tab=focus-session" class="tab-btn {{ $activeTab === 'focus-session' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Focus Session Log
                </a>
                <a href="?tab=activity" class="tab-btn {{ $activeTab === 'activity' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Activity Log
                </a>
                <a href="?tab=goal" class="tab-btn {{ $activeTab === 'goal' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Goal Log
                </a>
                <a href="?tab=emotion" class="tab-btn {{ $activeTab === 'emotion' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Emotion Log
                </a>
                <a href="?tab=streak" class="tab-btn {{ $activeTab === 'streak' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                    Streak Log
                </a>
            </div>

            <!-- Content Area -->
            <div class="main-content">
                <!-- KPI widgets -->
                <div class="kpi-row">
                    @foreach($kpis as $kpi)
                        <div class="kpi-card">
                            <div class="kpi-label">{{ $kpi['label'] }}</div>
                            <div class="kpi-value">{{ $kpi['value'] }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Chart container -->
                <div class="chart-row">
                    <div class="chart-card">
                        <h3>{{ $chartTitle }}</h3>
                        <div class="chart-wrapper">
                            @if(!empty($chartLabels))
                                <canvas id="analyticsChart"></canvas>
                            @else
                                <div class="empty-state">No enough analytics data to render chart</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-card">
                    <h3>Data Records</h3>
                    <div class="table-responsive">
                        @if($logs->count() > 0)
                            <table>
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
                                            <th>User ID</th>
                                            <th>Started At</th>
                                            <th>Ended At</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                        @elseif($activeTab === 'activity')
                                            <th>User ID</th>
                                            <th>Activity</th>
                                            <th>Duration</th>
                                            <th>Completed At</th>
                                            <th>Status</th>
                                        @elseif($activeTab === 'goal')
                                            <th>User ID</th>
                                            <th>Goal ID</th>
                                            <th>Target</th>
                                            <th>Achieved</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        @elseif($activeTab === 'emotion')
                                            <th>User ID</th>
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
                                                <td>{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</td>
                                                <td><strong>{{ $log->app_name }}</strong></td>
                                                <td><span style="font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: var(--text-muted);">{{ $log->package_name }}</span></td>
                                                <td>{{ $log->started_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                                <td>{{ $log->ended_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                                <td>{{ $log->duration_minutes }} mins</td>
                                                <td>{{ $log->opened_count }}</td>
                                            @elseif($activeTab === 'app-block')
                                                <td>{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</td>
                                                <td><strong>{{ $log->app_name }}</strong></td>
                                                <td><span style="font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: var(--text-muted);">{{ $log->package_name }}</span></td>
                                                <td><span class="badge badge-primary">{{ $log->event_type ?? 'block' }}</span></td>
                                                <td>{{ $log->blocked_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                                <td>{{ $log->released_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge {{ $log->success ? 'badge-success' : 'badge-danger' }}">
                                                        {{ $log->success ? 'Success' : 'Attempted' }}
                                                    </span>
                                                </td>
                                                <td>{{ $log->time_saved_minutes }} mins</td>
                                            @elseif($activeTab === 'focus-session')
                                                <td>{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</td>
                                                <td>{{ $log->started_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                                <td>{{ $log->ended_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                                <td>{{ $log->duration_minutes }} mins</td>
                                                <td>
                                                    <span class="badge {{ $log->completed ? 'badge-success' : 'badge-danger' }}">
                                                        {{ $log->completed ? 'Completed' : 'Interrupted' }}
                                                    </span>
                                                </td>
                                            @elseif($activeTab === 'activity')
                                                <td>{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</td>
                                                <td><strong>{{ $log->activity?->activity_title ?? 'Activity ID: '.$log->activity_id }}</strong></td>
                                                <td>{{ $log->duration_minutes }} mins</td>
                                                <td>{{ $log->completed_at ? \Carbon\Carbon::parse($log->completed_at)->format('M d, H:i') : 'N/A' }}</td>
                                                <td>
                                                    <span class="badge {{ $log->completed ? 'badge-success' : 'badge-warning' }}">
                                                        {{ $log->completed ? 'Completed' : 'Logged' }}
                                                    </span>
                                                </td>
                                            @elseif($activeTab === 'goal')
                                                <td>{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</td>
                                                <td>Goal ID: {{ $log->goal_id }}</td>
                                                <td>{{ $log->target_minutes }} mins</td>
                                                <td>{{ $log->achieved_minutes }} mins</td>
                                                <td>{{ $log->goal_date }}</td>
                                                <td>
                                                    <span class="badge {{ $log->completed ? 'badge-success' : 'badge-danger' }}">
                                                        {{ $log->completed ? 'Achieved' : 'Incomplete' }}
                                                    </span>
                                                </td>
                                            @elseif($activeTab === 'emotion')
                                                <td>{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</td>
                                                <td><strong>{{ $log->emotion }}</strong></td>
                                                <td>{{ $log->app_name ?? 'N/A' }}</td>
                                                <td>{{ $log->logged_at?->format('M d, H:i') ?? 'N/A' }}</td>
                                            @elseif($activeTab === 'streak')
                                                <td>{{ $log->user?->name ?? 'User ID: '.$log->user_id }}</td>
                                                <td>{{ $log->current_streak }} days</td>
                                                <td>{{ $log->longest_streak }} days</td>
                                                <td>{{ $log->last_completed_date }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination">
                                {{ $logs->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="empty-state">
                                <p>No logs found for this filter category.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($chartLabels))
        <script>
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
                                backgroundColor: 'rgba(59, 130, 246, 0.4)',
                                borderColor: '#3b82f6',
                                borderWidth: 1
                            },
                            {
                                label: 'Achieved Minutes',
                                data: dataValues.achieved,
                                backgroundColor: 'rgba(16, 185, 129, 0.4)',
                                borderColor: '#10b981',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                ticks: { color: '#94a3b8' }
                            },
                            x: {
                                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                ticks: { color: '#94a3b8' }
                            }
                        },
                        plugins: {
                            legend: { labels: { color: '#e2e8f0' } }
                        }
                    }
                };
            } else if (type === 'doughnut') {
                chartConfig = {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: dataValues,
                            backgroundColor: [
                                '#10b981',
                                '#ef4444',
                                '#3b82f6',
                                '#f59e0b',
                                '#6366f1',
                                '#a855f7',
                                '#ec4899'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: { color: '#e2e8f0' }
                            }
                        }
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
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.08)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#34d399',
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                ticks: { color: '#94a3b8' }
                            },
                            x: {
                                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                ticks: { color: '#94a3b8' }
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
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
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: '#3b82f6',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                ticks: { color: '#94a3b8' }
                            },
                            x: {
                                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                ticks: { color: '#94a3b8' }
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                };
            }

            new Chart(ctx, chartConfig);
        </script>
    @endif
</body>
</html>
