<?php

namespace App\Http\Controllers;

use App\Models\CalendarNote;
use App\Models\MedicalAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year  = $request->get('year',  Carbon::now()->year);

        $appointments = MedicalAppointment::with(['patient', 'user'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $notes = CalendarNote::where('id_user', Auth::id())
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        return view('calendar.index', compact(
            'appointments', 'notes', 'month', 'year'
        ));
    }

    public function storeNote(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:100',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'color'       => 'required|in:teal,blue,purple,red,amber',
        ]);

        CalendarNote::create([
            'id_user'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'date'        => $request->date,
            'color'       => $request->color,
        ]);

        return redirect()->back()->with('success', 'Nota agregada correctamente.');
    }

    public function destroyNote($id)
    {
        CalendarNote::where('id', $id)
            ->where('id_user', Auth::id())
            ->delete();

        return redirect()->back()->with('success', 'Nota eliminada.');
    }
}