<?php

namespace App\Http\Controllers;

use App\Models\MedicalAppointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class MedicalAppointmentController extends Controller
{
    public function index(Request $request)
{
    $search    = $request->get('search');
    $dateFrom  = $request->get('date_from');
    $dateTo    = $request->get('date_to');
    $state     = $request->get('state');

    $appointments = MedicalAppointment::with(['patient', 'user'])
        ->when($search, function ($query, $search) {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('CI',         'like', "%{$search}%");
            });
        })
        ->when($dateFrom, function ($query, $dateFrom) {
            $query->where('date', '>=', $dateFrom);
        })
        ->when($dateTo, function ($query, $dateTo) {
            $query->where('date', '<=', $dateTo);
        })
        ->when($state, function ($query, $state) {
            $query->where('state', $state);
        })
        ->orderBy('date')
        ->orderBy('hour')
        ->paginate(20);

    return view('appointments.index', compact(
        'appointments', 'search', 'dateFrom', 'dateTo', 'state'
    ));
}

    public function create()
    {
        $patients = Patient::orderBy('last_name')->get();
        $dentists = User::whereIn('role', ['admin', 'dentist'])->get();

        return view('appointments.create', compact('patients', 'dentists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_patient'       => 'required|exists:patients,id_patient',
            'id_user'          => 'required|exists:users,id_user',
            'date'             => 'required|date',
            'hour'             => 'required',
            'appointment_type' => 'required|in:Review,Treatment,Emergency',
            'state'            => 'required|in:Pending,Completed,Canceled',
            'reason'           => 'nullable|string',
            'duration_min'     => 'nullable|integer|min:10|max:180',
        ]);

        MedicalAppointment::create($validated);

        return redirect()->route('appointments.index')
                         ->with('success', 'Cita agendada correctamente.');
    }

    public function show($id)
    {
        $appointment = MedicalAppointment::with(['patient', 'user'])
            ->findOrFail($id);

        return view('appointments.show', compact('appointment'));
    }

    public function edit($id)
    {
        $appointment = MedicalAppointment::findOrFail($id);
        $patients    = Patient::orderBy('last_name')->get();
        $dentists    = User::whereIn('role', ['admin', 'dentist'])->get();

        return view('appointments.edit', compact('appointment', 'patients', 'dentists'));
    }

    public function update(Request $request, $id)
    {
        $appointment = MedicalAppointment::findOrFail($id);

        $validated = $request->validate([
            'id_patient'       => 'required|exists:patients,id_patient',
            'id_user'          => 'required|exists:users,id_user',
            'date'             => 'required|date',
            'hour'             => 'required',
            'appointment_type' => 'required|in:Review,Treatment,Emergency',
            'state'            => 'required|in:Pending,Completed,Canceled',
            'reason'           => 'nullable|string',
            'duration_min'     => 'nullable|integer',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment->id_appointment)
                         ->with('success', 'Cita actualizada.');
    }

    public function destroy($id)
    {
        MedicalAppointment::findOrFail($id)->delete();

        return redirect()->route('appointments.index')
                         ->with('success', 'Cita eliminada.');
    }
}