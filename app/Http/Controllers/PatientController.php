<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
    $search = $request->get('search');

    $patients = Patient::when($search, function ($query, $search) {
            $query->where('first_name',   'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('CI',         'like', "%{$search}%")
                  ->orWhere('phone_number','like', "%{$search}%");
        })
        ->orderBy('last_name')
        ->paginate(15);

    return view('patients.index', compact('patients', 'search'));
    }

    // ✅ Este método faltaba
    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate
        ([
            'CI'            => 'required|string|max:20|unique:patients|regex:/^[0-9]+$/',
            'first_name'    => 'required|string|max:100|regex:/^[\pL\s]+$/u',
            'last_name'     => 'required|string|max:100|regex:/^[\pL\s]+$/u',
            'date_of_birth' => 'nullable|date|before:today',
            'sex'           => 'nullable|in:M,F,Other',
            'phone_number'  => 'nullable|string|max:20|regex:/^[0-9]+$/',
            'address'       => 'nullable|string|max:250',
            'blood_type'    => 'nullable|string|max:5',
            'allergies'     => 'nullable|string',
            'emergency_contact_name'  => 'nullable|string|max:100|regex:/^[\pL\s]+$/u',
            'emergency_contact_phone' => 'nullable|string|max:20|regex:/^[0-9]+$/',
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

        $validated = $request->validate
        ([
            'first_name'   => 'required|string|max:100|regex:/^[\pL\s]+$/u',
            'last_name'    => 'required|string|max:100|regex:/^[\pL\s]+$/u',
            'phone_number' => 'nullable|string|max:20|regex:/^[0-9]+$/',
            'address'      => 'nullable|string|max:250',
            'allergies'    => 'nullable|string',
            'emergency_contact_name'  => 'nullable|string|max:100|regex:/^[\pL\s]+$/u',
            'emergency_contact_phone' => 'nullable|string|max:20|regex:/^[0-9]+$/',
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