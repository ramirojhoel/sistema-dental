<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicalAppointmentController;
use App\Http\Controllers\TreatmentController;

// Ruta de inicio
Route::get('/', function () {
    return view('welcome');
});

// Pacientes
Route::resource('patients', PatientController::class);

// Citas
Route::resource('appointments', MedicalAppointmentController::class);

// Tratamientos
Route::resource('treatments', TreatmentController::class);