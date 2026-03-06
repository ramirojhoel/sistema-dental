<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\MedicalHistory;
use App\Models\PaymentPlan;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_history'  => 'required|exists:medical_history,id_history',
            'id_user'     => 'required|exists:users,id_user',
            'category'    => 'required|in:Orthodontics,Endodontics,Aesthetics,Surgery',
            'description' => 'required|string',
            'cost'        => 'required|numeric|min:0',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after:start_date',
            'status'      => 'required|in:In progress,Completed,Suspended',
        ]);

        $treatment = Treatment::create($validated);

        // Crear plan de pago automáticamente
        PaymentPlan::create([
            'id_treatment'       => $treatment->id_treatment,
            'total_amount'       => $validated['cost'],
            'amount_paid'        => 0,
            'outstanding_balance'=> $validated['cost'],
        ]);

        return redirect()->back()->with('success', 'Tratamiento registrado con plan de pago.');
    }

    public function registerPayment(Request $request, $id)
    {
        $plan = PaymentPlan::where('id_treatment', $id)->firstOrFail();

        $request->validate([
            'amount'         => 'required|numeric|min:1|max:' . $plan->outstanding_balance,
            'payment_method' => 'required|in:Cash,QR',
        ]);

        $newPaid    = $plan->amount_paid + $request->amount;
        $newBalance = $plan->total_amount - $newPaid;

        $plan->update([
            'amount_paid'        => $newPaid,
            'outstanding_balance'=> $newBalance,
            'payment_date'       => now(),
            'payment_method'     => $request->payment_method,
        ]);

        return back()->with('success', 'Pago registrado correctamente.');
    }
}