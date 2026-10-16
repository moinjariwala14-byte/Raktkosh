@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-danger text-white py-3">
                <h4 class="card-title fw-bold mb-0">
                    <i class="fa-solid fa-id-card me-2"></i>Voluntary Donor Registration Portal
                </h4>
                <small class="text-white-50">Complies with National Blood Transfusion Council (NBTC) eligibility guidelines</small>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('donor.register') }}" method="POST">
                    @csrf

                    <h5 class="fw-bold text-secondary border-bottom pb-2 mb-3">1. Personal & Contact Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name *</label>
                            <input type="text" name="full_name" class="form-control" placeholder="e.g. Rahul Sharma" required value="{{ old('full_name') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Gender *</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male (90 days cooldown)</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female (120 days cooldown)</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Date of Birth *</label>
                            <input type="date" name="dob" class="form-control" required value="{{ old('dob') }}">
                            <small class="text-muted">Must be 18 to 65 years old</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mobile Number *</label>
                            <input type="tel" name="phone" maxlength="10" class="form-control" placeholder="10-digit mobile" required value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="donor@example.com" value="{{ old('email') }}">
                        </div>
                    </div>

                    <h5 class="fw-bold text-secondary border-bottom pb-2 mb-3">2. Residential Address</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Street / Area Address *</label>
                            <input type="text" name="address" class="form-control" placeholder="House no, street name, landmark" required value="{{ old('address') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">District *</label>
                            <input type="text" name="district" class="form-control" placeholder="e.g. Navsari" required value="{{ old('district') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">City / Taluka *</label>
                            <input type="text" name="city_taluka" class="form-control" placeholder="e.g. Chikhli" required value="{{ old('city_taluka') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pincode *</label>
                            <input type="text" name="pincode" maxlength="6" class="form-control" placeholder="6-digit pincode" required value="{{ old('pincode') }}">
                        </div>
                    </div>

                    <h5 class="fw-bold text-secondary border-bottom pb-2 mb-3">3. Donation History & Clinical Category</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Blood Group *</label>
                            <select name="blood_group" class="form-select" required>
                                <option value="">Select Blood Group</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Bombay Phenotype'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Donation Type *</label>
                            <select name="donation_type" class="form-select" required>
                                <option value="Voluntary" {{ old('donation_type') == 'Voluntary' ? 'selected' : '' }}>Voluntary</option>
                                <option value="Replacement" {{ old('donation_type') == 'Replacement' ? 'selected' : '' }}>Replacement</option>
                                <option value="Apheresis" {{ old('donation_type') == 'Apheresis' ? 'selected' : '' }}>Apheresis (Platelets/Plasma)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Last Donation Date</label>
                            <input type="date" name="last_donation_date" class="form-control" value="{{ old('last_donation_date') }}">
                            <small class="text-muted">Leave empty if donating for the 1st time</small>
                        </div>
                    </div>

                    <div class="alert alert-info py-2 small">
                        <i class="fa-solid fa-circle-info me-1"></i> <strong>Mandatory Criteria:</strong> Minimum weight 45 kg, minimum hemoglobin 12.5 g/dL. Clinical mini-physical screening will be conducted before blood collection.
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-danger btn-lg px-4 fw-semibold">
                            <i class="fa-solid fa-check me-1"></i> Register as Donor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
