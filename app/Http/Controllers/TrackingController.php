<?php

namespace App\Http\Controllers;

use App\Models\Tracking;
use App\Models\Treatment;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index($treatmentId)
    {
        $treatment = Treatment::with('medicalHistory.patient')->findOrFail($treatmentId);
        $trackings = Tracking::where('id_treatment', $treatmentId)
            ->orderByDesc('date')
            ->get();

        return view('tracking.index', compact('treatment', 'trackings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_treatment'         => 'required|exists:treatment,id_treatment',
            'id_patient'           => 'required|exists:patients,id_patient',
            'date'                 => 'required|date',
            'observations'         => 'nullable|string',
            'scheduled_appointment'=> 'nullable|date',
        ]);

        Tracking::create($validated);

        return redirect()->back()
                         ->with('success', 'Seguimiento registrado.');
    }

    public function update(Request $request, $id)
    {
        $tracking = Tracking::findOrFail($id);

        $validated = $request->validate([
            'observations'         => 'nullable|string',
            'scheduled_appointment'=> 'nullable|date',
        ]);

        $tracking->update($validated);

        return redirect()->back()->with('success', 'Seguimiento actualizado.');
    }
}