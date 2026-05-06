<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicalAppointmentController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\PaymentPlanController;
use App\Http\Controllers\XrayController;
use App\Http\Controllers\OdontogramController;

// ── AUTH 
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // ── SOLO ADMIN ────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // ── ADMIN Y DENTISTA ──────────────────────────
    Route::middleware('role:admin,dentist')->group(function () {
        // Historial médico
        Route::get('patients/{patientId}/history',        [MedicalHistoryController::class, 'index'])->name('medical_history.index');
        Route::get('patients/{patientId}/history/create', [MedicalHistoryController::class, 'create'])->name('medical_history.create');
        Route::post('history',                            [MedicalHistoryController::class, 'store'])->name('medical_history.store');
        Route::get('history/{id}',                        [MedicalHistoryController::class, 'show'])->name('medical_history.show');
        Route::get('history/{id}/edit',                   [MedicalHistoryController::class, 'edit'])->name('medical_history.edit');
        Route::put('history/{id}',                        [MedicalHistoryController::class, 'update'])->name('medical_history.update');

        // Tratamientos
        Route::resource('treatments', TreatmentController::class);

        // Radiografías
        Route::post('xrays',        [XrayController::class, 'store'])->name('xrays.store');
        Route::delete('xrays/{id}', [XrayController::class, 'destroy'])->name('xrays.destroy');
        Route::get('xrays/{id}', [XrayController::class, 'show'])->name('xrays.show');

        // Odontogramas
        Route::post('odontograms',        [OdontogramController::class, 'store'])->name('odontograms.store');
        Route::delete('odontograms/{id}', [OdontogramController::class, 'destroy'])->name('odontograms.destroy');

        // Seguimiento
        Route::get('treatments/{treatmentId}/tracking', [TrackingController::class, 'index'])->name('tracking.index');
        Route::post('tracking',                         [TrackingController::class, 'store'])->name('tracking.store');
        Route::put('tracking/{id}',                     [TrackingController::class, 'update'])->name('tracking.update');

        // Pagos
        Route::get('treatments/{treatmentId}/payment',  [PaymentPlanController::class, 'show'])->name('payment.show');
        Route::post('treatments/{treatmentId}/payment', [PaymentPlanController::class, 'registerPayment'])->name('payment.register');

        // ── REPORTES ──────────────────────────────────
        Route::get('reports/patients',     [\App\Http\Controllers\ReportController::class, 'patients'])->name('reports.patients');
        Route::get('reports/appointments', [\App\Http\Controllers\ReportController::class, 'appointments'])->name('reports.appointments');
        Route::get('reports/treatments',   [\App\Http\Controllers\ReportController::class, 'treatments'])->name('reports.treatments');
        Route::get('reports/financial',    [\App\Http\Controllers\ReportController::class, 'financial'])->name('reports.financial');

        // ── RUTA RAÍZ ──────────────────────────────
        Route::get('/', function () {
        return redirect()->route('login');
        });

    });

    // ── TODOS LOS ROLES ────────────────────────────
    Route::resource('patients',     PatientController::class);
    Route::resource('appointments', MedicalAppointmentController::class);

});