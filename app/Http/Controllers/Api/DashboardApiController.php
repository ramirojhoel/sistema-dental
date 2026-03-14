<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\MedicalAppointment;
use App\Models\Treatment;
use App\Models\User;
use App\Models\PaymentPlan;
use Carbon\Carbon;

class DashboardApiController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalPatients     = Patient::count();
        $totalAppointments = MedicalAppointment::count();
        $totalTreatments   = Treatment::count();
        $totalDentists     = User::where('role', 'dentist')->where('active', true)->count();

        // Citas por estado
        $pendingCount   = MedicalAppointment::where('state', 'Pending')->count();
        $completedCount = MedicalAppointment::where('state', 'Completed')->count();
        $cancelledCount = MedicalAppointment::where('state', 'Cancelled')->count();
        $inProgressCount = Treatment::where('status', 'In progress')->count();

        // Finanzas
        $totalIncome    = PaymentPlan::sum('total_amount');
        $totalCollected = PaymentPlan::sum('amount_paid');
        $totalPending   = PaymentPlan::sum('outstanding_balance');

        // Citas de hoy
        $todayAppointments = MedicalAppointment::with(['patient', 'user'])
            ->whereDate('date', Carbon::today())
            ->orderBy('hour')
            ->get();

        // Próximas citas
        $upcomingAppointments = MedicalAppointment::with(['patient', 'user'])
            ->where('state', 'Pending')
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('hour')
            ->take(5)
            ->get();

        // Últimos pacientes
        $recentPatients = Patient::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'stats' => [
                'total_patients'      => $totalPatients,
                'total_appointments'  => $totalAppointments,
                'total_treatments'    => $totalTreatments,
                'total_dentists'      => $totalDentists,
                'pending_count'       => $pendingCount,
                'completed_count'     => $completedCount,
                'cancelled_count'     => $cancelledCount,
                'in_progress_count'   => $inProgressCount,
            ],
            'finances' => [
                'total_income'    => $totalIncome,
                'total_collected' => $totalCollected,
                'total_pending'   => $totalPending,
            ],
            'today_appointments'    => $todayAppointments,
            'upcoming_appointments' => $upcomingAppointments,
            'recent_patients'       => $recentPatients,
        ]);
    }
}