@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Challenge Packs Management</li>
</ul>

@if(session('success'))
<div style="background: var(--success-bg); color: var(--success); padding: 0.875rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; border: 1px solid var(--success);">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700;">Challenge Packs List</h3>
        <button onclick="openModal('create-pack-modal')" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Challenge Pack
        </button>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.challenge-packs.index') }}" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product ID, app ID..." class="form-control" style="max-width: 280px;">
        <select name="status" class="form-control" style="max-width: 160px;">
            <option value="">All Statuses</option>
            <option value="unused" {{ request('status') === 'unused' ? 'selected' : '' }}>Unused</option>
            <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Used</option>
        </select>
        <select name="store" class="form-control" style="max-width: 160px;">
            <option value="">All Stores</option>
            <option value="app_store" {{ request('store') === 'app_store' ? 'selected' : '' }}>App Store</option>
            <option value="play_store" {{ request('store') === 'play_store' ? 'selected' : '' }}>Play Store</option>
            <option value="stripe" {{ request('store') === 'stripe' ? 'selected' : '' }}>Stripe</option>
        </select>
        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i> Filter</button>
    </form>

    <!-- Data Table -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product & App ID</th>
                    <th>User</th>
                    <th>Price</th>
                    <th>Store / Environment</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($challengePacks as $pack)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-heading);">{{ $pack->product_id }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">App ID: {{ $pack->app_id }}</div>
                    </td>
                    <td>
                        @if($pack->user)
                        <div style="font-weight: 600; color: var(--text-heading);">{{ $pack->user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $pack->user->email }}</div>
                        @else
                        <span style="color: var(--text-muted);">Unassigned</span>
                        @endif
                    </td>
                    <td><strong style="color: var(--text-heading);">${{ number_format($pack->price, 2) }}</strong></td>
                    <td>
                        <div style="font-weight: 500;">{{ ucfirst(str_replace('_', ' ', $pack->store ?? 'N/A')) }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ ucfirst($pack->environment ?? 'Production') }}</div>
                    </td>
                    <td>
                        <span class="badge badge-info" style="background: var(--primary-light); color: var(--primary);">
                            {{ $pack->remaining }} / {{ $pack->total }} Left
                        </span>
                    </td>
                    <td>
                        @if($pack->status === 'unused')
                        <span class="badge badge-success">Unused</span>
                        @elseif($pack->status === 'used')
                        <span class="badge badge-warning">Used</span>
                        @else
                        <span class="badge badge-info">{{ ucfirst($pack->status) }}</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div class="action-btn-group">
                            <a href="{{ route('admin.challenge-packs.show', $pack->id) }}" class="btn btn-secondary btn-sm" title="View Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <button type="button" onclick="editPack({{ json_encode($pack) }})" class="btn btn-secondary btn-sm" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" onclick="confirmDelete('{{ route('admin.challenge-packs.destroy', $pack->id) }}')" class="btn btn-danger btn-sm" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">No challenge packs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem;">
        {{ $challengePacks->links('partials.pagination') }}
    </div>
</div>

<!-- CREATE MODAL -->
<div class="modal-backdrop" id="create-pack-modal">
    <div class="modal-dialog" style="max-width: 680px;">
        <div class="modal-header">
            <h3>Add New Challenge Pack</h3>
            <button type="button" onclick="closeModal('create-pack-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.challenge-packs.store') }}" method="POST">
            @csrf
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">App ID *</label>
                        <input type="text" name="app_id" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Product ID *</label>
                        <input type="text" name="product_id" class="form-control" required>
                    </div>

                    <div class="form-group col-span-2">
                        <label class="form-label">Assigned User</label>
                        <select name="user_id" class="form-control">
                            <option value="">Select User (Optional)...</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Price ($) *</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Type</label>
                        <input type="text" name="type" class="form-control" placeholder="e.g. InApp, Pack">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Store</label>
                        <select name="store" class="form-control">
                            <option value="app_store">App Store</option>
                            <option value="play_store">Play Store</option>
                            <option value="stripe">Stripe</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Environment</label>
                        <select name="environment" class="form-control">
                            <option value="Production">Production</option>
                            <option value="Sandbox">Sandbox</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Total Items *</label>
                        <input type="number" name="total" class="form-control" value="0" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Remaining Items *</label>
                        <input type="number" name="remaining" class="form-control" value="0" required>
                    </div>

                    <div class="form-group col-span-2">
                        <label class="form-label">Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control">
                    </div>

                    <div class="form-group col-span-2">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="unused">Unused</option>
                            <option value="used">Used</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('create-pack-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Pack</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-backdrop" id="edit-pack-modal">
    <div class="modal-dialog" style="max-width: 680px;">
        <div class="modal-header">
            <h3>Edit Challenge Pack</h3>
            <button type="button" onclick="closeModal('edit-pack-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="edit-pack-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">App ID *</label>
                        <input type="text" name="app_id" id="edit-app-id" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Product ID *</label>
                        <input type="text" name="product_id" id="edit-product-id" class="form-control" required>
                    </div>

                    <div class="form-group col-span-2">
                        <label class="form-label">Assigned User</label>
                        <select name="user_id" id="edit-user-id" class="form-control">
                            <option value="">Select User (Optional)...</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Price ($) *</label>
                        <input type="number" step="0.01" name="price" id="edit-price" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Type</label>
                        <input type="text" name="type" id="edit-type" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Store</label>
                        <select name="store" id="edit-store" class="form-control">
                            <option value="app_store">App Store</option>
                            <option value="play_store">Play Store</option>
                            <option value="stripe">Stripe</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Environment</label>
                        <select name="environment" id="edit-environment" class="form-control">
                            <option value="Production">Production</option>
                            <option value="Sandbox">Sandbox</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Total Items *</label>
                        <input type="number" name="total" id="edit-total" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Remaining Items *</label>
                        <input type="number" name="remaining" id="edit-remaining" class="form-control" required>
                    </div>

                    <div class="form-group col-span-2">
                        <label class="form-label">Transaction ID</label>
                        <input type="text" name="transaction_id" id="edit-transaction-id" class="form-control">
                    </div>

                    <div class="form-group col-span-2">
                        <label class="form-label">Status *</label>
                        <select name="status" id="edit-status" class="form-control" required>
                            <option value="unused">Unused</option>
                            <option value="used">Used</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('edit-pack-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Pack</button>
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
                <p>Are you sure you want to delete this challenge pack entry?</p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('delete-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editPack(pack) {
        const updateUrl = "{{ route('admin.challenge-packs.update', ':id') }}".replace(':id', pack.id);
        document.getElementById('edit-pack-form').action = updateUrl;

        document.getElementById('edit-app-id').value = pack.app_id ?? '';
        document.getElementById('edit-product-id').value = pack.product_id ?? '';
        document.getElementById('edit-user-id').value = pack.user_id ?? '';
        document.getElementById('edit-price').value = pack.price ?? 0;
        document.getElementById('edit-type').value = pack.type ?? '';
        document.getElementById('edit-store').value = pack.store ?? 'app_store';
        document.getElementById('edit-environment').value = pack.environment ?? 'Production';
        document.getElementById('edit-total').value = pack.total ?? 0;
        document.getElementById('edit-remaining').value = pack.remaining ?? 0;
        document.getElementById('edit-transaction-id').value = pack.transaction_id ?? '';
        document.getElementById('edit-status').value = pack.status ?? 'unused';

        openModal('edit-pack-modal');
    }

    function confirmDelete(actionUrl) {
        document.getElementById('delete-form').action = actionUrl;
        openModal('delete-modal');
    }
</script>
@endsection