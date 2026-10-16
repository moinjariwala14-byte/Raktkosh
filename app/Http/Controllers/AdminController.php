<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodBatch;
use App\Models\BloodRequisition;
use App\Models\Donor;
use App\Models\DonorScreening;
use App\Models\Camp;
use App\Models\BloodBank;

class AdminController extends Controller
{
    public function dashboard()
    {
        $metrics = [
            'total_units' => BloodBatch::where('status', 'Available')->where('expiry_date', '>=', now())->count(),
            'pending_requests' => BloodRequisition::where('workflow_state', 'Pending')->count(),
            'total_donors' => Donor::count(),
            'scheduled_camps' => Camp::where('camp_date', '>=', now())->count(),
        ];

        $recentRequisitions = BloodRequisition::with('bloodBank')->orderBy('id', 'desc')->take(6)->get();

        return view('admin.dashboard', compact('metrics', 'recentRequisitions'));
    }

    // Process Clinical Workflow Pipeline
    public function updateRequisitionWorkflow(Request $request, $id)
    {
        $request->validate([
            'workflow_state' => 'required|in:Pending,Cross-Match Verified,Approved / Issued,Fulfilled,Rejected',
            'clinical_remarks' => 'nullable|string'
        ]);

        $requisition = BloodRequisition::findOrFail($id);
        $newState = $request->input('workflow_state');

        // Inventory deduction if marked as Approved / Issued
        if ($newState === 'Approved / Issued' && $requisition->workflow_state !== 'Approved / Issued') {
            $availableUnits = BloodBatch::where('blood_bank_id', $requisition->blood_bank_id)
                ->where('blood_group', $requisition->blood_group)
                ->where('component', $requisition->component)
                ->where('status', 'Available')
                ->where('testing_clearance_status', 'Cleared')
                ->where('expiry_date', '>=', now())
                ->take($requisition->units_required)
                ->get();

            if ($availableUnits->count() < $requisition->units_required) {
                return redirect()->back()->with('error', "Insufficient verified stock to approve {$requisition->units_required} unit(s). Only {$availableUnits->count()} available.");
            }

            // Reserve/Issue each unit
            foreach ($availableUnits as $unit) {
                $unit->update(['status' => 'Issued']);
            }
        }

        $requisition->update([
            'workflow_state' => $newState,
            'clinical_remarks' => $request->input('clinical_remarks')
        ]);

        return redirect()->back()->with('success', "Requisition #{$id} state moved to {$newState}.");
    }

    // Record Clinical Screening Mini-Physical
    public function storeScreening(Request $request)
    {
        $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'blood_bank_id' => 'required|exists:blood_banks,id',
            'weight_kg' => 'required|numeric',
            'hemoglobin_g_dl' => 'required|numeric',
            'pulse_rate' => 'required|integer',
            'blood_pressure' => 'required|string',
            'screened_by_officer' => 'required|string',
        ]);

        $evaluation = DonorScreening::evaluateFitness(
            $request->weight_kg,
            $request->hemoglobin_g_dl,
            $request->pulse_rate
        );

        DonorScreening::create([
            'donor_id' => $request->donor_id,
            'blood_bank_id' => $request->blood_bank_id,
            'weight_kg' => $request->weight_kg,
            'hemoglobin_g_dl' => $request->hemoglobin_g_dl,
            'pulse_rate' => $request->pulse_rate,
            'blood_pressure' => $request->blood_pressure,
            'screening_result' => $evaluation['result'],
            'deferral_reason' => ($evaluation['result'] !== 'Fit') ? $evaluation['reasons'] : null,
            'screened_by_officer' => $request->screened_by_officer,
        ]);

        // Update Donor Master Status
        Donor::where('id', $request->donor_id)->update([
            'is_eligible' => ($evaluation['result'] === 'Fit')
        ]);

        return redirect()->back()->with('success', "Screening completed. Result: {$evaluation['result']}");
    }
}
