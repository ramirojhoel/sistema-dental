<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;

class PatientApiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $patients = Patient::when($search, function ($query) use ($search) {
                $query->where('first_name', 'like', "%$search%")
                      ->orWhere('last_name',  'like', "%$search%")
                      ->orWhere('CI',         'like', "%$search%")
                      ->orWhere('phone_number','like', "%$search%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($patients);
    }

    public function show($id)
    {
        $patient = Patient::with([
            'medicalHistories',
            'appointments.user',
        ])->findOrFail($id);

        return response()->json($patient);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'CI'         => 'required|unique:patients,CI',
        ]);

        $patient = Patient::create($request->all());

        return response()->json($patient, 201);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'CI'         => 'required|unique:patients,CI,' . $id . ',id_patient',
        ]);

        $patient->update($request->all());

        return response()->json($patient);
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return response()->json(['message' => 'Paciente eliminado']);
    }
}