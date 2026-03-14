<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $user = Auth::user();

        if (!$user->active) {
            Auth::logout();
            return response()->json([
                'message' => 'Tu cuenta está inactiva'
            ], 403);
        }

        $token = $user->createToken('dental-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'        => $user->id_user,
                'name'      => $user->name,
                'last_name' => $user->last_name,
                'email'     => $user->email,
                'role'      => $user->role,
                'specialty' => $user->specialty,
                'phone'     => $user->phone,
                'active'    => $user->active,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id'        => $user->id_user,
            'name'      => $user->name,
            'last_name' => $user->last_name,
            'email'     => $user->email,
            'role'      => $user->role,
            'specialty' => $user->specialty,
            'phone'     => $user->phone,
            'active'    => $user->active,
        ]);
    }
}