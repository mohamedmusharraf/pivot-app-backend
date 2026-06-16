<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Error Logs</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --surface-color: rgba(30, 41, 59, 0.7);
            --surface-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #3b82f6;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #0ea5e9;
            --debug: #64748b;
            --glass-bg: rgba(15, 23, 42, 0.6);
            --glass-blur: blur(12px);
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
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(239, 68, 68, 0.15) 0px, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            padding: 2rem;
            line-height: 1.5;
            background-attachment: fixed;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--surface-border);
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(to right, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.025em;
        }

        .stats {
            display: flex;
            gap: 1rem;
        }

        .stat-badge {
            background: var(--surface-color);
            border: 1px solid var(--surface-border);
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            backdrop-filter: var(--glass-blur);
        }

        .log-grid {
            display: grid;
            gap: 1.5rem;
        }

        .log-card {
            background: var(--surface-color);
            border: 1px solid var(--surface-border);
            border-radius: 1rem;
            padding: 1.5rem;
            backdrop-filter: var(--glass-blur);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .log-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        .log-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }

        .log-card.level-error::before, .log-card.level-critical::before, .log-card.level-emergency::before {
            background-color: var(--danger);
            box-shadow: 0 0 10px var(--danger);
        }

        .log-card.level-warning::before {
            background-color: var(--warning);
            box-shadow: 0 0 10px var(--warning);
        }

        .log-card.level-info::before {
            background-color: var(--info);
            box-shadow: 0 0 10px var(--info);
        }

        .log-card.level-debug::before {
            background-color: var(--debug);
        }

        .log-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .log-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge.error { background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }
        .badge.warning { background: rgba(245, 158, 11, 0.2); color: #fcd34d; border: 1px solid rgba(245, 158, 11, 0.3); }
        .badge.info { background: rgba(14, 165, 233, 0.2); color: #7dd3fc; border: 1px solid rgba(14, 165, 233, 0.3); }
        .badge.debug { background: rgba(100, 116, 139, 0.2); color: #cbd5e1; border: 1px solid rgba(100, 116, 139, 0.3); }

        .log-time {
            color: var(--text-muted);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .log-message {
            font-size: 1.125rem;
            font-weight: 500;
            margin-bottom: 1rem;
            word-break: break-word;
        }

        .log-details {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .detail-row {
            display: flex;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            color: var(--text-muted);
            width: 100px;
            flex-shrink: 0;
            font-weight: 500;
        }

        .detail-value {
            color: var(--text-main);
            font-family: 'JetBrains Mono', monospace;
            word-break: break-all;
        }

        .code-block {
            background: #000;
            color: #a78bfa;
            padding: 1rem;
            border-radius: 0.5rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875rem;
            overflow-x: auto;
            margin-top: 1rem;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .toggle-trace {
            background: transparent;
            border: 1px solid var(--surface-border);
            color: var(--text-muted);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .toggle-trace:hover {
            color: var(--text-main);
            border-color: var(--text-muted);
            background: rgba(255,255,255,0.05);
        }

        .trace-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .trace-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: var(--surface-color);
            border: 1px dashed var(--surface-border);
            border-radius: 1rem;
            backdrop-filter: var(--glass-blur);
        }

        .empty-state svg {
            width: 4rem;
            height: 4rem;
            color: var(--info);
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-muted);
        }

        .pagination {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .page-link {
            padding: 0.5rem 1rem;
            background: var(--surface-color);
            border: 1px solid var(--surface-border);
            color: var(--text-main);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .page-link:hover, .page-link.active {
            background: var(--primary);
            border-color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>System Error Logs</h1>
            <div class="stats">
                @php
                    $logs = \App\Models\Log::orderBy('created_at', 'desc')->paginate(15);
                    $totalErrors = \App\Models\Log::whereIn('level', ['error', 'critical', 'emergency'])->count();
                @endphp
                <div class="stat-badge">Total Logs: {{ $logs->total() }}</div>
                <div class="stat-badge" style="color: #fca5a5; border-color: rgba(239, 68, 68, 0.3);">Errors: {{ $totalErrors }}</div>
            </div>
        </div>

        @if($logs->count() > 0)
            <div class="log-grid">
                @foreach($logs as $log)
                    @php
                        $levelClass = strtolower($log->level);
                        if (in_array($levelClass, ['error', 'critical', 'emergency'])) $badgeClass = 'error';
                        elseif (in_array($levelClass, ['warning'])) $badgeClass = 'warning';
                        elseif (in_array($levelClass, ['info', 'notice'])) $badgeClass = 'info';
                        else $badgeClass = 'debug';
                    @endphp
                    
                    <div class="log-card level-{{ $badgeClass }}">
                        <div class="log-header">
                            <div class="log-meta">
                                <span class="badge {{ $badgeClass }}">{{ $log->level }}</span>
                                <span class="badge" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1);">{{ $log->channel }}</span>
                            </div>
                            <div class="log-time">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $log->created_at->format('M d, Y H:i:s') }}
                            </div>
                        </div>

                        <div class="log-message">
                            {{ $log->message }}
                        </div>

                        @if($log->file || $log->url || $log->user_id)
                            <div class="log-details">
                                @if($log->url)
                                    <div class="detail-row">
                                        <div class="detail-label">URL</div>
                                        <div class="detail-value">{{ $log->url }}</div>
                                    </div>
                                @endif
                                @if($log->file)
                                    <div class="detail-row">
                                        <div class="detail-label">File</div>
                                        <div class="detail-value">{{ $log->file }} @if($log->line):{{ $log->line }}@endif</div>
                                    </div>
                                @endif
                                @if($log->user_id)
                                    <div class="detail-row">
                                        <div class="detail-label">User ID</div>
                                        <div class="detail-value">{{ $log->user_id }}</div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($log->trace)
                            <button class="toggle-trace" onclick="this.nextElementSibling.classList.toggle('active')">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                Toggle Stack Trace
                            </button>
                            <div class="trace-content">
                                <div class="code-block">{{ $log->trace }}</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="pagination">
                {{ $logs->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3>All Clear!</h3>
                <p>No system logs found in the database. Everything is running smoothly.</p>
            </div>
        @endif
    </div>
</body>
</html>
