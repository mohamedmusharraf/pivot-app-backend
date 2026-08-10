@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li><a href="{{ route('admin.user-profiles.index') }}">User Profiles</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Profile Details</li>
</ul>

<div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
    <!-- Profile Info Card -->
    <div class="card" style="flex: 1; min-width: 320px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.125rem; font-weight: 700;">User Profile Info</h3>
            <span class="badge {{ $profile->onboarding_completed ? 'badge-success' : 'badge-warning' }}">
                {{ $profile->onboarding_completed ? 'Onboarding Completed' : 'Onboarding Pending' }}
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
            <div>
                <span style="color: var(--text-muted); font-size: 0.75rem;">Full Name</span>
                <div style="font-weight: 600; font-size: 1rem;">{{ $profile->user?->name ?? 'N/A' }}</div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.75rem;">Email Address</span>
                <div style="font-weight: 600; font-size: 1rem;">{{ $profile->user?->email ?? 'N/A' }}</div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.75rem;">Country</span>
                <div style="font-weight: 600;">{{ $profile->country?->name ?? 'N/A' }}</div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.75rem;">Gender</span>
                <div style="font-weight: 600;">{{ ucfirst($profile->gender ?? 'N/A') }}</div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.75rem;">Birth Year</span>
                <div style="font-weight: 600;">{{ $profile->birth_year ?? 'N/A' }}</div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.75rem;">Weekly Goal</span>
                <div style="font-weight: 600;">{{ $profile->weekly_goal_minutes }} minutes</div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.75rem;">Set Goal Target</span>
                <div style="font-weight: 600;">{{ $profile->set_your_goal ?? 'N/A' }}</div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.75rem;">Profile Created</span>
                <div style="font-weight: 600;">{{ $profile->created_at ? $profile->created_at->format('M d, Y') : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Hobbies Card -->
    <div class="card" style="width: 340px;">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1.25rem;">User Hobbies</h3>
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
            @forelse($profile->hobbies as $hobby)
            <span class="badge badge-info">{{ $hobby->name }}</span>
            @empty
            <p style="color: var(--text-muted); font-size: 0.875rem;">No hobbies linked to this user.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection