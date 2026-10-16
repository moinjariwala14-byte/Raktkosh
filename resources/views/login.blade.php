<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Login - Red Cross Society</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .login-card { max-width: 440px; margin: 80px auto; border-top: 5px solid #b30000; border-radius: 8px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-danger" href="/">
            <i class="fa-solid fa-droplet me-2"></i>RED CROSS SOCIETY
        </a>
        <div class="ms-auto">
            <a class="btn btn-outline-light btn-sm" href="/"><i class="fa-solid fa-house me-1"></i>Home</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="card login-card shadow p-4 bg-white">
        <div class="text-center mb-4">
            <i class="fa-solid fa-user-shield text-danger fa-3x mb-2"></i>
            <h4 class="fw-bold text-dark">Officer Sign In</h4>
            <p class="text-muted small">Red Cross Transfusion Administrative Portal</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger py-2 small">
                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success py-2 small">
                <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope text-secondary"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="admin@redcross.org" value="{{ old('email', 'admin@redcross.org') }}" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock text-secondary"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••••••" value="Admin@12345" required>
                </div>
            </div>

            <button type="submit" class="btn btn-danger w-100 fw-bold py-2 shadow-sm">
                <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In to Dashboard
            </button>
        </form>
    </div>
</div>

</body>
</html>
