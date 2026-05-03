<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users             = User::orderBy('last_name')->paginate(15);
        $totalAdmin        = User::where('role', 'admin')->count();
        $totalDentist      = User::where('role', 'dentist')->count();
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
            'name'      => 'required|string|min:3|max:50|regex:/^[\pL\s]+$/u',
            'last_name' => 'required|string|min:3|max:50|regex:/^[\pL\s]+$/u',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:8|confirmed',
            'role'      => 'required|in:admin,dentist,receptionist',
            'specialty' => 'nullable|string|min:5|max:100|regex:/^[\pL\s,]+$/u',
            'phone'     => 'nullable|digits_between:7,15',
            'active'    => 'nullable|boolean',
        ], [
            'name.required'        => 'El nombre es obligatorio.',
            'name.min'             => 'El nombre debe tener al menos 3 caracteres.',
            'name.regex'           => 'El nombre solo debe contener letras.',
            'last_name.required'   => 'El apellido es obligatorio.',
            'last_name.min'        => 'El apellido debe tener al menos 3 caracteres.',
            'last_name.regex'      => 'El apellido solo debe contener letras.',
            'email.required'       => 'El correo es obligatorio.',
            'email.email'          => 'El correo electrónico no es válido.',
            'email.unique'         => 'El correo ya está registrado.',
            'password.required'    => 'La contraseña es obligatoria.',
            'password.min'         => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'   => 'Las contraseñas no coinciden.',
            'role.required'        => 'El rol es obligatorio.',
            'specialty.min'        => 'La especialidad debe tener al menos 5 caracteres.',
            'specialty.regex'      => 'La especialidad solo debe contener letras.',
            'phone.digits_between' => 'El teléfono debe tener entre 7 y 15 dígitos.',
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
            'name'      => 'required|string|min:3|max:50|regex:/^[\pL\s]+$/u',
            'last_name' => 'required|string|min:3|max:50|regex:/^[\pL\s]+$/u',
            'email'     => 'required|email|unique:users,email,' . $id . ',id_user',
            'role'      => 'required|in:admin,dentist,receptionist',
            'specialty' => 'nullable|string|min:5|max:100|regex:/^[\pL\s,]+$/u',
            'phone'     => 'nullable|digits_between:7,15',
            'active'    => 'nullable|boolean',
            'password'  => 'nullable|string|min:8|confirmed',
        ], [
            'name.required'        => 'El nombre es obligatorio.',
            'name.min'             => 'El nombre debe tener al menos 3 caracteres.',
            'name.regex'           => 'El nombre solo debe contener letras.',
            'last_name.required'   => 'El apellido es obligatorio.',
            'last_name.min'        => 'El apellido debe tener al menos 3 caracteres.',
            'last_name.regex'      => 'El apellido solo debe contener letras.',
            'email.email'          => 'El correo electrónico no es válido.',
            'specialty.min'        => 'La especialidad debe tener al menos 5 caracteres.',
            'specialty.regex'      => 'La especialidad solo debe contener letras.',
            'phone.digits_between' => 'El teléfono debe tener entre 7 y 15 dígitos.',
            'password.min'         => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'   => 'Las contraseñas no coinciden.',
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
        $user = User::findOrFail($id);
        $user->update(['active' => false]);

        return redirect()->route('users.index')
                         ->with('success', 'Usuario desactivado correctamente.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
}