<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::orderBy('last_name')->paginate(15);
        return view('patients.index', compact('patients'));
    }

    public function show($id)
    {
        $patient = Patient::with([
            'medicalHistories.treatments',
            'appointments',
            'tracking'
        ])->findOrFail($id);

        return view('patients.show', compact('patient'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'CI'            => 'required|string|max:20|unique:patients',
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
            'sex'           => 'nullable|in:M,F,Other',
            'phone_number'  => 'nullable|string|max:20',
            'blood_type'    => 'nullable|string|max:5',
        ]);

        $patient = Patient::create($validated);

        return redirect()->route('patients.show', $patient->id_patient)
                         ->with('success', 'Paciente registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'first_name'  => 'required|string|max:100',
            'last_name'   => 'required|string|max:100',
            'phone_number'=> 'nullable|string|max:20',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.show', $patient->id_patient)
                         ->with('success', 'Paciente actualizado.');
    }

    public function destroy($id)
    {
        Patient::findOrFail($id)->delete();
        return redirect()->route('patients.index')
                         ->with('success', 'Paciente eliminado.');
    }
}