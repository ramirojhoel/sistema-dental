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

    // ✅ Este método faltaba
    public function create()
    {
        return view('patients.create');
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
            'address'       => 'nullable|string|max:250',
            'blood_type'    => 'nullable|string|max:5',
            'allergies'     => 'nullable|string',
        ]);

        $patient = Patient::create($validated);

        return redirect()->route('patients.show', $patient->id_patient)
                         ->with('success', 'Paciente registrado correctamente.');
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

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:250',
            'allergies'    => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.show', $patient->id_patient)
                         ->with('success', 'Paciente actualizado correctamente.');
    }

    public function destroy($id)
    {
        Patient::findOrFail($id)->delete();
        return redirect()->route('patients.index')
                         ->with('success', 'Paciente eliminado.');
    }
}