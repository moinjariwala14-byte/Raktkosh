@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-danger text-white py-3 text-center">
                <h4 class="fw-bold mb-0"><i class="fa-solid fa-lock me-2"></i>Officer Login</h4>
                <small class="text-white-50">Red Cross Society Central Transfusion Portal</small>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email', 'admin@redcross.org') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Enter Password">
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-semibold py-2">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> Sign In
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
