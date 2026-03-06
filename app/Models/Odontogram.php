<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Odontogram extends Model
{
    protected $table = 'odontograms';
    protected $primaryKey = 'id_odontogram';

    protected $fillable = [
        'id_history', 'type', 'date', 'description', 'image_url'
    ];

    public function medicalHistory(): BelongsTo
    {
        return $this->belongsTo(MedicalHistory::class, 'id_history', 'id_history');
    }
}