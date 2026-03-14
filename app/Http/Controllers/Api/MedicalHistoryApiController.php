<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalHistory;
use App\Models\Patient;

class MedicalHistoryApiController extends Controller
{
    public function index($patientId)
    {
        $patient = Patient::findOrFail($patientId);

        $histories = MedicalHistory::with(['treatments.paymentPlan'])
            ->where('id_patient', $patientId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'patient'   => $patient,
            'histories' => $histories,
        ]);
    }

    public function show($id)
    {
        $history = MedicalHistory::with([
            'patient',
            'treatments.paymentPlan',
            'tracking',
            'odontograms',
            'xrays',
        ])->findOrFail($id);

        return response()->json($history);
    }

    public function store(Request $request, $patientId)
    {
        Patient::findOrFail($patientId);

        $request->validate([
            'reason_for_visit' => 'required|string',
        ]);

        $history = MedicalHistory::create([
            'id_patient'          => $patientId,
            'reason_for_visit'    => $request->reason_for_visit,
            'diagnosis'           => $request->diagnosis,
            'allergies'           => $request->allergies,
            'previous_diseases'   => $request->previous_diseases,
            'current_medications' => $request->current_medications,
            'blood_pressure'      => $request->blood_pressure,
            'pulse'               => $request->pulse,
            'temperature'         => $request->temperature,
            'observations'        => $request->observations,
        ]);

        $history->load('patient');

        return response()->json($history, 201);
    }

    public function update(Request $request, $id)
    {
        $history = MedicalHistory::findOrFail($id);

        $request->validate([
            'reason_for_visit' => 'required|string',
        ]);

        $history->update([
            'reason_for_visit'    => $request->reason_for_visit,
            'diagnosis'           => $request->diagnosis,
            'allergies'           => $request->allergies,
            'previous_diseases'   => $request->previous_diseases,
            'current_medications' => $request->current_medications,
            'blood_pressure'      => $request->blood_pressure,
            'pulse'               => $request->pulse,
            'temperature'         => $request->temperature,
            'observations'        => $request->observations,
        ]);

        $history->load('patient');

        return response()->json($history);
    }
}