<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\PaymentPlan;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
    $search = $request->get('search');

    $treatments = Treatment::with('medicalHistory.patient')
        ->when($search, function ($query, $search) {
            $query->whereHas('medicalHistory.patient', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('CI',         'like', "%{$search}%");
            })
            ->orWhere('category',    'like', "%{$search}%")
            ->orWhere('status',      'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
        })
        ->orderByDesc('start_date')
        ->paginate(15);

    return view('treatments.index', compact('treatments', 'search'));
    }

    public function create(Request $request)
    {
    $historyId = $request->query('history');
    return view('treatments.create', compact('historyId'));
    }

    public function store(Request $request)
    {
    $historyId = $request->input('id_history');

    $validator = \Validator::make($request->all(), [
        'id_history'  => 'required|exists:medical_history,id_history',
        'id_user'     => 'required|exists:users,id_user',
        'category'    => 'required|in:Orthodontics,Endodontics,Periodontics,Oral Surgery,Prosthodontics,Implants,Whitening,Cleaning,Aesthetics,Surgery,Other',
        'description' => 'required|string',
        'cost'        => 'required|numeric|min:0',
        'start_date'  => 'required|date',
        'end_date'    => 'nullable|date',
        'status'      => 'required|in:In progress,Completed,Suspended',
    ]);

    if ($validator->fails()) {
        return redirect()
            ->route('treatments.create', ['history' => $historyId])
            ->withErrors($validator)
            ->withInput();
    }

    $validated = $validator->validated();

    $treatment = Treatment::create($validated);

    PaymentPlan::create([
        'id_treatment'        => $treatment->id_treatment,
        'total_amount'        => $validated['cost'],
        'amount_paid'         => 0,
        'outstanding_balance' => $validated['cost'],
    ]);

    return redirect()->route('treatments.show', $treatment->id_treatment)
                     ->with('success', 'Tratamiento creado con plan de pago.');
    }

    public function show($id)
    {
        $treatment = Treatment::with([
            'medicalHistory.patient',
            'user',
            'paymentPlan',
            'tracking'
        ])->findOrFail($id);

        return view('treatments.show', compact('treatment'));
    }

    public function edit($id)
    {
        $treatment = Treatment::findOrFail($id);
        return view('treatments.edit', compact('treatment'));
    }

    public function update(Request $request, $id)
    {
        $treatment = Treatment::findOrFail($id);

        $validated = $request->validate([
            'status'      => 'required|in:In progress,Completed,Suspended',
            'end_date'    => 'nullable|date',
            'description' => 'required|string',
            'cost'        => 'required|numeric|min:0',
        ]);

        $treatment->update($validated);

        return redirect()->route('treatments.show', $treatment->id_treatment)
                         ->with('success', 'Tratamiento actualizado.');
    }

    public function destroy($id)
    {
        Treatment::findOrFail($id)->delete();
        return redirect()->route('treatments.index')
                         ->with('success', 'Tratamiento eliminado.');
    }
}
