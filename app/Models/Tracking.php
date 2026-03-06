<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tracking extends Model
{
    protected $table = 'tracking';
    protected $primaryKey = 'id_tracking';

    protected $fillable = [
        'id_treatment', 'id_patient', 'date',
        'observations', 'scheduled_appointment'
    ];

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class, 'id_treatment', 'id_treatment');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'id_patient', 'id_patient');
    }
}