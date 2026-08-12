@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Category Management</li>
</ul>

@if(session('success'))
<div style="background: var(--success-bg); color: var(--success); padding: 0.875rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; border: 1px solid var(--success);">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700;">Categories List</h3>
        <button onclick="openModal('create-category-modal')" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Category
        </button>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.categories.index') }}" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search category name..." class="form-control" style="max-width: 280px;">
        <select name="status" class="form-control" style="max-width: 160px;">
            <option value="">All Statuses</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i> Filter</button>
    </form>

    <!-- Data Table -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Icon</th>
                    <!-- <th>Theme Color</th> -->
                    <th>Activities</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-heading);">{{ $category->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ Str::limit($category->description, 40) ?? 'No description' }}</div>
                    </td>
                    <td>
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--bg-main); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                            <i class="{{ $category->icon ?? 'fa-solid fa-layer-group' }}"></i>
                        </div>
                    </td>
                    
                    <td>
                        <span class="badge badge-info">{{ $category->activities_count }} Activities</span>
                    </td>
                    <td>
                        <span class="badge {{ $category->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($category->status) }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div class="action-btn-group">
                            <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-secondary btn-sm" title="View Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <button type="button" onclick="editCategory({{ json_encode($category) }})" class="btn btn-secondary btn-sm" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" onclick="confirmDelete('{{ route('admin.categories.destroy', $category->id) }}')" class="btn btn-danger btn-sm" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem;">
        {{ $categories->links('partials.pagination') }}
    </div>
</div>

<!-- CREATE CATEGORY MODAL -->
<div class="modal-backdrop" id="create-category-modal">
    <div class="modal-dialog" style="max-width: 540px;">
        <div class="modal-header">
            <h3>Add New Category</h3>
            <button type="button" onclick="closeModal('create-category-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Micro-Movement" required>
                </div>

                
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('create-category-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Category</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT CATEGORY MODAL -->
<div class="modal-backdrop" id="edit-category-modal">
    <div class="modal-dialog" style="max-width: 540px;">
        <div class="modal-header">
            <h3>Edit Category</h3>
            <button type="button" onclick="closeModal('edit-category-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="edit-category-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" id="edit-name" class="form-control" required>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">FontAwesome Icon Class</label>
                        <input type="text" name="icon" id="edit-icon" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Theme Color</label>
                        <input type="color" name="color" id="edit-color" class="form-control" style="height: 38px; padding: 2px;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit-status" class="form-control" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="edit-description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('edit-category-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE MODAL -->
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
                <p>Are you sure you want to delete this category record?</p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('delete-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editCategory(category) {
        const updateUrl = "{{ route('admin.categories.update', ':id') }}".replace(':id', category.id);
        document.getElementById('edit-category-form').action = updateUrl;

        document.getElementById('edit-name').value = category.name ?? '';
        document.getElementById('edit-icon').value = category.icon ?? 'fa-solid fa-layer-group';
        document.getElementById('edit-color').value = category.color ?? '#3B5838';
        document.getElementById('edit-status').value = category.status ?? 'active';
        document.getElementById('edit-description').value = category.description ?? '';

        openModal('edit-category-modal');
    }

    function confirmDelete(actionUrl) {
        document.getElementById('delete-form').action = actionUrl;
        openModal('delete-modal');
    }
</script>
@endsection