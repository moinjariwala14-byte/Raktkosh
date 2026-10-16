<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodBatch;
use App\Models\BloodBank;
use App\Models\Donor;
use App\Models\DonorScreening;
use App\Models\Camp;
use App\Models\BloodRequisition;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    // Home dashboard showing component counters
    public function index()
    {
        $liveStockCounts = BloodBatch::where('status', 'Available')
            ->where('testing_clearance_status', 'Cleared')
            ->where('expiry_date', '>=', now()->toDateString())
            ->selectRaw('blood_group, component, count(*) as count')
            ->groupBy('blood_group', 'component')
            ->get();

        $camps = Camp::where('camp_date', '>=', now()->toDateString())
            ->orderBy('camp_date', 'asc')
            ->take(4)
            ->get();

        return view('public.home', compact('liveStockCounts', 'camps'));
    }

    // e-RaktKosh Advanced Stock Directory Filter
    public function stockDirectory(Request $request)
    {
        $query = BloodBatch::with('bloodBank')
            ->where('status', 'Available')
            ->where('testing_clearance_status', 'Cleared')
            ->where('expiry_date', '>=', now()->toDateString());

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }
        if ($request->filled('component')) {
            $query->where('component', $request->component);
        }
        if ($request->filled('district')) {
            $query->whereHas('bloodBank', function ($q) use ($request) {
                $q->where('district', 'like', '%' . $request->district . '%');
            });
        }

        $results = $query->paginate(15);
        return view('public.stock-search', compact('results'));
    }

    // Donor Registration & Eligibility Check
    public function registerDonor(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('public.donor-register');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date|before:-18 years|after:-65 years',
            'phone' => 'required|digits:10',
            'blood_group' => 'required',
            'district' => 'required',
            'city_taluka' => 'required',
            'pincode' => 'required|digits:6',
            'address' => 'required|string',
            'donation_type' => 'required|in:Voluntary,Replacement,Apheresis',
            'last_donation_date' => 'nullable|date|before:today',
        ]);

        $donor = new Donor($validated);

        // Verification of gender-based cooldown
        $eligibility = $donor->checkDonationEligibility();
        $donor->is_eligible = $eligibility['eligible'];
        $donor->certificate_number = 'CERT-RCS-' . strtoupper(Str::random(8));
        $donor->save();

        if (!$eligibility['eligible']) {
            return redirect()->back()->with('warning', $eligibility['message']);
        }

        return redirect()->back()->with('success', 'Registration complete. Your Donor Certificate Ref is: ' . $donor->certificate_number);
    }

    // Patient Requisition Registration
    public function storeEmergencyRequest(Request $request)
    {
        if ($request->isMethod('get')) {
            $bloodBanks = BloodBank::all();
            return view('public.request-blood', compact('bloodBanks'));
        }

        $validated = $request->validate([
            'patient_name' => 'required|string|max:100',
            'age' => 'required|integer|min:0|max:120',
            'admitted_hospital' => 'required|string',
            'treating_doctor' => 'required|string',
            'diagnosis_indication' => 'required|string',
            'attendant_contact' => 'required|digits:10',
            'blood_group' => 'required',
            'component' => 'required',
            'units_required' => 'required|integer|min:1|max:10',
            'urgency_level' => 'required|in:Elective,Emergency,SOS',
            'blood_bank_id' => 'required|exists:blood_banks,id',
        ]);

        $req = BloodRequisition::create(array_merge($validated, [
            'workflow_state' => 'Pending'
        ]));

        return redirect()->back()->with('success', "Emergency requisition submitted with Request ID #RCS-REQ-{$req->id}. Current State: Pending Cross-Match.");
    }
}
