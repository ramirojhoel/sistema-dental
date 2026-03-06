<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicalAppointmentController;
use App\Http\Controllers\TreatmentController;

// ── AUTH (rutas públicas) ──────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── RUTAS PROTEGIDAS (requieren login) ────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('patients',     PatientController::class);
    Route::resource('appointments', MedicalAppointmentController::class);
    Route::resource('treatments',   TreatmentController::class);

});









