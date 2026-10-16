<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\DonorController;
/*
|--------------------------------------------------------------------------

| 1. PUBLIC USER-FACING ROUTES
| :--- |

*/
// Home Page: Displays Hero Banner, Aggregated Live Stock, and Upcoming Camps
Route::get('/', function () {
    $batches = DB::table('blood_batches')
        ->where('status', 'Available')
        ->where('testing_clearance_status', 'Cleared')
        ->where('expiry_date', '>=', now()->toDateString())
        ->get();
    $camps = DB::table('camps')
        ->where('camp_date', '>=', now()->toDateString())
        ->orderBy('camp_date', 'asc')
        ->take(4)
        ->get();
    return view('home', compact('batches', 'camps'));
})->name('home');
// Stock Availability Directory (Search & Filter)
Route::get('/stock-directory', function (Request $request) {
    $bloodGroup = $request->query('blood_group');
    $component = $request->query('component');
    $district = $request->query('district');
    $query = DB::table('blood_batches')
        ->leftJoin('blood_banks', 'blood_batches.blood_bank_id', '=', 'blood_banks.id')
        ->select('blood_batches.*', 'blood_banks.name as blood_bank_name', 'blood_banks.district as blood_bank_district')
        ->where('blood_batches.status', 'Available')
        ->where('blood_batches.testing_clearance_status', 'Cleared')
        ->where('blood_batches.expiry_date', '>=', now()->toDateString());
    if ($bloodGroup && $bloodGroup !== 'All') {
        $query->where('blood_batches.blood_group', $bloodGroup);
    }
    if ($component && $component !== 'All') {
        $query->where('blood_batches.component', $component);
    }
    if ($district) {
        $query->where('blood_banks.district', 'like', "%{$district}%");
    }
    $batches = $query->orderBy('blood_batches.expiry_date', 'asc')->get();
    return view('stock_directory', compact('batches', 'bloodGroup', 'component', 'district'));
})->name('stock.directory');
// Voluntary Donor Registration & Digital Certificate
Route::get('/register-donor', [DonorController::class, 'create'])->name('donor.register');
Route::post('/register-donor', [DonorController::class, 'store'])->name('donor.store');
Route::get('/donor-certificate/{id}', [DonorController::class, 'showCertificate'])->name('donor.certificate');
// Emergency Blood Requisition Form & Submission
Route::get('/request-blood', function () {
    $bloodBanks = DB::table('blood_banks')->get();
    return view('request_blood', compact('bloodBanks'));
})->name('blood.request');
Route::post('/request-blood', function (Request $request) {
    $validated = $request->validate([
        'patient_name'      => 'required|string|max:150',
        'age'               => 'required|integer|min:1|max:120',
        'admitted_hospital' => 'required|string|max:200',
        'treating_doctor'   => 'required|string|max:150',
        'diagnosis'         => 'required|string|max:255',
        'attendant_phone'   => 'required|string|max:20',
        'blood_group'       => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        'component'         => 'required|string|max:100',
        'units_required'    => 'required|integer|min:1|max:10',
        'urgency_level'     => 'required|string|max:50',
        'blood_bank_id'     => 'required|exists:blood_banks,id',
    ]);
    $requisitionId = DB::table('blood_requisitions')->insertGetId([
        'patient_name'      => $validated['patient_name'],
        'age'               => $validated['age'],
        'admitted_hospital' => $validated['admitted_hospital'],
        'treating_doctor'   => $validated['treating_doctor'],
        'diagnosis'         => $validated['diagnosis'],
        'attendant_phone'   => $validated['attendant_phone'],
        'blood_group'       => $validated['blood_group'],
        'component'         => $validated['component'],
        'units_required'    => $validated['units_required'],
        'urgency_level'     => $validated['urgency_level'],
        'blood_bank_id'     => $validated['blood_bank_id'],
        'workflow_state'    => 'Pending',
        'created_at'        => now(),
        'updated_at'        => now(),
    ]);
    return redirect()->route('blood.request.status', ['id' => $requisitionId])
        ->with('success', 'Emergency blood requisition submitted successfully.');
})->name('blood.request.store');
// Requisition Status Display
Route::get('/request-status/{id}', function ($id) {
    $requisition = DB::table('blood_requisitions')
        ->leftJoin('blood_banks', 'blood_requisitions.blood_bank_id', '=', 'blood_banks.id')
        ->select('blood_requisitions.*', 'blood_banks.name as blood_bank_name')
        ->where('blood_requisitions.id', $id)
        ->first();
    if (!$requisition) {
        return redirect()->route('blood.request')->with('error', 'Requisition record not found.');
    }
    return view('request_status', compact('requisition'));
})->name('blood.request.status');
/*
|--------------------------------------------------------------------------

| 2. OFFICER AUTHENTICATION ROUTES
| :--- |

*/
Route::get('/login', function () {
    if (session()->has('admin_user')) {
        return redirect()->route('admin.dashboard');
    }
    return view('login');
})->name('login');
Route::post('/login', function (Request $request) {
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);
    $user = DB::table('users')->where('email', $request->email)->first();
    if ($user && Hash::check($request->password, $user->password)) {
        if ($user->role === 'admin') {
            session(['admin_user' => $user]);
            return redirect()->route('admin.dashboard');
        }
        return back()->with('error', 'Unauthorized access role.');
    }
    return back()->with('error', 'Invalid email address or password.');
})->name('login.post');
Route::get('/logout', function () {
    session()->forget('admin_user');
    return redirect()->route('login')->with('success', 'Logged out successfully.');
})->name('logout');
/*
|--------------------------------------------------------------------------

| 3. ADMINISTRATIVE CONTROL PANEL ROUTES
| :--- |

*/
Route::prefix('admin')->group(function () {
    // Admin Dashboard Overview
    Route::get('/dashboard', function () {
        if (!session()->has('admin_user')) {
            return redirect()->route('login')->with('error', 'Please sign in as officer first.');
        }
        // Summary Metric Counts
        $totalUnits = DB::table('blood_batches')
            ->where('status', 'Available')
            ->where('testing_clearance_status', 'Cleared')
            ->where('expiry_date', '>=', now()->toDateString())
            ->count();
        $pendingRequests = DB::table('blood_requisitions')
            ->where('workflow_state', 'Pending')
            ->count();
        $totalDonors = DB::table('donors')->count();
        $totalCamps = DB::table('camps')->count();
        // Complete $metrics array covering all possible Blade template keys
        $metrics = [
            'total_units'          => $totalUnits,
            'cleared_units'        => $totalUnits,
            'units'                => $totalUnits,
            'pending_requests'     => $pendingRequests,
            'pending_requisitions' => $pendingRequests,
            'pending'              => $pendingRequests,
            'total_donors'         => $totalDonors,
            'registered_donors'    => $totalDonors,
            'donors'               => $totalDonors,
            'scheduled_camps'      => $totalCamps,
            'total_camps'          => $totalCamps,
            'active_camps'         => $totalCamps,
            'camps'                => $totalCamps,
        ];
        // Safe Requisitions Query (Selects raw records and maps properties in PHP to avoid MySQL 1054 error)
        $recentRequisitions = DB::table('blood_requisitions')
            ->leftJoin('blood_banks', 'blood_requisitions.blood_bank_id', '=', 'blood_banks.id')
            ->select('blood_requisitions.*', 'blood_banks.name as blood_bank_name')
            ->orderBy('blood_requisitions.id', 'desc')
            ->take(15)
            ->get()
            ->map(function ($req) {
                // Ensure attendant_contact property is set
                $req->attendant_contact = $req->attendant_contact 
                    ?? $req->attendant_contact_no 
                    ?? $req->attendant_phone 
                    ?? $req->phone 
                    ?? 'N/A';
                // Ensure diagnosis_indication property is set
                $req->diagnosis_indication = $req->diagnosis_indication 
                    ?? $req->clinical_indication 
                    ?? $req->diagnosis 
                    ?? 'Emergency';
                return $req;
            });
        $requisitions = $recentRequisitions;
        // Fetch inventory batches
        $batches = DB::table('blood_batches')
            ->leftJoin('blood_banks', 'blood_batches.blood_bank_id', '=', 'blood_banks.id')
            ->select('blood_batches.*', 'blood_banks.name as blood_bank_name')
            ->orderBy('blood_batches.id', 'desc')
            ->take(15)
            ->get();
        // Fetch scheduled camps
        $camps = DB::table('camps')->orderBy('camp_date', 'asc')->get();
        return view('admin.dashboard', compact(
            'metrics',
            'recentRequisitions',
            'requisitions',
            'batches',
            'camps'
        ));
    })->name('admin.dashboard');
    // Update Requisition Status and Automatically Deduct Stock on Approval
    Route::post('/update-requisition-status', function (Request $request) {
        if (!session()->has('admin_user')) {
            return redirect()->route('login');
        }
        $request->validate([
            'requisition_id' => 'required|exists:blood_requisitions,id',
            'workflow_state' => 'required|in:Pending,Cross-Match Verified,Approved / Issued,Fulfilled,Rejected',
        ]);
        $requisition = DB::table('blood_requisitions')->where('id', $request->requisition_id)->first();
        // Automated inventory stock deduction upon requisition approval
        if ($request->workflow_state === 'Approved / Issued' && $requisition->workflow_state !== 'Approved / Issued') {
            $matchingBatches = DB::table('blood_batches')
                ->where('blood_group', $requisition->blood_group)
                ->where('component', $requisition->component)
                ->where('status', 'Available')
                ->where('testing_clearance_status', 'Cleared')
                ->where('expiry_date', '>=', now()->toDateString())
                ->take($requisition->units_required)
                ->pluck('id');
            if ($matchingBatches->count() < $requisition->units_required) {
                return back()->with('error', 'Insufficient cleared units available in stock to approve this requisition.');
            }
            DB::table('blood_batches')
                ->whereIn('id', $matchingBatches)
                ->update(['status' => 'Issued', 'updated_at' => now()]);
        }
        DB::table('blood_requisitions')
            ->where('id', $request->requisition_id)
            ->update([
                'workflow_state' => $request->workflow_state,
                'updated_at'     => now(),
            ]);
        return back()->with('success', "Requisition #{$request->requisition_id} updated to {$request->workflow_state}.");
    })->name('admin.update.requisition');
});