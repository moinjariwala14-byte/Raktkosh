@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h4 class="card-title fw-bold mb-0 text-warning">
                    <i class="fa-solid fa-truck-medical me-2"></i>Patient Blood Requisition Form
                </h4>
                <small class="text-white-50">Direct emergency requisitions are reviewed by transfusion officers in real-time</small>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('request.blood') }}" method="POST">
                    @csrf

                    <h5 class="fw-bold text-secondary border-bottom pb-2 mb-3">1. Patient & Hospital Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Patient Full Name *</label>
                            <input type="text" name="patient_name" class="form-control" required value="{{ old('patient_name') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Age *</label>
                            <input type="number" name="age" min="0" max="120" class="form-control" required value="{{ old('age') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Admitted Hospital Name *</label>
                            <input type="text" name="admitted_hospital" class="form-control" placeholder="e.g. Civil Hospital" required value="{{ old('admitted_hospital') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Treating Doctor Name *</label>
                            <input type="text" name="treating_doctor" class="form-control" placeholder="Dr. ..." required value="{{ old('treating_doctor') }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Clinical Indication / Diagnosis *</label>
                            <input type="text" name="diagnosis_indication" class="form-control" placeholder="e.g. Major Surgery, Trauma, Dialysis" required value="{{ old('diagnosis_indication') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Attendant Contact No. *</label>
                            <input type="tel" name="attendant_contact" maxlength="10" class="form-control" placeholder="10-digit number" required value="{{ old('attendant_contact') }}">
                        </div>
                    </div>

                    <h5 class="fw-bold text-secondary border-bottom pb-2 mb-3">2. Required Component Details</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Blood Group *</label>
                            <select name="blood_group" class="form-select" required>
                                <option value="">Select Group</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Bombay Phenotype'] as $bg)
                                    <option value="{{ $bg }}">{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Component Type *</label>
                            <select name="component" class="form-select" required>
                                <option value="Whole Blood">Whole Blood</option>
                                <option value="PRBC">PRBC (Packed Red Cells)</option>
                                <option value="Platelet Concentrate">Platelet Concentrate</option>
                                <option value="FFP">FFP (Fresh Frozen Plasma)</option>
                                <option value="Cryoprecipitate">Cryoprecipitate</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Units Required *</label>
                            <input type="number" name="units_required" min="1" max="10" value="1" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Urgency Level *</label>
                            <select name="urgency_level" class="form-select" required>
                                <option value="Elective">Elective (Planned Procedure)</option>
                                <option value="Emergency" selected>Emergency (Within 4-6 hours)</option>
                                <option value="SOS">SOS / Life-Threatening (Immediate)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Target Blood Bank Center *</label>
                            <select name="blood_bank_id" class="form-select" required>
                                <option value="">Select Blood Bank</option>
                                @foreach($bloodBanks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }} ({{ $bank->district }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-warning btn-lg px-4 fw-semibold">
                            <i class="fa-solid fa-paper-plane me-1"></i> Submit Emergency Requisition
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
