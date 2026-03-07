<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use App\Models\Treatment;
use Illuminate\Http\Request;

class PaymentPlanController extends Controller
{
    public function show($treatmentId)
    {
        $plan = PaymentPlan::with('treatment.medicalHistory.patient')
            ->where('id_treatment', $treatmentId)
            ->firstOrFail();

        return view('payment_plan.show', compact('plan'));
    }

    public function registerPayment(Request $request, $treatmentId)
    {
        $plan = PaymentPlan::where('id_treatment', $treatmentId)->firstOrFail();

        $request->validate([
            'amount'         => 'required|numeric|min:1|max:' . $plan->outstanding_balance,
            'payment_method' => 'required|in:Cash,QR',
            'receipt'        => 'nullable|string|max:255',
        ]);

        $newPaid    = $plan->amount_paid + $request->amount;
        $newBalance = $plan->total_amount - $newPaid;

        $plan->update([
            'amount_paid'         => $newPaid,
            'outstanding_balance' => $newBalance,
            'payment_date'        => now(),
            'payment_method'      => $request->payment_method,
            'receipt'             => $request->receipt,
        ]);

        return redirect()->back()->with('success', 'Pago registrado. Saldo: Bs. ' . $newBalance);
    }
}