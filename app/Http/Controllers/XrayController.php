<?php

namespace App\Http\Controllers;

use App\Models\Xray;
use Illuminate\Http\Request;

class XrayController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_history'   => 'required|exists:medical_history,id_history',
            'type'         => 'required|in:Panoramic,Periapical,Bitewing',
            'date'         => 'required|date',
            'archive_url'  => 'required|string|max:500',
            'observations' => 'nullable|string',
        ]);

        Xray::create($validated);

        return redirect()->back()->with('success', 'Radiografía registrada.');
    }

    public function destroy($id)
    {
        Xray::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Radiografía eliminada.');
    }
}