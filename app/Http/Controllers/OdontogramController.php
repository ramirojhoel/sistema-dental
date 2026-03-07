<?php

namespace App\Http\Controllers;

use App\Models\Odontogram;
use Illuminate\Http\Request;

class OdontogramController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_history'  => 'required|exists:medical_history,id_history',
            'type'        => 'required|in:Start,Progress,Final',
            'date'        => 'required|date',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|string|max:500',
        ]);

        Odontogram::create($validated);

        return redirect()->back()->with('success', 'Odontograma registrado.');
    }

    public function destroy($id)
    {
        Odontogram::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Odontograma eliminado.');
    }
}