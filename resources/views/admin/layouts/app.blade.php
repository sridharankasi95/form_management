<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #2c3e50;
            position: fixed;
            top: 0; left: 0;
        }

        .sidebar a {
            display: block;
            color: #bdc3c7;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover, .sidebar a.active {
            background: #1abc9c;
            color: white;
        }

        .sidebar .brand {
            color: white;
            font-size: 20px;
            font-weight: bold;
            padding: 20px;
            border-bottom: 1px solid #3d5166;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 15px 25px;
            border-bottom: 1px solid #dee2e6;
            margin: -30px -30px 30px -30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">⚙️ Admin Panel</div>

        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            📊 Dashboard
        </a>

        <a href="{{ route('admin.forms.index') }}" class="{{ request()->routeIs('admin.forms*') ? 'active' : '' }}">
            📋 Forms
        </a>

        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            👥 Users
        </a>

        <a href="{{ route('admin.submissions.index') }}" class="{{ request()->routeIs('admin.submissions*') ? 'active' : '' }}">
            📥 Submissions
        </a>

        <a href="{{ route('admin.import.index') }}" class="{{ request()->routeIs('admin.import*') ? 'active' : '' }}">
            📤 Import
        </a>

        <a href="{{ route('admin.export.index') }}" class="{{ request()->routeIs('admin.export*') ? 'active' : '' }}">
            💾 Export
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Top Bar -->
        <div class="topbar">
            <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
            <div>
                👤 {{ auth()->user()->name }}
                &nbsp;|&nbsp;
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </div>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>