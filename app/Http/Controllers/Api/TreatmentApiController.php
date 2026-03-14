<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Treatment;

class TreatmentApiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $treatments = Treatment::with(['medicalHistory.patient', 'paymentPlan'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('medicalHistory.patient', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%$search%")
                       ->orWhere('last_name',  'like', "%$search%")
                       ->orWhere('CI',         'like', "%$search%");
                })
                ->orWhere('category',    'like', "%$search%")
                ->orWhere('status',      'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($treatments);
    }

    public function show($id)
    {
        $treatment = Treatment::with([
            'medicalHistory.patient',
            'medicalHistory.tracking',
            'medicalHistory.odontograms',
            'medicalHistory.xrays',
            'paymentPlan',
        ])->findOrFail($id);

        return response()->json($treatment);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_history'  => 'required|exists:medical_history,id_history',
            'category'    => 'required|string',
            'status'      => 'required|string',
            'start_date'  => 'required|date',
            'cost'        => 'required|numeric|min:0',
        ]);

        $treatment = Treatment::create($request->all());
        $treatment->load(['medicalHistory.patient', 'paymentPlan']);

        return response()->json($treatment, 201);
    }

    public function update(Request $request, $id)
    {
        $treatment = Treatment::findOrFail($id);

        $request->validate([
            'category'   => 'required|string',
            'status'     => 'required|string',
            'start_date' => 'required|date',
            'cost'       => 'required|numeric|min:0',
        ]);

        $treatment->update($request->all());

        // Actualizar payment plan si viene
        if ($request->has('amount_paid') || $request->has('outstanding_balance')) {
            if ($treatment->paymentPlan) {
                $treatment->paymentPlan->update([
                    'total_amount'        => $request->cost ?? $treatment->cost,
                    'amount_paid'         => $request->amount_paid ?? $treatment->paymentPlan->amount_paid,
                    'outstanding_balance' => $request->outstanding_balance ?? $treatment->paymentPlan->outstanding_balance,
                ]);
            }
        }

        $treatment->load(['medicalHistory.patient', 'paymentPlan']);

        return response()->json($treatment);
    }

    public function destroy($id)
    {
        $treatment = Treatment::findOrFail($id);
        $treatment->delete();
        return response()->json(['message' => 'Tratamiento eliminado']);
    }
}