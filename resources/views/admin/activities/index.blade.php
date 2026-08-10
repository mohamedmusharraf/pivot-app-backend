@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Activities Management</li>
</ul>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700;">Activities List</h3>
        <button onclick="openModal('create-activity-modal')" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Activity
        </button>
    </div>

    <form method="GET" action="{{ route('admin.activities.index') }}" style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, description..." class="form-control" style="max-width: 280px;">
        <input type="text" name="tier" value="{{ request('tier') }}" placeholder="Tier (e.g. Free, Premium)" class="form-control" style="max-width: 180px;">
        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i> Filter</button>
    </form>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Duration</th>
                    <th>Tier</th>
                    <th>Energy Level</th>
                    <th>Neurodivergent Friendly</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $activity)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $activity->activity_title }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $activity->subcategory ?? 'General' }}</div>
                    </td>
                    <td>{{ $activity->activity_type ?? 'N/A' }}</td>
                    <td>{{ $activity->duration_minutes ? $activity->duration_minutes . ' mins' : 'N/A' }}</td>
                    <td><span class="badge badge-info">{{ $activity->tier ?? 'Standard' }}</span></td>
                    <td>{{ $activity->energy_level ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $activity->neurodivergent_friendly ? 'badge-success' : 'badge-warning' }}">
                            {{ $activity->neurodivergent_friendly ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.activities.show', $activity->id) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i></a>
                        <button onclick="editActivity({{ json_encode($activity) }})" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pen"></i></button>
                        <button onclick="confirmDelete('{{ route('admin.activities.destroy', $activity->id) }}')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align: center; padding: 2rem;">No activities found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem;">
        {{ $activities->links() }}
    </div>
</div>

<div class="modal-backdrop" id="create-activity-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>Add Activity</h3>
            <button onclick="closeModal('create-activity-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.activities.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Activity Title</label>
                    <input type="text" name="activity_title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Activity Type</label>
                    <input type="text" name="activity_type" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Energy Level</label>
                    <input type="text" name="energy_level" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('create-activity-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Activity</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-backdrop" id="edit-activity-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>Edit Activity</h3>
            <button onclick="closeModal('edit-activity-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="edit-activity-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Activity Title</label>
                    <input type="text" name="activity_title" id="edit-title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Activity Type</label>
                    <input type="text" name="activity_type" id="edit-type" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" id="edit-duration" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Energy Level</label>
                    <input type="text" name="energy_level" id="edit-energy" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('edit-activity-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Activity</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-backdrop" id="delete-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>Confirm Delete</h3>
            <button onclick="closeModal('delete-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="delete-form" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <p>Are you sure you want to delete this activity?</p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('delete-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
function editActivity(activity) {
    document.getElementById('edit-activity-form').action = `/admin/activities/${activity.id}`;
    document.getElementById('edit-title').value = activity.activity_title;
    document.getElementById('edit-type').value = activity.activity_type ?? '';
    document.getElementById('edit-duration').value = activity.duration_minutes ?? '';
    document.getElementById('edit-energy').value = activity.energy_level ?? '';
    openModal('edit-activity-modal');
}

function confirmDelete(actionUrl) {
    document.getElementById('delete-form').action = actionUrl;
    openModal('delete-modal');
}
</script>
@endsection