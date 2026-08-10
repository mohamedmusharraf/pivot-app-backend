@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Users Directory</li>
</ul>

<!-- Stats Section -->
<div class="grid grid-cols-4" style="margin-bottom: 1.5rem;">
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem;">Total Users</span>
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="stat-icon primary"><i class="fa-solid fa-users"></i></div>
    </div>
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem;">Ready Users</span>
            <div class="stat-value">{{ number_format($stats['ready']) }}</div>
        </div>
        <div class="stat-icon success"><i class="fa-solid fa-user-check"></i></div>
    </div>
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem;">Not Ready</span>
            <div class="stat-value">{{ number_format($stats['not_ready']) }}</div>
        </div>
        <div class="stat-icon warning"><i class="fa-solid fa-user-clock"></i></div>
    </div>
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem;">Google Auth</span>
            <div class="stat-value">{{ number_format($stats['google']) }}</div>
        </div>
        <div class="stat-icon primary"><i class="fa-brands fa-google"></i></div>
    </div>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700;">Users List</h3>
        <button onclick="openModal('create-user-modal')" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add User
        </button>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email..." class="form-control" style="max-width: 280px;">
        <select name="status" class="form-control" style="max-width: 160px;">
            <option value="">All Statuses</option>
            <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
            <option value="not_ready" {{ request('status') === 'not_ready' ? 'selected' : '' }}>Not Ready</option>
        </select>
        <select name="provider" class="form-control" style="max-width: 160px;">
            <option value="">All Providers</option>
            <option value="email" {{ request('provider') === 'email' ? 'selected' : '' }}>Email</option>
            <option value="google" {{ request('provider') === 'google' ? 'selected' : '' }}>Google</option>
            <option value="apple" {{ request('provider') === 'apple' ? 'selected' : '' }}>Apple</option>
        </select>
        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i> Filter</button>
    </form>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User Details</th>
                    <th>Provider</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Registered</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->email }}</div>
                    </td>
                    <td>
                        <span style="text-transform: capitalize;">
                            <i class="fa-brands fa-{{ $user->provider === 'email' ? 'envelope' : $user->provider }}"></i> {{ $user->provider }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $user->status === 'ready' ? 'badge-success' : 'badge-warning' }}">
                            {{ $user->status === 'ready' ? 'Ready' : 'Not Ready' }}
                        </span>
                    </td>
                    <td>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'N/A' }}</td>
                    <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i></a>
                        <button type="button" onclick="editUser({{ json_encode($user) }})" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pen"></i></button>
                        <button type="button" onclick="confirmDelete({{ $user->id }})" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem;">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span style="color: var(--text-muted); font-size: 0.8125rem;">
            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
        </span>
        {{ $users->links('partials.pagination') }}
    </div>
</div>

<!-- Create Modal -->
<div class="modal-backdrop" id="create-user-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>Add New User</h3>
            <button type="button" onclick="closeModal('create-user-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Provider</label>
                    <select name="provider" class="form-control" required>
                        <option value="email">Email</option>
                        <option value="google">Google</option>
                        <option value="apple">Apple</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="ready">Ready</option>
                        <option value="not_ready" selected>Not Ready</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('create-user-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-backdrop" id="edit-user-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>Edit User</h3>
            <button type="button" onclick="closeModal('edit-user-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="edit-user-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" id="edit-name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" id="edit-email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">New Password (Leave empty to keep existing)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Provider</label>
                    <select name="provider" id="edit-provider" class="form-control" required>
                        <option value="email">Email</option>
                        <option value="google">Google</option>
                        <option value="apple">Apple</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit-status" class="form-control" required>
                        <option value="ready">Ready</option>
                        <option value="not_ready">Not Ready</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('edit-user-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
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
                <p>Are you sure you want to delete this user?</p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('delete-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editUser(user) {
        let updateUrl = "{{ route('admin.users.update', ':id') }}".replace(':id', user.id);

        document.getElementById('edit-user-form').action = updateUrl;
        document.getElementById('edit-name').value = user.name;
        document.getElementById('edit-email').value = user.email;
        document.getElementById('edit-provider').value = user.provider || 'email';
        document.getElementById('edit-status').value = user.status || 'not_ready';

        openModal('edit-user-modal');
    }

    function confirmDelete(userId) {
        let deleteUrl = "{{ route('admin.users.destroy', ':id') }}".replace(':id', userId);
        document.getElementById('delete-form').action = deleteUrl;
        openModal('delete-modal');
    }
</script>
@endsection