<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Treatment extends Model
{
    protected $table = 'treatment';
    protected $primaryKey = 'id_treatment';

    protected $fillable = [
        'id_history', 'id_user', 'category',
        'description', 'cost', 'start_date', 'end_date', 'status'
    ];

    public function medicalHistory(): BelongsTo
    {
        return $this->belongsTo(MedicalHistory::class, 'id_history', 'id_history');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function tracking(): HasMany
    {
        return $this->hasMany(Tracking::class, 'id_treatment', 'id_treatment');
    }

    public function paymentPlan(): HasOne
    {
        return $this->hasOne(PaymentPlan::class, 'id_treatment', 'id_treatment');
    }
}