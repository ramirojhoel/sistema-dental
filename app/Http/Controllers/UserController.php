<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   public function index()
    {
    $users      = User::orderBy('last_name')->paginate(15);
    $totalAdmin = User::where('role', 'admin')->count();
    $totalDentist = User::where('role', 'dentist')->count();
    $totalReceptionist = User::where('role', 'receptionist')->count();

    return view('users.index', compact(
        'users', 'totalAdmin', 'totalDentist', 'totalReceptionist'
    ));
    }
    
    public function create()
    {
        return view('users.create');
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
        'active'    => 'nullable|boolean',
    ]);

    $validated['password'] = Hash::make($validated['password']);
    $validated['active']   = $request->input('active', 1);

    User::create($validated);

    return redirect()->route('users.index')
    ->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'name'      => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'email'     => 'required|email|unique:users,email,' . $id . ',id_user',
        'role'      => 'required|in:admin,dentist,receptionist',
        'specialty' => 'nullable|string|max:100',
        'phone'     => 'nullable|string|max:20',
        'active'    => 'nullable|boolean',
        'password'  => 'nullable|string|min:8|confirmed',
    ]);

    if (!empty($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }

    $validated['active'] = $request->input('active', 1);

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

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
}