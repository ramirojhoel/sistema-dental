<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8|confirmed',
            'role'      => 'required|in:admin,dentist,receptionist',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'last_name' => $request->last_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'specialty' => $request->specialty,
            'phone'     => $request->phone,
            'active'    => $request->active ?? true,
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $id . ',id_user',
            'role'      => 'required|in:admin,dentist,receptionist',
        ]);

        $data = [
            'name'      => $request->name,
            'last_name' => $request->last_name,
            'email'     => $request->email,
            'role'      => $request->role,
            'specialty' => $request->specialty,
            'phone'     => $request->phone,
            'active'    => $request->active,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado']);
    }
}