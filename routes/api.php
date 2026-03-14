<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\PatientApiController;
use App\Http\Controllers\Api\AppointmentApiController;
use App\Http\Controllers\Api\TreatmentApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\MedicalHistoryApiController;

// ── Públicas ──────────────────────────
Route::post('/login', [AuthApiController::class, 'login']);

// ── Protegidas con Sanctum ────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AuthApiController::class, 'me']);

    // Dashboard
    Route::get('/dashboard', [DashboardApiController::class, 'index']);

    // Pacientes
    Route::apiResource('/patients', PatientApiController::class);

    // Citas
    Route::apiResource('/appointments', AppointmentApiController::class);

    // Tratamientos
    Route::apiResource('/treatments', TreatmentApiController::class);

    // Usuarios
    Route::apiResource('/users', UserApiController::class);

    // Historial médico
    Route::get('/patients/{id}/history', [MedicalHistoryApiController::class, 'index']);
    Route::post('/patients/{id}/history', [MedicalHistoryApiController::class, 'store']);
    Route::get('/history/{id}', [MedicalHistoryApiController::class, 'show']);
    Route::put('/history/{id}', [MedicalHistoryApiController::class, 'update']);

});