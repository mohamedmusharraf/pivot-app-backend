@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="#">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Dashboard</li>
</ul>

<div class="grid grid-cols-4" style="margin-bottom: 2rem;">
    
    <div class="card stat-card">
        <div>
            <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Total Users</span>
            <div class="stat-value">24,512</div>
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
            <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--text-heading);">Recent Registered Users</h3>
            <p style="color: var(--text-muted); font-size: 0.8125rem;">Overview of users registered in the last 24 hours.</p>
        </div>
        <button onclick="openModal('sample-modal')" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add New User
        </button>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User Profile</th>
                    <th>Provider</th>
                    <th>Status</th>
                    <th>Joined Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-heading);">Sarah Jenkins</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">sarah.j@example.com</div>
                    </td>
                    <td><i class="fa-brands fa-google" style="margin-right: 4px;"></i> Google</td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>Aug 05, 2026</td>
                    <td style="text-align: right;">
                        <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-heading);">Michael Chen</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">m.chen@example.com</div>
                    </td>
                    <td><i class="fa-brands fa-apple" style="margin-right: 4px;"></i> Apple</td>
                    <td><span class="badge badge-warning">Pending</span></td>
                    <td>Aug 05, 2026</td>
                    <td style="text-align: right;">
                        <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span style="color: var(--text-muted); font-size: 0.8125rem;">Showing 1 to 2 of 50 entries</span>
        <div style="display: flex; gap: 0.375rem;">
            <button class="btn btn-secondary btn-sm" disabled>Previous</button>
            <button class="btn btn-primary btn-sm">1</button>
            <button class="btn btn-secondary btn-sm">2</button>
            <button class="btn btn-secondary btn-sm">Next</button>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="sample-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 style="font-size: 1.125rem; font-weight: 700;">Add New User</h3>
            <button onclick="closeModal('sample-modal')" style="background: none; border: none; cursor: pointer; color: var(--text-muted);">
                <i class="fa-solid fa-xmark fa-lg"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" placeholder="Enter user full name">
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" placeholder="name@domain.com">
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeModal('sample-modal')" class="btn btn-secondary">Cancel</button>
            <button class="btn btn-primary">Save User</button>
        </div>
    </div>
</div>
@endsection