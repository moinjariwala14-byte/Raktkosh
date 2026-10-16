<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Red Cross Society - e-RaktKosh Standard Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --primary-red: #c8102e; --dark-red: #9b0018; }
        .bg-redcross { background-color: var(--primary-red) !important; }
        .btn-redcross { background-color: var(--primary-red); color: white; }
        .btn-redcross:hover { background-color: var(--dark-red); color: white; }
        .badge-sos { background-color: #dc3545; animation: blinker 1.5s linear infinite; }
        @keyframes blinker { 50% { opacity: 0.3; } }
    </style>
</head>
<body class="bg-light">

<header class="bg-redcross text-white py-2 shadow-sm border-bottom border-danger">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 fw-bold"><i class="fa-solid fa-square-plus me-2"></i>RED CROSS SOCIETY</h4>
            <small class="text-white-50">Blood Transfusion Services & Inventory Management</small>
        </div>
        <div>
            <span class="badge bg-white text-danger px-3 py-2 fw-bold"><i class="fa-solid fa-phone me-1"></i> Helpline: 104 / 1910</span>
        </div>
    </div>
</header>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}"><i class="fa-solid fa-house me-1"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('stock.directory') }}"><i class="fa-solid fa-cubes-stacked me-1"></i> Stock Availability</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('donor.register') }}"><i class="fa-solid fa-user-plus me-1"></i> Donor Portal</a></li>
                <li class="nav-item"><a class="nav-link text-warning fw-semibold" href="{{ route('request.blood') }}"><i class="fa-solid fa-truck-medical me-1"></i> Blood Requisition</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <li class="nav-item"><a class="nav-link text-info fw-bold" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-shield-halved me-1"></i> Admin Panel</a></li>
                    @endif
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-light btn-sm ms-2 mt-1">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="btn btn-danger btn-sm px-3 mt-1" href="{{ route('login') }}"><i class="fa-solid fa-lock me-1"></i> Officer Login</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-xmark me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<footer class="bg-dark text-white-50 text-center py-4 mt-5">
    <div class="container">
        <p class="mb-0">Red Cross Society Central Blood Transfusion Management Portal</p>
        <small>Compliant with National Blood Transfusion Council Guidelines</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
