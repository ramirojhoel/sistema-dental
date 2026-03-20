<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\MedicalAppointment;
use App\Models\Treatment;
use App\Models\PaymentPlan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // 📊 Reporte de Pacientes
    public function patients()
    {
        $patients = Patient::orderBy('last_name')->get();
        $total    = $patients->count();
        $pdf = Pdf::loadView('reports.patients', compact('patients', 'total'))
                  ->setPaper('a4', 'portrait');
        return $pdf->download('reporte-pacientes-' . date('Y-m-d') . '.pdf');
    }

    // 📅 Reporte de Citas
    public function appointments()
    {
        $appointments = MedicalAppointment::with(['patient', 'user'])
            ->orderBy('date', 'desc')->get();
        $total     = $appointments->count();
        $pending   = $appointments->where('state', 'Pending')->count();
        $completed = $appointments->where('state', 'Completed')->count();
        $cancelled = $appointments->where('state', 'Cancelled')->count();

        $pdf = Pdf::loadView('reports.appointments', compact(
            'appointments', 'total', 'pending', 'completed', 'cancelled'
        ))->setPaper('a4', 'landscape');
        return $pdf->download('reporte-citas-' . date('Y-m-d') . '.pdf');
    }

    // 🦷 Reporte de Tratamientos
    public function treatments()
    {
        $treatments  = Treatment::with(['medicalHistory.patient', 'user'])->orderBy('start_date', 'desc')->get();
        $total       = $treatments->count();
        $inProgress  = $treatments->where('status', 'In progress')->count();
        $completed   = $treatments->where('status', 'Completed')->count();
        $suspended   = $treatments->where('status', 'Suspended')->count();

        $pdf = Pdf::loadView('reports.treatments', compact(
            'treatments', 'total', 'inProgress', 'completed', 'suspended'
        ))->setPaper('a4', 'landscape');
        return $pdf->download('reporte-tratamientos-' . date('Y-m-d') . '.pdf');
    }

    // 💰 Reporte Financiero
    public function financial()
    {
        $plans         = PaymentPlan::with('treatment.medicalHistory.patient')->get();
        $totalIncome   = $plans->sum('total_amount');
        $totalCollected = $plans->sum('amount_paid');
        $totalPending  = $plans->sum('outstanding_balance');

        $pdf = Pdf::loadView('reports.financial', compact(
            'plans', 'totalIncome', 'totalCollected', 'totalPending'
        ))->setPaper('a4', 'landscape');
        return $pdf->download('reporte-financiero-' . date('Y-m-d') . '.pdf');
    }
}