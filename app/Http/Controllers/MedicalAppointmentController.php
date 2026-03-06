<?php

namespace App\Http\Controllers;

use App\Models\MedicalAppointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class MedicalAppointmentController extends Controller
{
    public function index()
    {
        $appointments = MedicalAppointment::with(['patient', 'user'])
            ->orderBy('date')
            ->orderBy('hour')
            ->paginate(20);

        return view('appointments.index', compact('appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_patient'       => 'required|exists:patients,id_patient',
            'id_user'          => 'required|exists:users,id_user',
            'date'             => 'required|date|after_or_equal:today',
            'hour'             => 'required|date_format:H:i',
            'appointment_type' => 'required|in:Review,Treatment,Emergency',
            'state'            => 'required|in:Pending,Completed,Canceled',
            'reason'           => 'nullable|string',
            'duration_min'     => 'nullable|integer|min:10|max:180',
        ]);

        $appointment = MedicalAppointment::create($validated);

        return redirect()->route('appointments.index')
                         ->with('success', 'Cita agendada correctamente.');
    }

    public function updateState(Request $request, $id)
    {
        $appointment = MedicalAppointment::findOrFail($id);

        $request->validate([
            'state' => 'required|in:Pending,Completed,Canceled'
        ]);

        $appointment->update(['state' => $request->state]);

        return back()->with('success', 'Estado de cita actualizado.');
    }
}