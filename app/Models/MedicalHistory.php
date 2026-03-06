<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicalHistory extends Model
{
    protected $table = 'medical_history';
    protected $primaryKey = 'id_history';

    protected $fillable = [
        'id_patient', 'id_user', 'opening_date',
        'reason_for_consultation', 'background', 'current_medications'
    ];

    // Relaciones hacia arriba
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'id_patient', 'id_patient');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relaciones hacia abajo
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'id_history', 'id_history');
    }

    public function xrays(): HasMany
    {
        return $this->hasMany(Xray::class, 'id_history', 'id_history');
    }

    public function odontograms(): HasMany
    {
        return $this->hasMany(Odontogram::class, 'id_history', 'id_history');
    }
}