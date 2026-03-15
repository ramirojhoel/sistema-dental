<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Patient extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'id_patient';

    protected $fillable = [
    'CI', 'first_name', 'last_name', 'date_of_birth',
    'sex', 'address', 'phone_number', 'occupation',
    'blood_type', 'allergies',
    'emergency_contact_name', 'emergency_contact_phone',  // ← agregar esto
];

    // Relaciones
    public function medicalHistories(): HasMany
    {
        return $this->hasMany(MedicalHistory::class, 'id_patient', 'id_patient');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(MedicalAppointment::class, 'id_patient', 'id_patient');
    }

    public function tracking(): HasMany
    {
        return $this->hasMany(Tracking::class, 'id_patient', 'id_patient');
    }

    // Tratamientos a través de historial médico
    public function treatments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Treatment::class,
            MedicalHistory::class,
            'id_patient',   
            'id_history',   
            'id_patient',   
            'id_history'    
        );
    }
}