<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalHistoryController extends Controller
{
    // 📋 Listar historiales de un paciente
    public function index($patientId)
    {
        $patient  = Patient::findOrFail($patientId);
        $histories = MedicalHistory::with(['user', 'treatments', 'xrays'])
            ->where('id_patient', $patientId)
            ->orderByDesc('opening_date')
            ->get();

        return view('medical_history.index', compact('patient', 'histories'));
    }

    // 👁️ Ver un historial completo
    public function show($id)
    {
        $history = MedicalHistory::with([
            'patient',
            'user',
            'treatments.paymentPlan',
            'treatments.tracking',
            'xrays',
            'odontograms'
        ])->findOrFail($id);

        return view('medical_history.show', compact('history'));
    }

    // 📝 Formulario nuevo historial
    public function create($patientId)
    {
        $patient  = Patient::findOrFail($patientId);
        $dentists = User::where('role', 'dentist')->get();

        return view('medical_history.create', compact('patient', 'dentists'));
    }

    // 💾 Guardar nuevo historial
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_patient'             => 'required|exists:patients,id_patient',
            'opening_date'           => 'required|date',
            'reason_for_consultation'=> 'nullable|string',
            'background'             => 'nullable|string',
            'current_medications'    => 'nullable|string',
        ]);

        // Asignar el dentista logueado automáticamente
        $validated['id_user'] = Auth::id();

        $history = MedicalHistory::create($validated);

        return redirect()->route('medical_history.show', $history->id_history)
                         ->with('success', 'Historial creado correctamente.');
    }

    // ✏️ Formulario editar
    public function edit($id)
    {
        $history = MedicalHistory::with('patient')->findOrFail($id);
        return view('medical_history.edit', compact('history'));
    }

    // 🔄 Actualizar historial
    public function update(Request $request, $id)
    {
        $history = MedicalHistory::findOrFail($id);

        $validated = $request->validate([
            'reason_for_consultation'=> 'nullable|string',
            'background'             => 'nullable|string',
            'current_medications'    => 'nullable|string',
        ]);

        $history->update($validated);

        return redirect()->route('medical_history.show', $history->id_history)
                         ->with('success', 'Historial actualizado.');
    }
}