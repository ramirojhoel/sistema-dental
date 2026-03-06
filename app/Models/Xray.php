<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Xray extends Model
{
    protected $table = 'xrays';
    protected $primaryKey = 'id_xray';

    protected $fillable = [
        'id_history', 'type', 'date', 'archive_url', 'observations'
    ];

    public function medicalHistory(): BelongsTo
    {
        return $this->belongsTo(MedicalHistory::class, 'id_history', 'id_history');
    }
}