@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="#">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Dashboard</li>
</ul>

<div class="grid grid-cols-4" style="margin-bottom: 2rem;">

    <!-- Total Users -->
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Total Users</span>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <span style="color: var(--success); font-size: 0.75rem; font-weight: 600;"><i class="fa-solid fa-arrow-up"></i> +12.5%</span>
        </div>
        <div class="stat-icon primary">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>

    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Active Subscriptions</span>
            <div class="stat-value">3,420</div>
            <span style="color: var(--success); font-size: 0.75rem; font-weight: 600;"><i class="fa-solid fa-arrow-up"></i> +8.1%</span>
        </div>
        <div class="stat-icon success">
            <i class="fa-solid fa-credit-card"></i>
        </div>
    </div>

    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Monthly Revenue</span>
            <div class="stat-value">$18,450</div>
            <span style="color: var(--danger); font-size: 0.75rem; font-weight: 600;"><i class="fa-solid fa-arrow-down"></i> -2.3%</span>
        </div>
        <div class="stat-icon warning">
            <i class="fa-solid fa-dollar-sign"></i>
        </div>
    </div>

    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Activities Completed</span>
            <div class="stat-value">112,890</div>
            <span style="color: var(--success); font-size: 0.75rem; font-weight: 600;"><i class="fa-solid fa-arrow-up"></i> +24%</span>
        </div>
        <div class="stat-icon primary">
            <i class="fa-solid fa-circle-check"></i>
        </div>
    </div>

</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <div>
            <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--text-heading);">Users Logged In (Last 7 Days)</h3>
            <p style="color: var(--text-muted); font-size: 0.8125rem;">Overview of users who logged into the application during the past 7 days.</p>
        </div>
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

    <!-- Dynamic Pagination -->
    <div class="pagination">
        <span style="color: var(--text-muted); font-size: 0.8125rem;">
            Showing {{ $recentUsers->firstItem() ?? 0 }} to {{ $recentUsers->lastItem() ?? 0 }} of {{ $recentUsers->total() }} entries
        </span>

        {{ $recentUsers->links('partials.pagination') }}
    </div>
</div>
@endsection