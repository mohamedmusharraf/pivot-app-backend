@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Profile & Security</li>
</ul>

@if(session('success'))
<div style="background: var(--success-bg); color: var(--success); padding: 0.875rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; border: 1px solid var(--success);">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-2" style="gap: 1.5rem;">
    <!-- Profile Info Card -->
    <div class="card">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1.25rem; color: var(--text-heading); border-bottom: 1px solid var(--border-light); padding-bottom: 0.75rem;">
            <i class="fa-solid fa-user-gear" style="margin-right: 0.5rem; color: var(--primary);"></i> Profile Information
        </h3>

        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="form-control" required>
                @error('name')
                    <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control" required>
                @error('email')
                    <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Save Profile
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password Card -->
    <div class="card">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1.25rem; color: var(--text-heading); border-bottom: 1px solid var(--border-light); padding-bottom: 0.75rem;">
            <i class="fa-solid fa-lock" style="margin-right: 0.5rem; color: var(--primary);"></i> Change Password
        </h3>

        <form action="{{ route('admin.profile.password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
                @error('current_password')
                    <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')
                    <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-shield-halved"></i> Update Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection