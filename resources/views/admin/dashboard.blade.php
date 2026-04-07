@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="row g-4">

    <!-- Total Users Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div style="font-size:40px">👥</div>
                <h3 class="mt-2">{{ $totalUsers }}</h3>
                <p class="text-muted mb-0">Total Users</p>
            </div>
        </div>
    </div>

    <!-- Forms Card  -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div style="font-size:40px">📋</div>
                <h3 class="mt-2">{{ $totalForms }}</h3>
                <p class="text-muted mb-0">Total Forms</p>
            </div>
        </div>
    </div>

    <!-- Submissions Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div style="font-size:40px">📥</div>
                <h3 class="mt-2">{{ $totalSubmissions }}</h3>
                <p class="text-muted mb-0">Submissions</p>
            </div>
        </div>
    </div>

    <!-- Admin Info Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div style="font-size:40px">⚙️</div>
                <h3 class="mt-2">1</h3>
                <p class="text-muted mb-0">Admins</p>
            </div>
        </div>
    </div>

</div>

<!-- Welcome Message -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body p-4">
        <h5>Welcome back, {{ auth()->user()->name }}! 👋</h5>
        <p class="text-muted mb-0">
            You are logged in as <strong>Admin</strong>.
            Use the sidebar to manage Forms, Users, Submissions, Import and Export.
        </p>
    </div>
</div>

@endsection