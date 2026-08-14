@extends('layouts.admin')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Challenge Packs Management</li>
</ul>

<div class="card" style="text-align: center; padding: 4rem 2rem; margin-top: 1rem;">
    <div style="width: 80px; height: 80px; background: var(--primary-light); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; font-size: 2.25rem;">
        <i class="fa-solid fa-screwdriver-wrench"></i>
    </div>
    
    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.75rem;">
        Module Under Construction
    </h2>

    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
            <i class="fa-solid fa-house"></i> Back to Dashboard
        </a>
    </div>
</div>
@endsection