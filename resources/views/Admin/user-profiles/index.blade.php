@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">User Profiles</li>
</ul>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700;">User Profiles</h3>
    </div>

    <form method="GET" action="{{ route('admin.user-profiles.index') }}" style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user..." class="form-control" style="max-width: 280px;">
        <select name="gender" class="form-control" style="max-width: 160px;">
            <option value="">All Genders</option>
            <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
            <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Other</option>
        </select>
        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i> Filter</button>
    </form>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Country</th>
                    <th>Gender</th>
                    <th>Birth Year</th>
                    <th>Weekly Goal (Mins)</th>
                    <th>Onboarding</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($profiles as $profile)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $profile->user?->name ?? 'N/A' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $profile->user?->email }}</div>
                    </td>
                    <td>{{ $profile->country?->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($profile->gender ?? 'N/A') }}</td>
                    <td>{{ $profile->birth_year ?? 'N/A' }}</td>
                    <td>{{ $profile->weekly_goal_minutes }} mins</td>
                    <td>
                        <span class="badge {{ $profile->onboarding_completed ? 'badge-success' : 'badge-warning' }}">
                            {{ $profile->onboarding_completed ? 'Completed' : 'Pending' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.user-profiles.show', $profile->user_id) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i></a>
                        <button onclick="editProfile({{ json_encode($profile) }})" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pen"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align: center; padding: 2rem;">No user profiles found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem;">
        {{ $profiles->links() }}
    </div>
</div>

<div class="modal-backdrop" id="edit-profile-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>Edit User Profile</h3>
            <button onclick="closeModal('edit-profile-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="edit-profile-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <input type="text" name="gender" id="edit-gender" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Birth Year</label>
                    <input type="number" name="birth_year" id="edit-birth-year" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Weekly Goal (Minutes)</label>
                    <input type="number" name="weekly_goal_minutes" id="edit-goal-minutes" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Onboarding Status</label>
                    <select name="onboarding_completed" id="edit-onboarding" class="form-control">
                        <option value="1">Completed</option>
                        <option value="0">Pending</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('edit-profile-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
function editProfile(profile) {
    document.getElementById('edit-profile-form').action = `/admin/user-profiles/${profile.user_id}`;
    document.getElementById('edit-gender').value = profile.gender ?? '';
    document.getElementById('edit-birth-year').value = profile.birth_year ?? '';
    document.getElementById('edit-goal-minutes').value = profile.weekly_goal_minutes;
    document.getElementById('edit-onboarding').value = profile.onboarding_completed ? '1' : '0';
    openModal('edit-profile-modal');
}
</script>
@endsection