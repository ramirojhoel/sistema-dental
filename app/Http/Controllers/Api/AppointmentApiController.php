<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalAppointment;
use App\Models\Patient;
use App\Models\User;

class AppointmentApiController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->get('search');
        $dateFrom  = $request->get('date_from');
        $dateTo    = $request->get('date_to');
        $state     = $request->get('state');

        $appointments = MedicalAppointment::with(['patient', 'user'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('patient', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%$search%")
                       ->orWhere('last_name',  'like', "%$search%")
                       ->orWhere('CI',         'like', "%$search%");
                });
            })
            ->when($dateFrom, fn($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('date', '<=', $dateTo))
            ->when($state,    fn($q) => $q->where('state', $state))
            ->orderBy('date', 'desc')
            ->paginate(10);

        return response()->json($appointments);
    }

    public function show($id)
    {
        $appointment = MedicalAppointment::with(['patient', 'user'])
            ->findOrFail($id);
        return response()->json($appointment);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_patient'       => 'required|exists:patients,id_patient',
            'id_user'          => 'required|exists:users,id_user',
            'date'             => 'required|date',
            'hour'             => 'required',
            'appointment_type' => 'required|string',
            'state'            => 'required|string',
        ]);

        $appointment = MedicalAppointment::create($request->all());
        $appointment->load(['patient', 'user']);

        return response()->json($appointment, 201);
    }

    public function update(Request $request, $id)
    {
        $appointment = MedicalAppointment::findOrFail($id);

        $request->validate([
            'id_patient'       => 'required|exists:patients,id_patient',
            'id_user'          => 'required|exists:users,id_user',
            'date'             => 'required|date',
            'hour'             => 'required',
            'appointment_type' => 'required|string',
            'state'            => 'required|string',
        ]);

        $appointment->update($request->all());
        $appointment->load(['patient', 'user']);

        return response()->json($appointment);
    }

    public function destroy($id)
    {
        $appointment = MedicalAppointment::findOrFail($id);
        $appointment->delete();
        return response()->json(['message' => 'Cita eliminada']);
    }
}