@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title fw-bold text-dark mb-0"><i class="fa-solid fa-filter me-2 text-danger"></i>e-RaktKosh Live Stock Availability Finder</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('stock.directory') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Blood Group</label>
                <select name="blood_group" class="form-select">
                    <option value="">All Groups</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Bombay Phenotype'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Component</label>
                <select name="component" class="form-select">
                    <option value="">All Components</option>
                    @foreach(['Whole Blood', 'PRBC', 'FFP', 'Platelet Concentrate', 'Cryoprecipitate'] as $comp)
                        <option value="{{ $comp }}" {{ request('component') == $comp ? 'selected' : '' }}>{{ $comp }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">District</label>
                <input type="text" name="district" value="{{ request('district') }}" class="form-control" placeholder="e.g. Navsari, Surat">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-redcross w-100 fw-semibold"><i class="fa-solid fa-magnifying-glass me-1"></i> Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Barcode #</th>
                        <th>Blood Bank / Nodal Center</th>
                        <th>Group</th>
                        <th>Component</th>
                        <th>Volume</th>
                        <th>Testing Status</th>
                        <th>Expiry Date</th>
                        <th>Availability</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $unit)
                    <tr>
                        <td class="fw-bold font-monospace">{{ $unit->unit_barcode }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $unit->bloodBank->name }}</div>
                            <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i>{{ $unit->bloodBank->district }}, {{ $unit->bloodBank->state }} | Helpline: {{ $unit->bloodBank->helpline_phone }}</small>
                        </td>
                        <td><span class="badge bg-danger fs-6">{{ $unit->blood_group }}</span></td>
                        <td>{{ $unit->component }}</td>
                        <td>{{ $unit->bag_volume }}</td>
                        <td><span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>{{ $unit->testing_clearance_status }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($unit->expiry_date)->format('d M Y') }}</td>
                        <td><span class="badge bg-primary">In Stock</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No stock matching the specified criteria.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $results->links('pagination::bootstrap-5') }}
</div>
@endsection
