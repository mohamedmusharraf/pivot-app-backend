<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pivot Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body>
    <div class="app-wrapper">
        
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
    <img src="{{ asset('assets/img/logo.png') }}" 
         alt="Pivot Logo" 
         style="width: 36px; height: 36px; border-radius: 8px; object-fit: cover;">
    <span class="sidebar-brand-title">Pivot Admin</span>
</div>

            <nav class="sidebar-nav">
    <div class="nav-section-title">Core</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-chart-pie"></i>
        <span>Dashboard</span>
    </a>

    <div class="nav-section-title">User Management</div>
    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="fa-solid fa-users"></i>
        <span>Users</span>
    </a>
    <a href="{{ route('admin.user-profiles.index') }}" class="nav-item {{ request()->routeIs('admin.user-profiles.*') ? 'active' : '' }}">
        <i class="fa-solid fa-id-card"></i>
        <span>User Profiles</span>
    </a>

    <div class="nav-section-title">Content & Program</div>
    <a href="{{ route('admin.activities.index') }}" class="nav-item {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
        <i class="fa-solid fa-person-running"></i>
        <span>Activities</span>
    </a>
    <a href="#" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="fa-solid fa-layer-group"></i>
        <span>Categories</span>
    </a>
    <a href="#" class="nav-item {{ request()->routeIs('admin.challenge-packs.*') ? 'active' : '' }}">
        <i class="fa-solid fa-trophy"></i>
        <span>Challenge Packs</span>
    </a>
    <a href="#" class="nav-item {{ request()->routeIs('admin.research-articles.*') ? 'active' : '' }}">
        <i class="fa-solid fa-book-open"></i>
        <span>Research Articles</span>
    </a>

    <div class="nav-section-title">Monetization</div>
    <a href="#" class="nav-item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
        <i class="fa-solid fa-credit-card"></i>
        <span>Subscriptions</span>
    </a>
    <a href="#" class="nav-item {{ request()->routeIs('admin.payment-history.*') ? 'active' : '' }}">
        <i class="fa-solid fa-receipt"></i>
        <span>Payment History</span>
    </a>

    <div class="nav-section-title">System</div>
    <a href="#" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <i class="fa-solid fa-gear"></i>
        <span>Settings</span>
    </a>
</nav>
        </aside>

        <div class="main-content">
            
            <header class="topbar">
                <div class="topbar-left">
                    <button class="btn btn-secondary btn-icon" id="sidebar-toggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>

                <div class="topbar-right">
    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 38px; height: 38px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700;">
            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
        </div>
        <div>
            <div style="font-weight: 600; font-size: 0.875rem;">{{ Auth::user()->name ?? 'Admin User' }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ Auth::user()->email ?? 'admin@pivot.com' }}</div>
        </div>
    </div>

    <form action="{{ route('admin.logout') }}" method="POST" style="margin-left: 0.5rem;">
        @csrf
        <button type="submit" class="btn btn-secondary btn-sm" title="Logout">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </button>
    </form>
</div>
            </header>

            <main class="content-body">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.getElementById('sidebar-toggle').addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });

        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
    </script>
</body>
</html>