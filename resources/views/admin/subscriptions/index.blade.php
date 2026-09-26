@extends('layouts.admin')

@section('title', 'Subscriptions | Pivot Admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Subscription Management</li>
</ul>

<!-- STATS CARDS -->
<div class="grid grid-cols-4" style="margin-bottom: 1.5rem;">
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem;">Active Subscriptions</span>
            <div class="stat-value">{{ number_format($metrics['active'] ?? 0) }}</div>
        </div>
        <div class="stat-icon success"><i class="fa-solid fa-circle-check"></i></div>
    </div>
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem;">Expired Subscriptions</span>
            <div class="stat-value">{{ number_format($metrics['expired'] ?? 0) }}</div>
        </div>
        <div class="stat-icon warning"><i class="fa-solid fa-clock-rotate-left"></i></div>
    </div>
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem;">Cancelled Subscriptions</span>
            <div class="stat-value">{{ number_format($metrics['cancelled'] ?? 0) }}</div>
        </div>
        <div class="stat-icon danger"><i class="fa-solid fa-ban"></i></div>
    </div>
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem;">Monthly Revenue</span>
            <div class="stat-value">${{ number_format($metrics['monthly_revenue'] ?? 0, 2) }}</div>
        </div>
        <div class="stat-icon primary"><i class="fa-solid fa-dollar-sign"></i></div>
    </div>
</div>

<!-- SUBSCRIPTION PANEL -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700;">RevenueCat Subscriptions</h3>
    </div>

    <!-- FILTER FORM -->
    <form method="GET" action="{{ route('admin.subscriptions.index') }}" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user, subscription ID..." class="form-control" style="max-width: 280px;">
        
        <select name="status" class="form-control" style="max-width: 160px;">
            <option value="">All Statuses</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
        </select>

        <select name="store" class="form-control" style="max-width: 160px;">
            <option value="">All Stores</option>
            <option value="App Store" {{ request('store') === 'App Store' ? 'selected' : '' }}>App Store</option>
            <option value="Google Play" {{ request('store') === 'Google Play' ? 'selected' : '' }}>Google Play</option>
            <option value="Stripe" {{ request('store') === 'Stripe' ? 'selected' : '' }}>Stripe</option>
        </select>

        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i> Filter</button>
        @if(request('search') || request('status') || request('store'))
            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary"><i class="fa-solid fa-xmark"></i> Clear</a>
        @endif
    </form>

    <!-- DATA TABLE -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Subscription ID</th>
                    <th>Product ID</th>
                    <th>Store Platform</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Purchase Date</th>
                    <th>Expiry Date</th>
                    <th>Auto Renew</th>
                    <th>Environment</th>
                </tr>
            </thead>
            <tbody>
                @forelse($formattedSubscriptions as $subscription)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-heading);">{{ $subscription['user_name'] }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $subscription['user_email'] }}</div>
                    </td>
                    <td>
                        <span style="font-family: monospace; font-size: 0.8rem; color: var(--text-muted);">
                            {{ $subscription['subscription_id'] }}
                        </span>
                    </td>
                    <td>{{ $subscription['product_id'] }}</td>
                    <td>
                        <span>
                            <i class="fa-brands fa-{{ strtolower($subscription['store_platform']) === 'google play' ? 'google-play' : (strtolower($subscription['store_platform']) === 'app store' ? 'apple' : 'stripe') }}"></i>
                            {{ $subscription['store_platform'] }}
                        </span>
                    </td>
                    <td><strong>{{ $subscription['currency'] === 'USD' ? '$' : $subscription['currency'] }}{{ number_format($subscription['price'], 2) }}</strong></td>
                    <td>
                        <span class="badge {{ $subscription['status'] === 'Active' ? 'badge-success' : ($subscription['status'] === 'Cancelled' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $subscription['status'] }}
                        </span>
                    </td>
                    <td>{{ $subscription['purchase_date'] }}</td>
                    <td>{{ $subscription['expiry_date'] }}</td>
                    <td>
                        <span class="badge {{ $subscription['auto_renew'] === 'Yes' ? 'badge-success' : 'badge-warning' }}">
                            {{ $subscription['auto_renew'] }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $subscription['environment'] === 'Production' ? 'badge-success' : 'badge-warning' }}">
                            {{ $subscription['environment'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 2rem; color: var(--text-muted);">No RevenueCat subscription records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection