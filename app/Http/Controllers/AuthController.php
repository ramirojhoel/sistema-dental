<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 👁️ Mostrar formulario de login
    public function showLogin()
    {
        // Si ya está logueado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // 🔐 Procesar el login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // seguridad anti-hijacking

            // Redirigir según el rol
            $role = Auth::user()->role;

            return match($role) {
                'admin'         => redirect()->route('dashboard'),
                'dentist'       => redirect()->route('patients.index'),
                'receptionist'  => redirect()->route('dashboard'),
                 default         => redirect()->route('dashboard'),
            };
        }

        // Login fallido
        return back()
            ->withErrors(['email' => 'Correo o contraseña incorrectos.'])
            ->onlyInput('email');
    }

    // 🚪 Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('success', 'Sesión cerrada correctamente.');
    }
}