@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">User Profiles</li>
</ul>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700;">User Profiles Directory</h3>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.user-profiles.index') }}" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user name, email..." class="form-control" style="max-width: 280px;">
        <select name="gender" class="form-control" style="max-width: 160px;">
            <option value="">All Genders</option>
            <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
            <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Other</option>
        </select>
        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i> Filter</button>
    </form>

    <!-- Data Table -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Country</th>
                    <th>Gender</th>
                    <th>Birth Year</th>
                    <th>Weekly Goal</th>
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
                    <td>{{ $profile->weekly_goal_minutes ?? 0 }} mins</td>
                    <td>
                        <span class="badge {{ $profile->onboarding_completed ? 'badge-success' : 'badge-warning' }}">
                            {{ $profile->onboarding_completed ? 'Completed' : 'Pending' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.user-profiles.show', $profile->user_id) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i></a>
                        <button type="button" onclick="editProfile({{ json_encode($profile) }})" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pen"></i></button>
                        <button type="button" onclick="confirmDelete({{ $profile->user_id }})" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem;">No user profiles found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <span style="color: var(--text-muted); font-size: 0.8125rem;">
            Showing {{ $profiles->firstItem() ?? 0 }} to {{ $profiles->lastItem() ?? 0 }} of {{ $profiles->total() }} entries
        </span>
        {{ $profiles->links('partials.pagination') }}
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal-backdrop" id="edit-profile-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>Edit User Profile</h3>
            <button type="button" onclick="closeModal('edit-profile-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="edit-profile-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <select name="country_id" id="edit-country-id" class="form-control">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select name="gender" id="edit-gender" class="form-control">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Birth Year</label>
                    <input type="number" name="birth_year" id="edit-birth-year" class="form-control" min="1900" max="{{ date('Y') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Set Goal</label>
                    <input type="number" step="0.01" name="set_your_goal" id="edit-set-goal" class="form-control">
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

<!-- Delete Confirmation Modal -->
<div class="modal-backdrop" id="delete-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>Confirm Delete</h3>
            <button type="button" onclick="closeModal('delete-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="delete-form" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <p>Are you sure you want to delete this user profile?</p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('delete-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editProfile(profile) {
        let updateUrl = "{{ route('admin.user-profiles.update', ':id') }}".replace(':id', profile.user_id);

        document.getElementById('edit-profile-form').action = updateUrl;
        document.getElementById('edit-country-id').value = profile.country_id ?? '';
        document.getElementById('edit-gender').value = profile.gender ?? 'male';
        document.getElementById('edit-birth-year').value = profile.birth_year ?? '';
        document.getElementById('edit-set-goal').value = profile.set_your_goal ?? '';
        document.getElementById('edit-goal-minutes').value = profile.weekly_goal_minutes ?? 0;
        document.getElementById('edit-onboarding').value = profile.onboarding_completed ? '1' : '0';

        openModal('edit-profile-modal');
    }

    function confirmDelete(userId) {
        let deleteUrl = "{{ route('admin.user-profiles.destroy', ':id') }}".replace(':id', userId);
        document.getElementById('delete-form').action = deleteUrl;
        openModal('delete-modal');
    }
</script>
@endsection