<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentPlan extends Model
{
    protected $table = 'payment_plan';
    protected $primaryKey = 'id_payment';

    protected $fillable = [
        'id_treatment', 'total_amount', 'amount_paid',
        'outstanding_balance', 'payment_date', 'payment_method', 'receipt'
    ];

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class, 'id_treatment', 'id_treatment');
    }
}