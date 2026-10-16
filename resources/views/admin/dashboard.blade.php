@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">Central Transfusion Administration</h2>
        <span class="text-muted">Master control panel for stock verification & clinical orders</span>
    </div>
    <span class="badge bg-success p-2"><i class="fa-solid fa-circle-dot me-1"></i> System Online</span>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-danger text-white p-3">
            <h6 class="text-uppercase text-white-50">Cleared Units in Stock</h6>
            <h2 class="fw-bold mb-0">{{ $metrics['total_units'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-warning text-dark p-3">
            <h6 class="text-uppercase text-muted">Pending Requisitions</h6>
            <h2 class="fw-bold mb-0">{{ $metrics['pending_requests'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-info text-white p-3">
            <h6 class="text-uppercase text-white-50">Registered Donors</h6>
            <h2 class="fw-bold mb-0">{{ $metrics['total_donors'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-dark text-white p-3">
            <h6 class="text-uppercase text-white-50">Scheduled Camps</h6>
            <h2 class="fw-bold mb-0">{{ $metrics['scheduled_camps'] }}</h2>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-heart-pulse me-2 text-danger"></i>Clinical Requisitions Workflow Pipeline</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Req #</th>
                        <th>Patient Details</th>
                        <th>Admitted Hospital / Doctor</th>
                        <th>Required Spec</th>
                        <th>Urgency</th>
                        <th>Current State</th>
                        <th>Action Pipeline</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRequisitions as $req)
                    <tr>
                        <td class="fw-bold">#REQ-{{ $req->id }}</td>
                        <td>
                            <div class="fw-bold">{{ $req->patient_name }} ({{ $req->age }} yrs)</div>
                            <small class="text-muted"><i class="fa-solid fa-phone me-1"></i>{{ $req->attendant_contact }}</small>
                        </td>
                        <td>
                            <div>{{ $req->admitted_hospital }}</div>
                            <small class="text-muted">Dr. {{ $req->treating_doctor }} ({{ $req->diagnosis_indication }})</small>
                        </td>
                        <td>
                            <span class="badge bg-danger">{{ $req->blood_group }}</span>
                            <span class="badge bg-secondary">{{ $req->component }}</span>
                            <div class="small fw-semibold mt-1">{{ $req->units_required }} Unit(s)</div>
                        </td>
                        <td>
                            <span class="badge {{ $req->urgency_level === 'SOS' ? 'badge-sos text-white' : ($req->urgency_level === 'Emergency' ? 'bg-danger' : 'bg-secondary') }}">
                                {{ $req->urgency_level }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-dark">{{ $req->workflow_state }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.requisition.workflow', $req->id) }}" method="POST" class="d-flex gap-1">
                                @csrf
                                <select name="workflow_state" class="form-select form-select-sm" style="width: 170px;">
                                    <option value="Pending" {{ $req->workflow_state == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Cross-Match Verified" {{ $req->workflow_state == 'Cross-Match Verified' ? 'selected' : '' }}>Cross-Match Verified</option>
                                    <option value="Approved / Issued" {{ $req->workflow_state == 'Approved / Issued' ? 'selected' : '' }}>Approved / Issued</option>
                                    <option value="Fulfilled" {{ $req->workflow_state == 'Fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                                    <option value="Rejected" {{ $req->workflow_state == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-dark">Update</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
