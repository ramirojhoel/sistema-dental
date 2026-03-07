<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('last_name')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:8|confirmed',
            'role'      => 'required|in:admin,dentist,receptionist',
            'specialty' => 'nullable|string|max:100',
            'phone'     => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('users.index')
                         ->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'role'      => 'required|in:admin,dentist,receptionist',
            'specialty' => 'nullable|string|max:100',
            'phone'     => 'nullable|string|max:20',
            'active'    => 'boolean',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')
                         ->with('success', 'Usuario actualizado.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')
                         ->with('success', 'Usuario eliminado.');
    }
}