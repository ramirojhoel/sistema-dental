<?php

namespace App\Http\Controllers;

use App\Models\Xray;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class XrayController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_history'   => 'required|exists:medical_history,id_history',
            'type'         => 'required|in:Panoramic,Periapical,Bitewing,Occlusal,Cephalometric',
            'date'         => 'required|date',
            'archive_url'  => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'observations' => 'nullable|string',
        ]);

        $path = $request->file('archive_url')->store('xrays', 'public');

        Xray::create([
            'id_history'   => $request->id_history,
            'type'         => $request->type,
            'date'         => $request->date,
            'archive_url'  => $path,
            'observations' => $request->observations,
        ]);

        return redirect()->back()->with('success', 'Radiografía subida correctamente.');
    }

    public function show($id)
    {
        $xray = Xray::with('medicalHistory.patient')->findOrFail($id);
        return view('xrays.show', compact('xray'));
    }

    public function destroy($id)
    {
        $xray = Xray::findOrFail($id);
        Storage::disk('public')->delete($xray->archive_url);
        $xray->delete();
        return redirect()->back()->with('success', 'Radiografía eliminada.');
    }
}