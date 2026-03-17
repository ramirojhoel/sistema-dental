<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalAppointment extends Model
{
    protected $table = 'medical_appointments';
    protected $primaryKey = 'id_appointment';

    protected $fillable = [
    'id_patient', 'id_user', 'date', 'hour',
    'appointment_type', 'state', 'notes', 'duration_min'
    
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'id_patient', 'id_patient');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}