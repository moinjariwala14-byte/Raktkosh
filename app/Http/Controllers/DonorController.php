<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DonorController extends Controller
{
    /**
     * Show Voluntary Donor Registration Form
     */
    public function create()
    {
        return view('register_donor');
    }

    /**
     * Process Donor Registration and Generate Certificate or Cooldown Notice
     */
    public function store(Request $request)
    {
        // 1. Form Validation
        $validated = $request->validate([
            'full_name'          => 'required|string|max:150',
            'gender'             => 'required|in:Male,Female,Other',
            'dob'                => 'required|date|before:today',
            'phone'              => 'required|string|min:10|max:15',
            'email'              => 'nullable|email|max:150',
            'address'            => 'required|string|max:255',
            'pincode'            => 'nullable|string|max:10',
            'district'           => 'required|string|max:100',
            'city_taluka'        => 'nullable|string|max:100',
            'blood_group'        => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'donation_type'      => 'nullable|string|max:50',
            'last_donation_date' => 'nullable|date|before_or_equal:today',
        ]);

        $gender = $validated['gender'];
        $lastDonation = !empty($validated['last_donation_date']) ? Carbon::parse($validated['last_donation_date']) : null;
        $isEligible = true;
        $daysRemaining = 0;
        $nextEligibleDate = null;

        // 2. NBTC Cooldown Rules (90 days for male, 120 days for female)
        if ($lastDonation) {
            $cooldownRequired = ($gender === 'Male') ? 90 : 120;
            $daysSinceLast = (int) $lastDonation->diffInDays(Carbon::now());

            if ($daysSinceLast < $cooldownRequired) {
                $isEligible = false;
                $daysRemaining = $cooldownRequired - $daysSinceLast;
                $nextEligibleDate = $lastDonation->copy()->addDays($cooldownRequired)->format('d-m-Y');
            }
        }

        // 3. Unique Certificate Number
        $certSerial = 'CERT-RCS-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

        // 4. Save to Database (including pincode with fallback)
        $donorId = DB::table('donors')->insertGetId([
            'full_name'          => $validated['full_name'],
            'gender'             => $validated['gender'],
            'dob'                => $validated['dob'],
            'phone'              => $validated['phone'],
            'email'              => $validated['email'] ?? null,
            'address'            => $validated['address'],
            'pincode'            => $request->input('pincode') ?: '396445',
            'district'           => $validated['district'],
            'city_taluka'        => $validated['city_taluka'] ?? 'Navsari',
            'blood_group'        => $validated['blood_group'],
            'donation_type'      => $validated['donation_type'] ?? 'Voluntary',
            'last_donation_date' => $validated['last_donation_date'] ?? null,
            'is_eligible'        => $isEligible ? 1 : 0,
            'certificate_number' => $certSerial,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        $donor = DB::table('donors')->where('id', $donorId)->first();

        // 5. Render Cooldown Notice if Ineligible
        if (!$isEligible) {
            return view('donor_cooldown', [
                'donor'            => $donor,
                'daysRemaining'    => $daysRemaining,
                'nextEligibleDate' => $nextEligibleDate,
            ]);
        }

        // 6. Render Certificate if Eligible
        return view('donor_certificate', [
            'donor' => $donor
        ]);
    }

    /**
     * Fallback Direct View by ID
     */
    public function showCertificate($id)
    {
        $donor = DB::table('donors')->where('id', $id)->first();
        if (!$donor) {
            return redirect('/register-donor')->with('error', 'Donor record not found.');
        }

        if (!$donor->is_eligible) {
            return view('donor_cooldown', [
                'donor'            => $donor,
                'daysRemaining'    => 30,
                'nextEligibleDate' => 'Eligible soon'
            ]);
        }

        return view('donor_certificate', ['donor' => $donor]);
    }
}
