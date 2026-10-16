<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooldown Notice - Red Cross Society</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-danger" href="/"><i class="fa-solid fa-droplet me-2"></i>RED CROSS SOCIETY</a>
        <a class="btn btn-outline-light btn-sm" href="/"><i class="fa-solid fa-house me-1"></i>Home</a>
    </div>
</nav>

<div class="container my-5">
    <div class="card shadow border-0 mx-auto" style="max-width: 650px; border-top: 5px solid #ffc107;">
        <div class="card-header bg-warning text-dark text-center py-3">
            <h4 class="mb-0 fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i>Donor Cooldown Period Active</h4>
        </div>
        <div class="card-body p-4 text-center">
            <p class="fs-5">Dear <strong>{{ $donor->full_name }}</strong>,</p>
            <p class="text-muted">Your voluntary registration has been recorded, but according to <strong>NBTC clinical guidelines</strong>, your physiological rest period has not yet elapsed.</p>
            
            <div class="alert alert-danger text-start my-4">
                <ul class="mb-0">
                    <li><strong>Blood Group:</strong> {{ $donor->blood_group }}</li>
                    <li><strong>Gender:</strong> {{ $donor->gender }} ({{ $donor->gender === 'Male' ? '90 Days' : '120 Days' }} mandatory rest interval)</li>
                    <li><strong>Days Remaining in Cooldown:</strong> <span class="fw-bold fs-5 text-danger">{{ $daysRemaining }} Days</span></li>
                    @if ($nextEligibleDate)
                        <li><strong>Eligible to Donate On or After:</strong> <span class="fw-bold text-success">{{ $nextEligibleDate }}</span></li>
                    @endif
                </ul>
            </div>
            
            <a href="/" class="btn btn-primary px-4 mt-2"><i class="fa-solid fa-house me-2"></i>Back to Home</a>
        </div>
    </div>
</div>

</body>
</html>
