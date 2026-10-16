<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntary Donor Registration - Red Cross Society</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .register-card { max-width: 680px; margin: 40px auto; border-top: 5px solid #b30000; border-radius: 8px; }
    </style>
</head>
<body>

<!-- Navigation Header -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-danger" href="/">
            <i class="fa-solid fa-droplet me-2"></i>RED CROSS SOCIETY
        </a>
        <div class="ms-auto">
            <a class="btn btn-outline-light btn-sm me-2" href="/"><i class="fa-solid fa-house me-1"></i>Home</a>
            <a class="btn btn-danger btn-sm" href="/request-blood"><i class="fa-solid fa-truck-medical me-1"></i>Requisition</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="card register-card shadow p-4 bg-white">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-danger"><i class="fa-solid fa-user-plus me-2"></i>Voluntary Donor Registration</h3>
            <p class="text-muted small">National Blood Transfusion Council (NBTC) Regulatory Criteria</p>
        </div>

        <!-- Form Validation Errors Alert -->
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <h6 class="fw-bold mb-2"><i class="fa-solid fa-circle-exclamation me-2"></i>Please correct the following errors:</h6>
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('donor.store') }}" method="POST">
            @csrf

            <!-- Full Name -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" placeholder="e.g. Nawaz Nazir Jodliwala" value="{{ old('full_name') }}" required>
            </div>

            <!-- Gender & Date of Birth -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select" required>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male (90 days interval)</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female (120 days interval)</option>
                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" name="dob" class="form-control" value="{{ old('dob', '2002-05-15') }}" required>
                </div>
            </div>

            <!-- Phone & Email -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                    <input type="tel" name="phone" class="form-control" placeholder="10-digit mobile number" value="{{ old('phone') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}">
                </div>
            </div>

            <!-- Street Address -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Residential Address <span class="text-danger">*</span></label>
                <textarea name="address" class="form-control" rows="2" placeholder="House/Flat No., Street, Area" required>{{ old('address', 'Viraval, Navsari') }}</textarea>
            </div>

            <!-- District, City & Pincode -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">District <span class="text-danger">*</span></label>
                    <input type="text" name="district" class="form-control" value="{{ old('district', 'Navsari') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">City / Taluka</label>
                    <input type="text" name="city_taluka" class="form-control" value="{{ old('city_taluka', 'Gandevi') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Pincode <span class="text-danger">*</span></label>
                    <input type="text" name="pincode" class="form-control" value="{{ old('pincode', '396445') }}" required>
                </div>
            </div>

            <!-- Blood Group & Type -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Blood Group <span class="text-danger">*</span></label>
                    <select name="blood_group" class="form-select" required>
                        @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $grp)
                            <option value="{{ $grp }}" {{ old('blood_group', 'O+') == $grp ? 'selected' : '' }}>{{ $grp }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Donation Category</label>
                    <select name="donation_type" class="form-select">
                        <option value="Voluntary">Voluntary Donation</option>
                        <option value="Replacement">Replacement Donation</option>
                        <option value="Apheresis">Apheresis</option>
                    </select>
                </div>
            </div>

            <!-- Last Donation Date -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Last Donation Date <span class="text-muted fw-normal">(Leave empty if first-time donor)</span></label>
                <input type="date" name="last_donation_date" class="form-control" value="{{ old('last_donation_date') }}">
                <div class="form-text small">Leave empty if you have not donated blood previously.</div>
            </div>

            <button type="submit" class="btn btn-danger btn-lg w-100 shadow-sm fw-bold">
                <i class="fa-solid fa-heart me-2"></i>Register as Voluntary Donor
            </button>
        </form>
    </div>
</div>

<footer class="bg-dark text-white text-center py-4">
    <p class="mb-1">Central Blood Transfusion Services &bull; Red Cross Society</p>
    <p class="small text-secondary mb-0">District Center Navsari - 396445 | Emergency Helpline: 104 / 1910</p>
</footer>

</body>
</html>
