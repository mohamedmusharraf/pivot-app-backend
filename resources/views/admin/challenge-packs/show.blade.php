@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li><a href="{{ route('admin.challenge-packs.index') }}">Challenge Packs Management</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Challenge Pack Details</li>
</ul>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-heading); margin: 0;">{{ $pack->product_id }}</h2>
        <span class="badge {{ $pack->status === 'unused' || $pack->status === 'active' ? 'badge-success' : 'badge-warning' }}" style="margin-top: 0.25rem;">
            {{ ucfirst($pack->status) }}
        </span>
    </div>

    <a href="{{ route('admin.challenge-packs.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="grid grid-cols-2" style="gap: 1.5rem;">
    <div class="card">
        <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-heading); margin-bottom: 1rem; border-bottom: 1px solid var(--border-light); padding-bottom: 0.5rem;">
            Pack Attributes
        </h3>

        <div style="display: flex; flex-direction: column; gap: 0.875rem;">
            <div>
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">App ID</span>
                <strong style="color: var(--text-heading);">{{ $pack->app_id }}</strong>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Price</span>
                <strong style="color: var(--text-heading); font-size: 1.125rem;">${{ number_format($pack->price, 2) }}</strong>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Type</span>
                <strong style="color: var(--text-heading);">{{ $pack->type ?? 'N/A' }}</strong>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Store Platform</span>
                <strong style="color: var(--text-heading);">{{ ucfirst(str_replace('_', ' ', $pack->store ?? 'N/A')) }}</strong>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Environment</span>
                <strong style="color: var(--text-heading);">{{ ucfirst($pack->environment ?? 'Production') }}</strong>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-heading); margin-bottom: 1rem; border-bottom: 1px solid var(--border-light); padding-bottom: 0.5rem;">
            Usage & Transaction Info
        </h3>

        <div style="display: flex; flex-direction: column; gap: 0.875rem;">
            <div>
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Assigned User</span>
                <strong style="color: var(--text-heading);">
                    {{ $pack->user ? $pack->user->name . ' (' . $pack->user->email . ')' : 'Unassigned' }}
                </strong>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Transaction ID</span>
                <strong style="color: var(--text-heading);">{{ $pack->transaction_id ?? 'N/A' }}</strong>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Remaining / Total Items</span>
                <span class="badge badge-info" style="font-size: 0.875rem;">
                    {{ $pack->remaining }} / {{ $pack->total }} Items
                </span>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Created Date</span>
                <strong style="color: var(--text-heading);">{{ $pack->created_at->format('M d, Y H:i A') }}</strong>
            </div>
        </div>
    </div>
</div>
@endsection