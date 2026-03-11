<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\MedicalAppointment;
use App\Models\Treatment;
use App\Models\User;
use App\Models\PaymentPlan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Totales generales ──────────────────────
        $totalPatients     = Patient::count();
        $totalAppointments = MedicalAppointment::count();
        $totalTreatments   = Treatment::count();
        $totalDentists     = User::where('role', 'dentist')->where('active', true)->count();

        // ── Citas de hoy ───────────────────────────
        $todayAppointments = MedicalAppointment::with(['patient', 'user'])
            ->whereDate('date', Carbon::today())
            ->orderBy('hour')
            ->get();

        // ── Citas por estado ───────────────────────
        $pendingCount   = MedicalAppointment::where('state', 'Pending')->count();
        $completedCount = MedicalAppointment::where('state', 'Completed')->count();
        $cancelledCount = MedicalAppointment::where('state', 'Cancelled')->count();

        // ── Tratamientos por estado ────────────────
        $inProgressCount = Treatment::where('status', 'In progress')->count();
        $completedTreatments = Treatment::where('status', 'Completed')->count();

        // ── Ingresos ───────────────────────────────
        $totalIncome    = PaymentPlan::sum('total_amount');
        $totalCollected = PaymentPlan::sum('amount_paid');
        $totalPending   = PaymentPlan::sum('outstanding_balance');
        
        // ── Próximas citas (5) ─────────────────────
        $upcomingAppointments = MedicalAppointment::with(['patient', 'user'])
            ->whereDate('date', '>=', Carbon::today())
            ->where('state', 'Pending')
            ->orderBy('date')
            ->orderBy('hour')
            ->take(5)
            ->get();

        // ── Últimos pacientes (5) ──────────────────
        $recentPatients = Patient::orderByDesc('created_at')->take(5)->get();

        return view('dashboard', compact(
            'totalPatients',
            'totalAppointments',
            'totalTreatments',
            'totalDentists',
            'todayAppointments',
            'pendingCount',
            'completedCount',
            'cancelledCount',
            'inProgressCount',
            'completedTreatments',
            'totalIncome',
            'totalCollected',
            'totalPending',
            'upcomingAppointments',
            'recentPatients'
        ));
    }
}