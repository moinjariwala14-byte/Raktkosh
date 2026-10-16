@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="p-5 mb-4 bg-danger text-white rounded-3 shadow">
    <div class="container-fluid py-3">
        <h1 class="display-5 fw-bold"><i class="fa-solid fa-droplet me-2"></i>Red Cross Transfusion Services</h1>
        <p class="col-md-8 fs-5">
            Centralized inventory tracking and live blood component availability across regional blood banks and transfusion centers.
        </p>
        <div class="d-flex gap-2">
            <a href="{{ route('stock.directory') }}" class="btn btn-light btn-lg px-4 fw-semibold text-danger">
                <i class="fa-solid fa-magnifying-glass me-1"></i> Check Live Stock
            </a>
            <a href="{{ route('request.blood') }}" class="btn btn-warning btn-lg px-4 fw-semibold">
                <i class="fa-solid fa-truck-medical me-1"></i> Emergency Requisition
            </a>
            <a href="{{ route('donor.register') }}" class="btn btn-outline-light btn-lg px-4 fw-semibold">
                <i class="fa-solid fa-user-plus me-1"></i> Register as Donor
            </a>
        </div>
    </div>
</div>

<!-- Live Stock Overview Cards -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-cubes-stacked me-2 text-danger"></i>Live Component Stock Summary</h4>
    <a href="{{ route('stock.directory') }}" class="text-danger text-decoration-none fw-semibold">View Full Directory &rarr;</a>
</div>

<div class="row g-3 mb-5">
    @forelse($liveStockCounts as $stock)
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 h-100" style="border-left: 4px solid #c8102e !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-danger fs-6">{{ $stock->blood_group }}</span>
                        <small class="text-muted fw-semibold">{{ $stock->component }}</small>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stock->count }} <span class="fs-6 text-muted fw-normal">Units</span></h3>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-secondary mb-0">No cleared units in stock currently.</div>
        </div>
    @endforelse
</div>

<!-- Scheduled Blood Donation Camps -->
<h4 class="fw-bold text-dark mb-3"><i class="fa-solid fa-calendar-days me-2 text-danger"></i>Upcoming Blood Donation Camps</h4>
<div class="row g-4">
    @forelse($camps as $camp)
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="fw-bold text-danger mb-1">{{ $camp->camp_title }}</h5>
                        <span class="badge bg-secondary">{{ \Carbon\Carbon::parse($camp->camp_date)->format('d M Y') }}</span>
                    </div>
                    <p class="text-muted small mb-2"><i class="fa-solid fa-users me-1"></i> Organized by: <strong>{{ $camp->organizing_institution }}</strong></p>
                    <p class="mb-1"><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $camp->venue_address }}, {{ $camp->city_taluka }}, {{ $camp->district }}</p>
                    <p class="mb-2"><i class="fa-regular fa-clock me-1 text-muted"></i> {{ $camp->start_time }} to {{ $camp->end_time }}</p>
                    <small class="text-muted"><i class="fa-solid fa-phone me-1"></i> Contact: {{ $camp->nodal_officer_contact }}</small>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border text-muted">No scheduled blood donation camps at this time.</div>
        </div>
    @endforelse
</div>
@endsection
